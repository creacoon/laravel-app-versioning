<?php

namespace Creacoon\VersionManager;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class VersionManager
{
    /**
     * Get the current application version.
     */
    public function getCurrentVersion(): string
    {
        $config_key = config('version.config_key', 'app.version');
        return config($config_key);
    }

    /**
     * Set a new application version.
     */
    public function setVersion(string $newVersion): bool
    {
        $current_version = $this->getCurrentVersion();

        if (version_compare($newVersion, $current_version) <= 0) {
            return false;
        }

        return $this->updateConfigFile($newVersion) &&
            $this->updateRuntimeConfig($newVersion);
    }

    /**
     * Update the config file with the new version.
     */
    public function updateConfigFile(string $newVersion): bool
    {
        $config_path = $this->getConfigPath();
        $config_content = File::get($config_path);

        $version_key = config('version.version_key', 'version');
        $pattern = "/['|\"]" . preg_quote($version_key) . "['|\"](\s*=>\s*)['|\"](.*?)['|\"]/";

        $updated_content = preg_replace(
            $pattern,
            "'$version_key'\$1'$newVersion'",
            $config_content
        );

        if ($updated_content === $config_content) {
            return false;
        }

        return (bool) File::put($config_path, $updated_content);
    }

    /**
     * Update the runtime configuration.
     */
    public function updateRuntimeConfig(string $newVersion): bool
    {
        $config_key = config('version.config_key', 'app.version');
        app()['config']->set($config_key, $newVersion);

        return config($config_key) === $newVersion;
    }

    /**
     * Update the changelog file with the new version.
     */
    public function updateChangelog(string $newVersion): bool
    {
        $changelog_path = $this->getChangelogPath();

        if (!File::exists($changelog_path)) {
            return false;
        }

        $changelog_content = File::get($changelog_path);
        $unreleased_pattern = config('version.unreleased_pattern', '## [Unreleased]');

        $date = Carbon::now()->toDateString();
        $updated_changelog = str_replace(
            $unreleased_pattern,
            "$unreleased_pattern\n\n## [$newVersion] $date",
            $changelog_content
        );

        return (bool) File::put($changelog_path, $updated_changelog);
    }

    /**
     * Create a Git commit and tag for the new version.
     */
    public function handleGitOperations(string $newVersion): array
    {
        $results = [
            'success' => false,
            'messages' => [],
        ];

        try {
            if (!is_dir(base_path('.git'))) {
                $results['messages'][] = 'Not a git repository. Skipping git operations.';
                return $results;
            }

            $results['messages'][] = 'Running git operations...';

            // Determine which files to commit
            $files_to_commit = $this->getFilesToCommit();

            // Stage files
            exec('git add ' . implode(' ', $files_to_commit) . ' 2>&1', $output, $return_code);
            if ($return_code !== 0) {
                $results['messages'][] = 'Failed to stage files: ' . implode("\n", $output);
                return $results;
            }

            // Commit changes
            $commit_message = config('version.commit_message', 'chg: dev: Version set to');
            exec("git commit -m '$commit_message $newVersion' 2>&1", $output, $return_code);
            if ($return_code === 0) {
                $results['messages'][] = 'Git commit created successfully';
            } else {
                $results['messages'][] = 'Failed to create commit: ' . implode("\n", $output);
                return $results;
            }

            // Create tag
            exec("git tag $newVersion 2>&1", $output, $return_code);
            if ($return_code === 0) {
                exec('git describe --tags 2>&1', $describeOutput);
                $results['messages'][] = 'Git tag created: ' . ($describeOutput[0] ?? $newVersion);
                $results['success'] = true;
            } else {
                $results['messages'][] = 'Failed to create tag: ' . implode("\n", $output);
            }

            return $results;
        } catch (\Exception $e) {
            $results['messages'][] = 'Git operations failed: ' . $e->getMessage();
            return $results;
        }
    }

    /**
     * Get the config file path.
     */
    protected function getConfigPath(): string
    {
        return config('version.config_path');
    }

    /**
     * Get the changelog file path.
     */
    protected function getChangelogPath(): string
    {
        return config('version.changelog_path');
    }

    /**
     * Get the files to commit.
     */
    protected function getFilesToCommit(): array
    {
        return config('version.files_to_commit');
    }
}

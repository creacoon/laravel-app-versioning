<?php

namespace Creacoon\AppVersioning\Commands;

use Creacoon\AppVersioning\Facades\VersionManager;
use Illuminate\Console\Command;

class VersionManagerCommand extends Command
{
    protected $signature = 'version {version?}';
    protected $description = 'Get or set the application version';

    public function handle(): int
    {
        $new_version = $this->argument('version');
        $current_version = VersionManager::getCurrentVersion();

        if ($new_version) {
            if (version_compare($new_version, $current_version) <= 0) {
                $this->error('The new version should be greater than ' . $current_version);
                return self::FAILURE;
            }

            // Update config file
            if (!VersionManager::updateConfigFile($new_version)) {
                $this->error('Could not update version in config file. Please check the format.');
                return self::FAILURE;
            }

            // Update runtime config
            VersionManager::updateRuntimeConfig($new_version);
            $this->info('Application version set to: ' . VersionManager::getCurrentVersion());

            // Update changelog
            VersionManager::updateChangelog($new_version);

            // Git operations
            $git_results = VersionManager::handleGitOperations($new_version);
            foreach ($git_results['messages'] as $message) {
                if (str_contains($message, 'Failed')) {
                    $this->warn($message);
                } else {
                    $this->line($message);
                }
            }

            return self::SUCCESS;
        } else {
            $this->info('Application version: ' . $current_version);
            return self::SUCCESS;
        }
    }
}


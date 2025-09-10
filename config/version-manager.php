<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Version Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options for the version manager.
    |
    */

    // The config key where the version is stored
    'config_key' => 'app.version',

    // The key name in the config file
    'version_key' => 'version',

    // Path to the config file
    'config_path' => config_path('app.php'),

    // Path to the changelog file
    'changelog_path' => base_path('CHANGELOG.md'),

    // Pattern to find the Unreleased section in the changelog
    'unreleased_pattern' => '## [Unreleased]',

    // Commit message prefix
    'commit_message' => 'chg: dev: Version set to',

    // Files to commit when updating the version
    'files_to_commit' => [
        'CHANGELOG.md',
        'config/app.php',
    ],
];

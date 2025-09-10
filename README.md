### . README.md
# Laravel Version Manager

A Laravel package for managing application versioning.

## Installation

You can install the package via composer:

```bash
composer require yourvendor/laravel-version-manager
```

After installing, publish the configuration file:
``` bash
php artisan vendor:publish --provider="YourVendor\VersionManager\ServiceProvider" --tag="config"
```

## Usage
### Getting the current version
``` php
// Using the facade
use YourVendor\VersionManager\Facades\VersionManager;

$version = VersionManager::getCurrentVersion();

// Using the command
php artisan version
```
### Setting a new version
``` php
// Using the facade
use YourVendor\VersionManager\Facades\VersionManager;

VersionManager::setVersion('1.2.3');

// Using the command
php artisan version 1.2.3
```

The package will:
1. Update the version in your config file
2. Update the runtime configuration
3. Update your CHANGELOG.md file
4. Create a git commit with the changes
5. Create a git tag for the new version

## Configuration
You can customize the package behavior by modifying the `config/version.php` file:
``` php
return [
    'config_key' => 'app.version',
    'version_key' => 'version',
    'config_path' => config_path('app.php'),
    'changelog_path' => base_path('CHANGELOG.md'),
    'unreleased_pattern' => '## [Unreleased]',
    'commit_message' => 'chg: dev: Version set to',
    'files_to_commit' => [
        'CHANGELOG.md',
        'config/app.php'
    ],
];
```

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

### 4. How to Use This Package

Once the package is published, a Laravel application can use it as follows:

1. Install the package via Composer
2. Publish the configuration file
3. Use the Artisan command or the facade to manage versions

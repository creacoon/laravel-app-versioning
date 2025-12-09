# Laravel Version Manager

A Laravel package for managing application versioning.

## Installation

You can install the package via composer:

```bash
composer require creacoon/laravel-app-versioning
```

After installing, publish the configuration file:
``` bash
php artisan vendor:publish --provider="Creacoon\AppVersioning\VersionManagerServiceProvider" --tag="config"
```

## Usage
### Getting the current version
``` php
// Using the facade
use Creacoon\AppVersioning\Facades\VersionManager;

$version = VersionManager::getCurrentVersion();

// Using the command
php artisan version
```
### Setting a new version
``` php
// Using the facade
use Creacoon\AppVersioning\Facades\VersionManager;

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
You can customize the package behavior by modifying the `config/version-manager.php` file.

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

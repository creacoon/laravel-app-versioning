<?php

namespace Creacoon\AppVersioning\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Creacoon\AppVersioning\VersionManager
 */
class VersionManager extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Creacoon\AppVersioning\VersionManager::class;
    }
}

<?php

namespace Creacoon\VersionManager\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Creacoon\VersionManager\VersionManager
 */
class VersionManager extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Creacoon\VersionManager\VersionManager::class;
    }
}

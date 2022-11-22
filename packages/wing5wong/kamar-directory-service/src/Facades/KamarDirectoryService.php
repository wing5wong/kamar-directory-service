<?php

namespace Wing5wong\KamarDirectoryService\Facades;

use Illuminate\Support\Facades\Facade;

class KamarDirectoryService extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'kamar-directory-service';
    }
}

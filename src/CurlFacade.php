<?php

namespace Deskti\Curl\Facades;

use Illuminate\Support\Facades\Facade;

class CurlFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'curl';
    }
}
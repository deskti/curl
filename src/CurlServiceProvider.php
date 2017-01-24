<?php
/**
 * Created by PhpStorm.
 * User: Servidor
 * Date: 20/01/2017
 * Time: 16:28
 */

namespace Deskti\Curl;


use Illuminate\Support\ServiceProvider;

class CurlServiceProvider extends ServiceProvider
{

    public function boot()
    {
    }

    public function register()
    {
        $this->app->singleton('curl',function ($app){
            return new CurlRequest();
        });
    }
}
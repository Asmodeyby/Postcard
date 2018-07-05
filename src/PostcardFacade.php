<?php
/**
 * Created by PhpStorm.
 * User: Asmod
 * Date: 20.06.2018
 * Time: 0:48
 */

namespace Asmodeyby\Postcard;

use Illuminate\Support\Facades\Facade;


class PostcardFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'postcard';
    }
}
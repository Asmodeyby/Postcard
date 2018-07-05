<?php
/**
 * Created by PhpStorm.
 * User: Asmod
 * Date: 26.06.2018
 * Time: 23:54
 */

namespace Asmodeyby\Postcard;


interface AbstractLayout
{
    public function render();

    public function init($width, $height);
}
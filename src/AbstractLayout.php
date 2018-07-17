<?php
/**
 * Created by PhpStorm.
 * User: Asmod
 * Date: 26.06.2018
 * Time: 23:54
 */

namespace Asmodeyby\Postcard;

use Asmodeyby\Postcard\Postcard;


interface AbstractLayout
{
    public function render();

    public function init(Postcard $postcard);
}
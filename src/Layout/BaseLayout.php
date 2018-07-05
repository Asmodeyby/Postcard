<?php
/**
 * Created by PhpStorm.
 * User: Asmod
 * Date: 05.07.2018
 * Time: 16:05
 */

namespace Asmodeyby\Postcard\Layout;


use Asmodeyby\Postcard\AbstractLayout;
use Intervention\Image\Constraint;
use Intervention\Image\ImageManager;


class BaseLayout
{

    const CONTAIN_IMAGE = "CONTAIN_IMAGE";
    const CANVAS_IMAGE = "CANVAS_IMAGE";
    const STRETCH_IMAGE = "STRETCH_IMAGE";
    const CROP_IMAGE = "CROP_IMAGE";

    const POSITION_CENTER = "center";
    const POSITION_LEFT_TOP = "left-top";


    protected $_width = null;

    protected $_height = null;

    protected $_x = 0;

    protected $_y = 0;

    protected $_opacity = 100;

    /** @var null|ImageManager */
    protected $_manager = null;

    /**
     * @var \Intervention\Image\Image|null
     */
    protected $_canvas = null;

    public function init($width, $height) {

        $this->_width = $width;
        $this->_height = $height;

        // create an image manager instance with favored driver
        $this->_manager = new ImageManager(array('driver' => 'imagick'));

        $this->_canvas = $this->_manager->canvas($this->_width, $this->_height);

    }

    public function setOpacity($opacity) {
        $this->_opacity = $opacity;
    }

    public function setLayerPosition($x, $y) {
        $this->_x = $x;
        $this->_y = $y;
    }

    public function getX() {
        return $this->_x;
    }

    public function getY() {
        return $this->_y;
    }
}
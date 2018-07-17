<?php
/**
 * Created by PhpStorm.
 * User: Asmod
 * Date: 05.07.2018
 * Time: 16:05
 */

namespace Asmodeyby\Postcard\Layout;


use Asmodeyby\Postcard\AbstractLayout;
use Asmodeyby\Postcard\Postcard;
use Intervention\Image\Constraint;
use Intervention\Image\ImageManager;


class BaseLayout
{
    const CONTAIN_IMAGE = "CONTAIN_IMAGE";
    const CANVAS_IMAGE = "CANVAS_IMAGE";
    const STRETCH_IMAGE = "STRETCH_IMAGE";
    const CROP_IMAGE = "CROP_IMAGE";
    const NO_MODIFY = "NO_MODIFY";
    const RESIZE_IMAGE = "RESIZE_IMAGE";

    const POSITION_CENTER = "center";
    const POSITION_LEFT_TOP = "left-top";
    const POSITION_LEFT_BOTTOM = "left-bottom";
    const POSITION_RIGHT_BOTTOM = "bottom-right";

    protected $_layer_width = null;

    protected $_layer_height = null;

    protected $_layer_x = 0;

    protected $_layer_y = 0;

    protected $_x = 0;

    protected $_y = 0;

    protected $_position = self::POSITION_LEFT_TOP;

    protected $_opacity = 100;

    /** @var null|ImageManager */
    protected $_manager = null;

    /** @var null|Postcard */
    protected $_postcard = null;

    /**
     * @var \Intervention\Image\Image|null
     */
    protected $_canvas = null;

    public function init(Postcard $postcard) {

        if ($this->_layer_width == null && $this->_layer_height == null) {
            $this->_layer_width = $postcard->getWidth();
            $this->_layer_height = $postcard->getHeight();
        }

        $this->_postcard = $postcard;

        // create an image manager instance with favored driver
        $this->_manager = new ImageManager(array('driver' => 'imagick'));

        $this->_canvas = $this->_manager->canvas($this->_layer_width, $this->_layer_height);
    }

    public function setOpacity($opacity) {
        $this->_opacity = $opacity;
    }

    public function setSize($width, $height = null) {
        $this->_layer_width = $width;
        if ($height)
            $this->_layer_height = $height;
        else
            $this->_layer_width = $width;
    }

    public function setLayerPosition($x, $y, $position = self::POSITION_LEFT_TOP) {
        $this->_layer_x = $x;
        $this->_layer_y = $y;
        $this->_position = $position;
    }

    public function getLayerPosition() {
        return $this->_position;
    }

    public function getLayerX() {
        return $this->_layer_x;
    }

    public function getLayerY() {
        return $this->_layer_y;
    }

    public function getX() {
        return $this->_x;
    }

    public function getY() {
        return $this->_y;
    }
}
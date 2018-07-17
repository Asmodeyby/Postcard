<?php
/**
 * Created by PhpStorm.
 * User: Asmod
 * Date: 20.06.2018
 * Time: 0:47
 */

namespace Asmodeyby\Postcard;




use Illuminate\Support\Facades\Storage;
use Intervention\Image\AbstractFont;
use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManager;

class Postcard
{

    private $_layouts = [];

    private $_canvas = null;

    private $_width = null;

    private $_height = null;

    public function __construct($width, $height)
    {
        // create an image manager instance with favored driver
        $manager = new ImageManager(array('driver' => 'imagick'));

        $this->_width = $width;
        $this->_height = $height;

        /** @var  \Intervention\Image\Image _canvas */
        $this->_canvas = $manager->canvas($this->_width, $this->_height);
    }

    public function addLayout(AbstractLayout $layout) {
        $layout->init($this);
        $this->_layouts[] = $layout;
    }

    /**
     * @return \Intervention\Image\Image|null
     */
    public function render() {
        foreach($this->_layouts as $layout) {
            $this->_canvas->insert($layout->render(), $layout->getLayerPosition(), $layout->getLayerX(), $layout->getLayerY());
        }
        return $this->_canvas;
    }

    public function getWidth() {
        return $this->_width;
    }

    public function getHeight() {
        return $this->_height;
    }

    /**
     * @param $width
     * @param $height
     * @return Postcard
     */
    public static function make($width, $height) {
        return new self($width, $height);
    }
}
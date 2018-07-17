<?php
/**
 * Created by PhpStorm.
 * User: Asmod
 * Date: 26.06.2018
 * Time: 23:56
 */

namespace Asmodeyby\Postcard\Layout;


use Asmodeyby\Postcard\AbstractLayout;
use Asmodeyby\Postcard\Postcard;
use Intervention\Image\Constraint;
use Intervention\Image\ImageManager;

class ImageLayout extends BaseLayout implements AbstractLayout
{
    private $_imagePath = null;

    private $_type = null;

    //private $_height = null;

    //private $_width = null;

    /** @var \Intervention\Image\Image|null */
    private $_image = null;

    public function init(Postcard $postcard) {
        parent::init($postcard);
        $this->_image = $this->_manager->make($this->_imagePath);
    }

    public function setImage($path, $type = self::CROP_IMAGE) {
        $this->_imagePath = $path;
        $this->_type = $type;
    }

    /**
     * @return \Intervention\Image\Image
     */
    public function render()
    {
        if ($this->_type == self::CANVAS_IMAGE) {
            $this->_image->fit($this->_layer_width, $this->_layer_height);
        }

        if ($this->_type == self::STRETCH_IMAGE) {
            $this->_image->resize($this->_layer_width, $this->_layer_height);
        }

        if ($this->_type == self::CONTAIN_IMAGE) {
            $this->_image->resize($this->_layer_width, $this->_layer_height, function (Constraint $constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        if ($this->_type == self::RESIZE_IMAGE) {
            $this->_image->resize($this->_layer_width, $this->_layer_height, function (Constraint $constraint) {});
        }

        if ($this->_opacity !== 100)
            $this->_image->opacity($this->_opacity);


        $this->_canvas->insert($this->_image, $this->_position, $this->_x, $this->_y);

        return $this->_canvas;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Asmod
 * Date: 26.06.2018
 * Time: 23:56
 */

namespace Asmodeyby\Postcard\Layout;


use Asmodeyby\Postcard\AbstractLayout;
use Intervention\Image\Constraint;
use Intervention\Image\ImageManager;
use \Illuminate\Support\Facades\File;

class LinearGradientLayout extends BaseLayout implements AbstractLayout
{

    /** @var \Intervention\Image\Image|null */
    private $_image = null;

    private $_color0 = "#000000";

    private $_color1 = "#ffffff";

    private $_x0 = 0;

    private $_x1 = 0;

    private $_y0 = 0;

    private $_y1 = 0;


    public function render()
    {
        if ($this->_opacity !== 100)
            $this->_image->opacity($this->_opacity);

        $this->_canvas->insert($this->_image, self::POSITION_LEFT_TOP, $this->_x, $this->_y);

        return $this->_canvas;
    }

    public function init($width, $height)
    {
        parent::init($width, $height);

        $imagick = new \Imagick();
        $imagick->newPseudoImage(abs($this->_x0 - $this->_x1),abs($this->_y0 - $this->_y1),"gradient:{$this->_color0}-{$this->_color1}");

        $this->_image = $this->_manager->make($imagick);
    }

    public function setGradient($x0, $y0, $x1, $y1, $color0, $color1) {
        $this->_x0 = $x0;
        $this->_x1 = $x1;
        $this->_y0 = $y0;
        $this->_y1 = $y1;
        $this->_color0 = $color0;
        $this->_color1 = $color1;
    }

}
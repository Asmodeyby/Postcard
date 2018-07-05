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
        $layout->init($this->_width, $this->_height);
        $this->_layouts[] = $layout;
    }

    /**
     * @return \Intervention\Image\Image|null
     */
    public function render() {
        foreach($this->_layouts as $layout) {
            $this->_canvas->fill($layout->render());
        }
        return $this->_canvas;
    }

    /**
     * @param $width
     * @param $height
     * @return Postcard
     */
    public static function make($width, $height) {

        return new self($width, $height);

        /*

        // create an image manager instance with favored driver
        $manager = new ImageManager(array('driver' => 'gd'));


        $i = $manager->make($path);

        $i->fit(1076, 480);

        $i->greyscale();
        $i->fill('rgba(0, 0, 0, 0.5)');


        $i->text("Аниме спасет мир Аниме спасет мир Аниме спасет мир", 30, 30, function($font) {
            $font->file(Storage::path("OpenSans-SemiBold.ttf"));
            $font->size(64);
            $font->color('#fff');
            $font->align('left');
            $font->valign('top');
        });


        $i->text("#АНИМЕ", 20, 480 - 30, function(AbstractFont $font) {
            $font->file(Storage::path("OpenSans-SemiBold.ttf"));
            $font->size(32);
            $font->color('#fff');
            $font->align('left');
            $font->valign('bottom');
        });




        $logo_image =  $manager->make($logo_path);
        $logo_image->resize(64,64);

        $i->insert($logo_image, "bottom-right", 30, 30);




        return $i;

        */

    }
}
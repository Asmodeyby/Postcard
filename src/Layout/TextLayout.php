<?php
/**
 * Created by PhpStorm.
 * User: Asmod
 * Date: 11.07.2018
 * Time: 17:34
 */

namespace Asmodeyby\Postcard\Layout;

use Asmodeyby\Postcard\AbstractLayout;
use Asmodeyby\Postcard\Postcard;
use Intervention\Image\Constraint;
use Intervention\Image\ImageManager;
use \Illuminate\Support\Facades\File;

class TextLayout extends BaseLayout implements AbstractLayout
{
    /** @var \Intervention\Image\Image|null */
    private $_image = null;

    private $_text = null;

    private $_font = "resources/fonts/Play-Regular.ttf";

    private $_fontSize = 56;

    private $_fontColor = "#ffffff";

    public function setText($text)
    {
        $this->_text = $text;
    }

    public function setColor($color) {
        $this->_fontColor = $color;
    }

    public function setFontSize($size) {
        $this->_fontSize = $size;
    }

    public function getFontSize() {
        return $this->_fontSize;
    }

    public function init(Postcard $postcard) {
        parent::init($postcard);

        $imagick = new \Imagick();

        //$width = $postcard->getWidth();
        //$height = $postcard->getHeight();

        $width = $this->_layer_width;
        $height = $this->_layer_height;

        $imagick->newImage($width, $height, "transparent");

        $draw = new \ImagickDraw();
        $draw->setFontSize($this->_fontSize);
        $draw->setFont($this->_font);

        $draw->setGravity( \Imagick::GRAVITY_NORTHWEST );

        $draw->setFillColor(new \ImagickPixel($this->_fontColor));

        // Holds calculated height of lines with given font, font size
        $total_height = 0;

        $line_height_ratio = 0;

        $max_height = $height;

        $max_width = $width;

        if( strlen( $this->_text ) < 1 )
        {
            return;
        }

        $imageWidth = $imagick->getImageWidth();
        $imageHeight = $imagick->getImageHeight();

        // margins are css-type margins: T, R, B, L
        $boundingBoxWidth = $imageWidth;
        $boundingBoxHeight = $imageHeight;

       // dd([$imageWidth, $imageHeight]);

        // We begin by setting the initial font size
        // to the maximum allowed height, and work our way down
        $fontSize = $this->_fontSize;
        $textLength = strlen( $this->_text );

        $leading = 1;

        // Start the main routine where the culprits are
        do
        {
            $probeText = $this->_text;
            $probeTextLength = $textLength;
            $lines = explode( "\n", $probeText );
            $lineCount = count( $lines );
            $draw->setFontSize( $fontSize );
            $fontMetrics = $imagick->queryFontMetrics( $draw, $probeText, true );
            // This routine will try to wordwrap() text until it
            // finds the ideal distibution of words over lines,
            // given the current font size, to fit the bounding box width
            // If it can't, it will fall through and the parent
            // enclosing routine will try a smaller font size
            while( $fontMetrics[ 'textWidth' ] >= $boundingBoxWidth )
            {
                // While there's no change in line lengths
                // decrease wordwrap length (no point in
                // querying font metrics if the dimensions
                // haven't changed)
                $lineLengths = array_map( 'strlen', $lines );
                do
                {
                    $probeText = wordwrap( $this->_text, $probeTextLength );
                    $lines = explode( "\n", $probeText );
                    // This is one of the performance culprits
                    // I was hoping to find some kind of binary
                    // search type algorithm that eliminates
                    // the need to decrease the length only
                    // one character at a time
                    $probeTextLength--;
                }
                while( $lineLengths === array_map( 'strlen', $lines ) && $probeTextLength > 0 );
                // Get the font metrics for the current line distribution
                $fontMetrics = $imagick->queryFontMetrics( $draw, $probeText, true );
                if( $probeTextLength <= 0 )
                {
                    break;
                }
            }
            // Ignore font metrics textHeight, we'll calculate our own
            // based on our $leading argument
            $lineHeight = $leading * $fontSize;
            $lineSpacing = ( $leading - 1 ) * $fontSize;
            $lineCount = count( $lines );
            $textHeight = ( $lineCount * $fontSize ) + ( ( $lineCount - 1 ) * $lineSpacing );
            // This is the other performance culprit
            // Here I was also hoping to find some kind of
            // binary search type algorithm that eliminates
            // the need to decrease the font size only
            // one pixel at a time
            $fontSize -= 1;
        }
        while( $textHeight >= $imageHeight || $fontMetrics[ 'textWidth' ] >= $boundingBoxWidth );


        $y = $this->_y;
        foreach( $lines as $line )
        {
            $imagick->annotateImage( $draw, $this->_x, $y, 0, $line );
            $y += $lineHeight;
        }

        $this->_image = $this->_manager->make($imagick);
    }

    public function render()
    {

        /*
        $this->_canvas->text($this->_text, $this->_x, $this->_y, function($font) {
            $font->file($this->_font);
            $font->size(120);
            $font->color('#00000');
            $font->align('left');
            $font->valign('top');
        });
        */

        $this->_canvas->insert($this->_image, self::POSITION_LEFT_TOP, $this->_x, $this->_y);

        return $this->_canvas;
    }
}
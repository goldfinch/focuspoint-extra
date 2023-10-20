<?php

namespace Goldfinch\FocusPointExtra\Forms;

use SilverStripe\Assets\Image;
use SilverStripe\View\ArrayData;
use SilverStripe\Forms\TextField;
use SilverStripe\Control\Director;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\Forms\HiddenField;
use SilverStripe\View\Requirements;
use SilverStripe\Forms\LiteralField;

class ImageCoordsField extends FieldGroup
{
    private static $debug = false;

    public function  __construct($name, $XFieldName, $YFieldName, $xySumFieldName, $image, $width, $height, $cssGrid = false, $onlyCanvas = false)
    {

      // Create the fields
        $previewImage = ArrayData::create( [
          'Width' => $width,
          'Height' => $height,
          'Image' => $image,
          'XYSumFieldName' => $xySumFieldName,
          'XFieldName' => $XFieldName,
          'YFieldName' => $YFieldName,
          'CSSGrid' => $cssGrid,
        ] );

        if ($onlyCanvas)
        {
            $fields = [
                LiteralField::create('ImageCoordsGrid', $previewImage->renderWith('Goldfinch\FocusPointExtra\ImageCoordsGrid')),
                HiddenField::create($XFieldName, $XFieldName, $image->FocusPointX),
                HiddenField::create($YFieldName , $YFieldName, $image->FocusPointY),
            ];
        }
        else
        {
            $fields = [
                $sumField = LiteralField::create($xySumFieldName, '<br><div class="sumField">mouseX 0.0 / mouseY 0.0</div><br>'),
                LiteralField::create('ImageCoordsGrid', $previewImage->renderWith('Goldfinch\FocusPointExtra\ImageCoordsGrid')),
                TextField::create($XFieldName, $XFieldName,  $image->FocusPointX),
                TextField::create($YFieldName , $YFieldName,  $image->FocusPointY),
            ];
        }

        parent::__construct($fields);

        $this->setName('ImageCoord');
        $this->setTitle($name);
        $this->addExtraClass('image-coord-fieldgroup');

        if (Director::isDev() && $this->config()->get('debug'))
        {
            $this->addExtraClass('debug');
        }
    }
}

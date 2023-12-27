<?php

namespace Goldfinch\ImageEditor\Forms;

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

    public function __construct(
        $name,
        $XFieldName = null,
        $YFieldName = null,
        $xySumFieldName = 'filename',
        $image = null,
        $width = null,
        $height = null,
        $cssGrid = false,
        $onlyCanvas = false,
    ) {
        if (is_object($name)) {
            $image = $name->$XFieldName();

            if ($image && get_class($image) == Image::class) {
                $name = $XFieldName;
                $XFieldName = $name . '-_1_-FocusPointX';
                $YFieldName = $name . '-_1_-FocusPointY';
                $width = $image->getWidth();
                $height = $image->getHeight();
            }
        } else {
            // Example with plain data:

            // $image = $this->Image();
            // $imageField =  ImageCoordsField::create('Image.FocusPoint', 'Image-_1_-FocusPointX', 'Image-_1_-FocusPointY', 'filename', $image, $image->getWidth(), $image->getHeight());
        }

        // Create the fields
        $previewImage = ArrayData::create([
            'Width' => $width,
            'Height' => $height,
            'Image' => $image,
            'XYSumFieldName' => $xySumFieldName,
            'XFieldName' => $XFieldName,
            'YFieldName' => $YFieldName,
            'CSSGrid' => $cssGrid,
        ]);

        if ($onlyCanvas) {
            $fields = [
                LiteralField::create(
                    'ImageCoordsGrid',
                    $previewImage->renderWith(
                        'Goldfinch\ImageEditor\ImageCoordsGrid',
                    ),
                ),
                HiddenField::create(
                    $XFieldName,
                    $XFieldName,
                    $image->FocusPointX,
                ),
                HiddenField::create(
                    $YFieldName,
                    $YFieldName,
                    $image->FocusPointY,
                ),
            ];
        } else {
            $fields = [
                ($sumField = LiteralField::create(
                    $xySumFieldName,
                    '<div class="sumField">mouseX 0.0 / mouseY 0.0</div><br>',
                )),
                LiteralField::create(
                    'ImageCoordsGrid',
                    $previewImage->renderWith(
                        'Goldfinch\ImageEditor\ImageCoordsGrid',
                    ),
                ),
                TextField::create(
                    $XFieldName,
                    'Focus Point X',
                    $image->FocusPointX,
                ),
                TextField::create(
                    $YFieldName,
                    'Focus Point Y',
                    $image->FocusPointY,
                ),
            ];
        }

        parent::__construct($fields);

        $this->setName('ImageCoord');
        $this->setTitle($name);
        $this->addExtraClass('image-coord-fieldgroup');

        if (Director::isDev() && $this->config()->get('debug')) {
            $this->addExtraClass('debug');
        }
    }
}

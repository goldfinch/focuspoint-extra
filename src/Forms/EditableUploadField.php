<?php

namespace Goldfinch\ImageEditor\Forms;

use ReflectionMethod;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\ToggleCompositeField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use Goldfinch\ImageEditor\Forms\ImageCoordsField;
use SilverStripe\AssetAdmin\Forms\ImageFormFactory;

class EditableUploadField
{
    public static $fields;
    public static $parent;
    public static $name;
    public static $title;
    public static $record;

    public function isOneOrMany()
    {
        $parent = self::$parent;
        if (!$parent->exists()) {
            return false;
        }
        switch ($parent->getRelationType(self::$name)) {
            case 'has_one':
            case 'belongs_to':
                return 'one';
            case 'has_many':
            case 'many_many':
            case 'belongs_many_many':
                return 'many';
        }
    }

    public function __construct(...$args)
    {
        $name = $args[0];
        $title = $args[1];
        $fieldList = $args[2];
        $parent = $args[3];

        self::$name = $name;
        self::$title = $title;
        self::$parent = $parent;
        self::$record = $parent;

        if (isset($args[4])) {
            $field = $args[4];
        } else {
            $field = UploadField::create($name, $title);
        }

        if ($this->isOneOrMany() == 'one') {
            self::$record = $parent->{$name}();
        }

        $r = self::$record;

        if ($r::class == Image::class && $r->exists()) {
            // $ImageEditor = $fieldList->flattenFields()->fieldByName($name . '_ImageEditor');
            $ImageEditor = self::getImageEditor();

            if ($ImageEditor) {
                self::$fields = [$field, $ImageEditor];
            } else {
                self::$fields = [$field];
            }
        } else {
            self::$fields = [$field];
        }

        $fieldList->removeByName([$name . '_ImageEditor', $name]);
    }

    public static function getImageEditor()
    {
        $r = new ReflectionMethod(ImageFormFactory::class, 'getSpecsMarkup');
        $r->setAccessible(true);
        $imageSpecs = $r->invoke(new ImageFormFactory(), self::$record);

        return ToggleCompositeField::create(
            self::$name . '_ImageEditor',
            self::$title . ' Editor',
            [
                ImageCoordsField::create(
                    'Focus Point',
                    self::$name . '-_1_-FocusPointX',
                    self::$name . '-_1_-FocusPointY',
                    'filename',
                    self::$record,
                    self::$record->getWidth(),
                    self::$record->getHeight(),
                    false,
                    true,
                ),
                TextField::create(self::$name . '-_1_-Title', 'Alt / Title'),
                TextField::create(self::$name . '-_1_-Name', 'Filename'),
                LiteralField::create(
                    self::$name . 'ImageInfo',
                    '<div class="form__fieldgroup form__field-holder field"><p><a href="' .
                        self::$record->Link() .
                        '" target="_blank">Original image</a></p>' .
                        $imageSpecs .
                        '</div>',
                ),
            ],
        )->addExtraClass('mb-4');
    }

    public function getFields()
    {
        return self::$fields;
    }

    public static function create(...$args): static
    {
        return new static(...$args);
    }
}

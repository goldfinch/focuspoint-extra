```
private static $has_one = [
    'Image' => Image::class,
];

private static $many_many = [
    'Images' => Image::class,
];

private static $many_many_extraFields = [
    'Images' => [
        'SortExtra' => 'Int',
    ]
];

private static $owns = [
    'Image',
    'Images',
];

...UploadFieldWithSettings::create('Image', 'Image', $fields, $this)->getFields(),
...SortableUploadFieldWithSettings::create('Images', 'Images', $fields, $this)->getFields(),
```


```

$grid = GridField::create('ImageAttributes', 'OG_Images', $this->OG_Images(),
GridFieldManyManyFocusConfig::create());
$fields->insertAfter('Title', $grid);

$fields->insertAfter('Title', UploadField::create(
  'Image',
  'Image'
));

$image = $this->Image();
$imageField =  ImageCoordsField::create('Image.FocusPoint', 'Image-_1_-FocusPointX', 'Image-_1_-FocusPointY', 'filename', $image, $image->getWidth(), $image->getHeight());
$fields->insertAfter('Title', $imageField);


...UploadFieldWithSettings::create('Image', 'Image', $fields, $this)->getFields(),


// $fields->addFieldsToTab(
//   'Root.ImagesAttributes',
//   [
//       GridField::create('ImageAttributes', 'Images', $this->Images(), GridFieldManyManyFocusConfig::create(null, true))->addExtraClass('image-editor-attrs-grid'),
//   ]
// );
```

Thanks to: https://github.com/jonom/silverstripe-image-coord & https://github.com/seppzzz/silverstripe-image-coord


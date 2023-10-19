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

```

Thanks to: https://github.com/jonom/silverstripe-image-coord & https://github.com/seppzzz/silverstripe-image-coord

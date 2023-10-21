<?php

namespace Goldfinch\FocusPointExtra\Forms\GridField;

use SilverStripe\Forms\TextField;
use SilverStripe\Forms\HiddenField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\GridField\GridFieldConfig;
use SilverStripe\Forms\GridField\GridFieldDetailForm;
use SilverStripe\Forms\GridField\GridFieldEditButton;
use SilverStripe\Forms\GridField\GridField_ActionMenu;
use SilverStripe\Forms\GridField\GridFieldDataColumns;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\Forms\GridField\GridFieldToolbarHeader;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use SilverStripe\Forms\GridField\GridFieldSortableHeader;
use Symbiote\GridFieldExtensions\GridFieldEditableColumns;

class GridFieldManyManyFocusConfig extends GridFieldConfig
{
    public function __construct($itemsPerPage = null, $sortable = false)
    {
        parent::__construct($itemsPerPage);

        $this->addComponents(
            GridFieldToolbarHeader::create(),
            GridFieldSortableHeader::create(),
            GridFieldDataColumns::create(),
            GridFieldDetailForm::create(),
            GridFieldDeleteAction::create(),
            GridFieldEditButton::create(),
            GridField_ActionMenu::create(),
            GridFieldEditableColumns::create(),
        );

        if ($sortable)
        {
          $this->addComponent(GridFieldOrderableRows::create('SortOrder'));
        }

        $dataColumns = $this->getComponentByType(GridFieldDataColumns::class);

        $dataColumns->setDisplayFields([
            'Title' => 'Alt / Title',
            'Name' => 'Filename',
            'FocusPointX'=> 'FocusPointX',
            'FocusPointY'=> 'FocusPointY',
            'FocusPoint' => ['title' => 'Focus Point', 'field' => LiteralField::class, 'callback' => function($record, $columnName, $gridField) {

              if (in_array('ss-gridfield-editable', array_values($gridField->extraClasses)))
              {
                  $gridPrefix = $gridField->getName() . '[GridFieldEditableColumns]['.$record->ID.']';

                  return $record->getFocusPointGridEditableColumn($columnName, $gridPrefix);
              }
          }],
        ]);

        $dataEditableColumns = $this->getComponentByType(GridFieldEditableColumns::class);

        $dataEditableColumns->setDisplayFields([
            // 'Title'  => function($record, $column, $grid) {
            //   return TextField::create($column);
            // },
            // 'Filename'  => function($record, $column, $grid) {
            //   return TextField::create($column);
            // },
            'Title' => 'Title',
            'Name' => 'Name',
            'FocusPointX' => function($record, $column, $grid) {
              return HiddenField::create('FocusPointX');
            },
            'FocusPointY'  => function($record, $column, $grid) {
              return HiddenField::create('FocusPointY');
            },
        ]);

        $this->extend('updateConfig');
    }
}

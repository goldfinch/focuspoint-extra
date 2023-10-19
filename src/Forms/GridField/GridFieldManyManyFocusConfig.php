<?php

namespace Goldfinch\FocusPointExtra\Forms\GridField;

use SilverStripe\Forms\GridField\GridFieldConfig;
// use SilverStripe\Forms\GridField\GridFieldDetailForm;
use SilverStripe\Forms\GridField\GridFieldEditButton;
// use SilverStripe\Forms\GridField\GridField_ActionMenu;
use SilverStripe\Forms\GridField\GridFieldDataColumns;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
// use SilverStripe\Forms\GridField\GridFieldFilterHeader;
// use SilverStripe\Forms\GridField\GridFieldToolbarHeader;
// use SilverStripe\Forms\GridField\GridFieldSortableHeader;
// use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\HiddenField;
use SilverStripe\Forms\LiteralField;

class GridFieldManyManyFocusConfig extends GridFieldConfig
{
    public function __construct($itemsPerPage = null)
    {
        parent::__construct($itemsPerPage);

        $this
          ->removeComponentsByType(GridFieldAddNewButton::class)
          ->removeComponentsByType(GridFieldEditButton::class)
          // ->removeComponentsByType('GridFieldDataColumns')
          ->removeComponentsByType(GridFieldArchiveAction::class)
          ->removeComponentsByType(GridFieldDeleteAction::class)
          ->addComponent($cols = new GridFieldEditableColumns())
          ->addComponent($colsData = new GridFieldDataColumns())
          ->addComponent(new GridFieldOrderableRows('SortOrder'))
        ;

        $colsData->setDisplayFields([
            'FocusPoint' => ['title' => 'Focus Point', 'field' => LiteralField::class, 'callback' => function($record, $columnName, $gridField) {

                if (in_array('ss-gridfield-editable', array_values($gridField->extraClasses)))
                {
                    $gridPrefix = $gridField->getName() . '[GridFieldEditableColumns]['.$record->ID.']';

                    return $record->getFocusPointGridEditableColumn($columnName, $gridPrefix);
                }
            }],
        ]);

        $cols->setDisplayFields([
            'Title'  => function($record, $column, $grid) {
              return TextField::create($column);
            },
            'FocusPointX'  => function($record, $column, $grid) {
              return HiddenField::create('FocusPointX');
            },
            'FocusPointY'  => function($record, $column, $grid) {
              return HiddenField::create('FocusPointY');
            },
        ]);

        $cols->setDisplayFields([
          'Title' => 'Alt',
        ]);

        $this->extend('updateConfig');
    }
}

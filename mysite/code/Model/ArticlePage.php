<?php

use SilverStripe\Assets\Image;
use SilverStripe\Assets\File;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\FieldType\DBDate;
use SilverStripe\Forms\DateField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\TextField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\CheckboxSetField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\GraphQL\Scaffolding\Interfaces\ScaffoldingProvider;
use SilverStripe\GraphQL\Scaffolding\Scaffolders\SchemaScaffolder;

/**
 * Class ArticlePage
 * @property DateField Date
 * @property string Teaser
 * @property string Author
 * @method DataList Comments
 */
class ArticlePage extends Page implements ScaffoldingProvider
{
    private static $db = array(
        'Date' => 'Date',
        'Teaser' => 'Text',
        'Author' => 'Varchar',
    );


    private static $has_one = array(
        'Photo' => Image::class,
        'Brochure' => File::class,
        'Region' => 'Region'
    );


    private static $many_many = array(
        'Categories' => 'ArticleCategory'
    );


    private static $has_many = array(
        'Comments' => 'ArticleComment'
    );


    private static $can_be_root = false;


    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->addFieldToTab('Root.Main', DateField::create(DBDate::class, 'Date of article'), 'Content');
        $fields->addFieldToTab('Root.Main', TextareaField::create('Teaser'), 'Content');
        $fields->addFieldToTab('Root.Main', TextField::create('Author', 'Author of article'), 'Content');
        $fields->addFieldToTab('Root.Attachments', $photo = UploadField::create('Photo'));
        $fields->addFieldToTab('Root.Attachments',
            $brochure = UploadField::create('Brochure', 'Travel brochure, optional (PDF only)'));

        $photo->getValidator()->setAllowedExtensions(array('png', 'gif', 'jpg', 'jpeg'));
        $photo->setFolderName('travel-photos');

        $brochure->getValidator()->setAllowedExtensions(array('pdf'));
        $brochure->setFolderName('travel-brochures');

        $fields->addFieldToTab('Root.Categories', CheckboxSetField::create(
            'Categories',
            'Selected categories',
            $this->Parent()->Categories()->map('ID', 'Title')
        ));

        $fields->addFieldToTab('Root.Main', DropdownField::create(
            'RegionID',
            'Region',
            Region::get()->map('ID', 'Title')
        )->setEmptyString('-- None --'), 'Content');

        return $fields;
    }


    public function CategoriesList()
    {
        if ($this->Categories()->exists()) {
            return implode(', ', $this->Categories()->column('Title'));
        }
    }
    
    public function provideGraphQLScaffolding(SchemaScaffolder $scaffolder)
    {
        $scaffolder
            ->type("ArticlePage")
                ->addFields(array_keys(self::$db))
                ->operation(SchemaScaffolder::READ)
                    ->end()
                ->operation(SchemaScaffolder::UPDATE)
                    ->end()
                ->nestedQuery('Comments')
                    ->end()
                ->end()
            ->type("ArticleComment")
                ->addAllFields()
                ->operation(SchemaScaffolder::READ)
                    ->end();
        
        return $scaffolder;
    }
}

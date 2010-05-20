<?php

/**
 * BaseTitleCategory
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $title_id
 * @property integer $category_id
 * @property Title $Title
 * @property Category $Category
 * 
 * @method integer       getTitleId()     Returns the current record's "title_id" value
 * @method integer       getCategoryId()  Returns the current record's "category_id" value
 * @method Title         getTitle()       Returns the current record's "Title" value
 * @method Category      getCategory()    Returns the current record's "Category" value
 * @method TitleCategory setTitleId()     Sets the current record's "title_id" value
 * @method TitleCategory setCategoryId()  Sets the current record's "category_id" value
 * @method TitleCategory setTitle()       Sets the current record's "Title" value
 * @method TitleCategory setCategory()    Sets the current record's "Category" value
 * 
 * @package    LoveToKnow
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
abstract class BaseTitleCategory extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('title_category');
        $this->hasColumn('title_id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             ));
        $this->hasColumn('category_id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             ));

        $this->option('type', 'INNODB');
        $this->option('collate', 'utf8_unicode_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Title', array(
             'local' => 'title_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('Category', array(
             'local' => 'category_id',
             'foreign' => 'id'));

        $auditable0 = new Auditable();
        $this->actAs($auditable0);
    }
}
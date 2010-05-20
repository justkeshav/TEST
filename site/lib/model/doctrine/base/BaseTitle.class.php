<?php

/**
 * BaseTitle
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $channel_id
 * @property string $title
 * @property string $slug
 * @property string $url
 * @property enum $type
 * @property enum $status
 * @property integer $published_content_version
 * @property string $notes
 * @property date $available_on
 * @property integer $author_user_id
 * @property integer $image_id
 * @property string $image_link
 * @property string $image_text
 * @property string $image_caption
 * @property integer $image_width
 * @property boolean $image_thumbnail
 * @property Channel $Channel
 * @property Doctrine_Collection $Categories
 * @property sfGuardUser $sfGuardUser
 * @property Image $Image
 * @property Doctrine_Collection $FeatureArticle
 * @property Doctrine_Collection $TitleCategories
 * 
 * @method integer             getChannelId()       Returns the current record's "channel_id" value
 * @method string              getTitle()           Returns the current record's "title" value
 * @method string              getSlug()            Returns the current record's "slug" value
 * @method string              getUrl()             Returns the current record's "url" value
 * @method enum                getType()            Returns the current record's "type" value
 * @method enum                getStatus()          Returns the current record's "status" value
 * @method string              getNotes()           Returns the current record's "notes" value
 * @method date                getAvailableOn()     Returns the current record's "available_on" value
 * @method integer             getAuthorUserId()    Returns the current record's "author_user_id" value
 * @method integer             getImageId()         Returns the current record's "image_id" value
 * @method string              getImageLink()       Returns the current record's "image_link" value
 * @method string              getImageText()       Returns the current record's "image_text" value
 * @method string              getImageCaption()    Returns the current record's "image_caption" value
 * @method integer             getImageWidth()      Returns the current record's "image_width" value
 * @method boolean             getImageThumbnail()  Returns the current record's "image_thumbnail" value
 * @method Channel             getChannel()         Returns the current record's "Channel" value
 * @method Doctrine_Collection getCategories()      Returns the current record's "Categories" collection
 * @method sfGuardUser         getSfGuardUser()     Returns the current record's "sfGuardUser" value
 * @method Image               getImage()           Returns the current record's "Image" value
 * @method Doctrine_Collection getFeatureArticle()  Returns the current record's "FeatureArticle" collection
 * @method Doctrine_Collection getTitleCategories() Returns the current record's "TitleCategories" collection
 * @method Title               setChannelId()       Sets the current record's "channel_id" value
 * @method Title               setTitle()           Sets the current record's "title" value
 * @method Title               setSlug()            Sets the current record's "slug" value
 * @method Title               setUrl()             Sets the current record's "url" value
 * @method Title               setType()            Sets the current record's "type" value
 * @method Title               setStatus()          Sets the current record's "status" value
 * @method Title               setNotes()           Sets the current record's "notes" value
 * @method Title               setAvailableOn()     Sets the current record's "available_on" value
 * @method Title               setAuthorUserId()    Sets the current record's "author_user_id" value
 * @method Title               setImageId()         Sets the current record's "image_id" value
 * @method Title               setImageLink()       Sets the current record's "image_link" value
 * @method Title               setImageText()       Sets the current record's "image_text" value
 * @method Title               setImageCaption()    Sets the current record's "image_caption" value
 * @method Title               setImageWidth()      Sets the current record's "image_width" value
 * @method Title               setImageThumbnail()  Sets the current record's "image_thumbnail" value
 * @method Title               setChannel()         Sets the current record's "Channel" value
 * @method Title               setCategories()      Sets the current record's "Categories" collection
 * @method Title               setSfGuardUser()     Sets the current record's "sfGuardUser" value
 * @method Title               setImage()           Sets the current record's "Image" value
 * @method Title               setFeatureArticle()  Sets the current record's "FeatureArticle" collection
 * @method Title               setTitleCategories() Sets the current record's "TitleCategories" collection

 * 
 * @package    LoveToKnow
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
abstract class BaseTitle extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('title');
        $this->hasColumn('channel_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('title', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '255',
             ));
        $this->hasColumn('slug', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '255',
             ));
        $this->hasColumn('url', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '255',
             ));
        $this->hasColumn('type', 'enum', null, array(
             'type' => 'enum',
             'values' => 
             array(
              0 => 'Category',
              1 => 'Article',
              2 => 'Slideshow',
              3 => 'Quiz',
             ),
             ));
        $this->hasColumn('status', 'enum', null, array(
             'type' => 'enum',
             'values' => 
             array(
              0 => 'Queued',
              1 => 'Available',
              2 => 'Claimed',
              3 => 'InProgress',
              4 => 'Submitted',
              5 => 'Rejected',
              6 => 'Approved',
              7 => 'Published',
              8 => 'Deleted',
             ),
             'default' => 'Queued',
             'notnull' => true,
             ));
        $this->hasColumn('published_content_version', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('notes', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('available_on', 'date', null, array(
             'type' => 'date',
             ));
        $this->hasColumn('author_user_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('image_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('image_link', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('image_text', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('image_caption', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('image_width', 'integer', null, array(
             'type' => 'integer',
             'default' => 300,
             ));
        $this->hasColumn('image_thumbnail', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));


        $this->index('title', array(
             'fields' => 
             array(
              0 => 'id',
              1 => 'channel_id',
             ),
             'type' => 'unique',
             ));
        $this->index('url', array(
             'fields' => 
             array(
              0 => 'channel_id',
              1 => 'url',
             ),
             'type' => 'unique',
             ));
        $this->option('type', 'INNODB');
        $this->option('collate', 'utf8_unicode_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Channel', array(
             'local' => 'channel_id',
             'foreign' => 'id'));

        $this->hasMany('Category as Categories', array(
             'refClass' => 'TitleCategory',
             'local' => 'title_id',
             'foreign' => 'category_id'));

        $this->hasOne('sfGuardUser', array(
             'local' => 'author_user_id',
             'foreign' => 'id'));

        $this->hasOne('Image', array(
             'local' => 'image_id',
             'foreign' => 'id'));

        $this->hasMany('FeatureArticle', array(
             'local' => 'id',
             'foreign' => 'article_id'));

        $this->hasMany('TitleCategory as TitleCategories', array(
             'local' => 'id',
             'foreign' => 'title_id'));

        $sluggable0 = new Doctrine_Template_Sluggable(array(
             'unique' => false,
             'canUpdate' => true,
             'builder' => 
             array(
              0 => 'ltkUrl',
              1 => 'generateSlug',
             ),
             ));
        $defaultsort0 = new DefaultSort(array(
             'column' => 'title',
             ));
        $auditable0 = new Auditable();
        $this->actAs($sluggable0);
        $this->actAs($defaultsort0);
        $this->actAs($auditable0);
    }
}
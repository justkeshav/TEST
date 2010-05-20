<?php

/**
 * BaseImage
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $channel_id
 * @property string $host
 * @property string $path
 * @property string $filename
 * @property array $sizes
 * @property string $source
 * @property string $source_url
 * @property string $permission
 * @property clob $description
 * @property Channel $Channel
 * 
 * @method integer getChannelId()   Returns the current record's "channel_id" value
 * @method string  getHost()        Returns the current record's "host" value
 * @method string  getPath()        Returns the current record's "path" value
 * @method string  getFilename()    Returns the current record's "filename" value
 * @method array   getSizes()       Returns the current record's "sizes" value
 * @method string  getSource()      Returns the current record's "source" value
 * @method string  getSourceUrl()   Returns the current record's "source_url" value
 * @method string  getPermission()  Returns the current record's "permission" value
 * @method clob    getDescription() Returns the current record's "description" value
 * @method Channel getChannel()     Returns the current record's "Channel" value
 * @method Image   setChannelId()   Sets the current record's "channel_id" value
 * @method Image   setHost()        Sets the current record's "host" value
 * @method Image   setPath()        Sets the current record's "path" value
 * @method Image   setFilename()    Sets the current record's "filename" value
 * @method Image   setSizes()       Sets the current record's "sizes" value
 * @method Image   setSource()      Sets the current record's "source" value
 * @method Image   setSourceUrl()   Sets the current record's "source_url" value
 * @method Image   setPermission()  Sets the current record's "permission" value
 * @method Image   setDescription() Sets the current record's "description" value
 * @method Image   setChannel()     Sets the current record's "Channel" value
 * 
 * @package    LoveToKnow
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
abstract class BaseImage extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('image');
        $this->hasColumn('channel_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('host', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '255',
             ));
        $this->hasColumn('path', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '255',
             ));
        $this->hasColumn('filename', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '255',
             ));
        $this->hasColumn('sizes', 'array', null, array(
             'type' => 'array',
             'notnull' => true,
             ));
        $this->hasColumn('source', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '255',
             ));
        $this->hasColumn('source_url', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('permission', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '255',
             ));
        $this->hasColumn('description', 'clob', null, array(
             'type' => 'clob',
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
    }
}
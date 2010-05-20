<?php

// After running this, if you want the fixtures to insert a fixed numberfor the PK instead
// of auto incrementing, you can do a regex search and replace.
//
// Search for "^  ([^:]+)_(\d+):(\n)" and replace with "  \1_\2:\3    id: \2\3".

if($argc < 3 && $argv[1] != '--urls')
{
   echo '
Dumps model data to YAML fixture files.

Usage: php test/fixtures/dump.php modelname id1..idN

   modelname: the name of the model class that you want to dump data for,
              e.g. Title; currently Title is the only supported modelname

   id1..idN:  space-separated list of primary keys for objects in the model
              class to dump

   For example, to dump articles 54, 76, and 302 and their related objects:
      php test/fixtures/dump.php Title 54 76 302

Alternate Usage: php test/fixtures/dump.php --urls

   This will dump all Title URLs that are hard-coded into the script\'s $urls var

';
   exit;
}

// URLs of Titles to load if the --urls flag is set
$urls = array(
'wiki/Dr._Beverly_Crusher',
'wiki/Category:The_Lord_of_the_Rings',
'wiki/Lord_of_the_Rings:_The_Return_of_the_King',
'wiki/Lord_of_the_Rings_Swords',
'wiki/Lord_of_the_Rings:_the_Fellowship_of_the_Ring',
'wiki/Lord_of_the_Rings:_The_Two_Towers',
'wiki/Tolkien\'s_The_Hobbit',
'Category:Hair_Style_Pictures',
'Slideshow:Blonde_Hair_Styles_Gallery',
'Short_Hair_Cut_Photos',
'Hair_Highlight_Examples',
'Shag_Hair_Cut_Pictures',
'Slideshow:Beautiful_Brunettes',
'Goody_Hair_Accessories',
'wiki/Q',
'wiki/Category:Star_Trek',
'wiki/Slideshow:Star_Trek_Character_Names',
'wiki/Free_Star_Trek_Screensavers',
'wiki/Star_Trek_Cast',
'Half_Ponytail'
);

require_once(dirname(__FILE__).'/../../config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'prod', true);
sfContext::createInstance($configuration);

$export = new Doctrine_Data_Export(dirname(__FILE__));
$export->setFormat('yml');
$export->exportIndividualFiles(true);

$data = array();

if($argv[1] == '--urls')
{
   foreach($urls as $url)
   {
      load('Title', Doctrine::getTable('Title')->findByUrl($url));
   }
}
else
{
   for($i = 2; $i < $argc; $i++)
   {
      load($argv[1], Doctrine::getTable($argv[1])->find(intval($argv[$i])));
   }
}

$data = $export->prepareData($data);

$export->dumpData($data);

// Now need to fix TitleCategory & TitleCategoryVersion (it doesnt seem like dump works well with associative tables)

$file = dirname(__FILE__).'/TitleCategory.yml';
if($text = file_get_contents($file))
{
   file_put_contents($file, preg_replace('/Version: TitleCategoryVersion_(\d+)/', 'Title: Title_\1', $text));
}

$file = dirname(__FILE__).'/TitleCategoryVersion.yml';
if($text = file_get_contents($file))
{
   file_put_contents($file, preg_replace('/TitleCategory: TitleCategory_(\d+)/', 'title_id: \1', $text));
}

// Search for "^  ([^:]+)_(\d+):(\n)" and replace with "  \1_\2:\3    id: \2\3".

function load($modelName, $data)
{
   if($data instanceof Doctrine_Record)
   {
      loadObject($modelName, $data);
   }
   else if($data instanceof Doctrine_Collection)
   {
      foreach($data as $object)
      {
         loadObject($modelName, $object);
      }
   }
}

// keeps track of loaded URLs so we dont hit an endless loop
$loaded = array();

function loadObject($modelName, $object)
{
   global $data, $loaded;

   if(!array_key_exists($modelName, $data))
   {
      $data[$modelName] = array();
   }

   $data[$modelName][] = $object;

   switch($modelName)
   {
      case 'Title':
         if(!array_key_exists($object->url, $loaded))
         {
            $loaded[$object->url] = true;
            load('TitleVersion', $object->Version);
            load('TitleCategory', $object->TitleCategories);
            load($object->typeClassName, $object->Content);
         }
         break;

      case 'Category':
         load('CategoryVersion', $object->Version);
         load('Article', $object->Article);
         load('Title', $object->Title);
         break;

      case 'TitleCategory':
         load('TitleCategoryVersion', $object->Version);
         load('Category', $object->Category);
         break;

      case 'Article':
         load('ArticleVersion', $object->Version);
         break;

      case 'Slideshow':
         load('SlideshowVersion', $object->Version);
         load('Slide', $object->Slides);
         break;

      case 'Slide':
         load('SlideVersion', $object->Version);
         load('Image', $object->Image);
         break;
   }
}

<?php

slot('heading', $title->title);
slot('page-type-css-class', $title->typeTableName);

if($title->type == 'Category')
{
   $category = $title->Content;
}
else
{
   $categories = $title->Categories;

   if(!empty($categories))
   {
      $category = $categories[0];
      add_crumb(link_to_content($category));
   }
}

if(!is_null($category))
{
   foreach($category->getNode()->getAncestors() as $ancestor)
   {
      if($ancestor->getNode()->isRoot()) break;
      add_crumb(link_to_content($ancestor), true);
   }
}

include_component($title->typeTableName, 'show', array('content' => $title->getPublishedContent($preview), 'title' => $title));

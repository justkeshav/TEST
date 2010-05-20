<?php
/**
* Helper functions for outputting a variety of common html layouts
*/

/**
* Add a crumb to the list of bread crumbs.
*
* @param string $crumb the HTML representing the crumb to add; normally this will be a single <a> element
* @param boolean $prepend whether to add the crumb to the beginning of the list or the end
*/
function add_crumb($crumb, $prepend = false)
{
   $newCrumb = "<li>$crumb &raquo;</li>";
   $before = '';
   $after = '';

   if($prepend)
   {
      $before = $newCrumb;
   }
   else
   {
      $after = $newCrumb;
   }

   slot('crumbs', $before . get_slot('crumbs', '') . $after);
}

/**
* Evenly splits the content items into multiple lists and echoes them.
*
* @param mixed $items the collection of content items to list
* @param array $options additional HTML compliant <a> tag parameters
* @param integer $splits the number of lists to split the items into
*/
function include_content_split_list($items, $options = array(), $splits = 2)
{
   include_split_list($items, 'title', 'content', $options, $splits);
}

/**
* Evenly splits the items into multiple lists and echoes them.
*
* @param mixed $items the collection of items to list
* @param string $labelField the field of the item object to use for display purposes
* @param string $routeName the name of the route to link to or null to not link
* @param array $options additional HTML compliant <a> tag parameters
* @param integer $splits the number of lists to split the items into
*/
function include_split_list($items, $labelField, $routeName = null, $options = array(), $splits = 2)
{
   // make sure there is something to do
   if(empty($items)) return;

   echo '<ul>';

   $priorChar = false;
   $atSplitPoint = false;
   $splitStep = count($items) / $splits;
   $splitPoint = $splitStep;
   $outputCount = 1;
   foreach($items as $item)
   {
      $label = $item->$labelField;
      $char = $label[0];

      if(strcasecmp($char, 'A') < 0)
      {
         $char = '#';
      }

      if($atSplitPoint)
      {
         echo '</ul></li></ul><ul>';
      }

      if($char !== $priorChar)
      {
         if($priorChar && !$atSplitPoint)
         {
            echo '</ul></li>';
         }

         echo "<li>$char<ul>";

         $priorChar = $char;
      }
      else if($atSplitPoint)
      {
         // we have hit the split point and have not started a new char so output a continuation label
         echo '<li>', $char, ' (cont.)<ul>';
      }

      if($routeName)
      {
         echo '<li>', link_to($label, $routeName, $item, $options), '</li>';
      }
      else
      {
         echo '<li>', $label, '</li>';
      }

      // if we hit the split point of the item count, end the current list and start a
      // new one so we can have multiple lists evenly split
      if($outputCount >= $splitPoint)
      {
         $atSplitPoint = true;
         $splitPoint += $splitStep;
      }
      else
      {
         $atSplitPoint = false;
      }

      $outputCount++;
   }

   echo '</ul></li></ul>';
}

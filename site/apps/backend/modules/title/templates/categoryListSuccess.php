<?php 
if (isset($categoryList))
{
   foreach($categoryList as $category)
   {
      echo '<option value="'.$category->id.'">'.$category->title.'</option>';
   }
}

?>
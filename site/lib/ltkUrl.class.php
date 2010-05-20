<?php

class ltkUrl
{
   /**
   * Generate a slug from text. Slugs are text formatted for use in a URL.
   *
   * @param string the text to generate a slug represenatation for
   * @return string text converted to a slug usable in URLs
   */
   public static function generateSlug($text)
   {
      $slug = $text;

      // remove the words a, an, the, of, to, in, for, with, on, and, but, for, or, nor, so, yet
      $slug = preg_replace('/\b(a|an|the|of|to|in|for|with|on|and|but|for|or|nor|so|yet)\b/i', '', $slug);

      // remove apostrophes
      $slug = str_replace("'", '', $slug);

      // remove right single quote marks
      $slug = str_replace('’', '', $slug);

      // This doctrine provided function does most of the grunt work
      $slug = Doctrine_Inflector::urlize($slug);

      return $slug;
   }
}

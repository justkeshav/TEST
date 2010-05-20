<?php

require_once dirname(__FILE__).'/../bootstrap/unit.php';

class ltkUrlTest extends UnitTestCase
{
   function testSlugGeneration()
   {
      // Only allowable chars are a-z, 0-9, and -
      $this->assertEqual('legal-char-test-acaijkte', ltkUrl::generateSlug('Legal Char_Test:åçÀĳĶŦé'));

      // May not start or end with -
      $this->assertEqual('que-pasa', ltkUrl::generateSlug('¿Que pasa?'));

      // Remove all apostrophes/right single quotes and replace other punctuation with -
      $this->assertEqual('joshs-test-its-good', ltkUrl::generateSlug("Josh's Test: it’s good!"));

      // May not have consecutive -
      $this->assertEqual('josh-aubry', ltkUrl::generateSlug('Josh & Aubry'));

      // Remove articles (a, an, the), prepositions (of, to, in, for, with, on), and conjunctions (and, but, for, or, nor, so, yet)
      $this->assertEqual('he-was-room-he-was-not', ltkUrl::generateSlug('He Was in The Room Yet he was not'));
   }
}

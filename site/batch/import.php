<?php

$dir = dirname(__FILE__);

$args = implode(' ', array_slice($argv, 1));

$files = array(
   'import-categories.php',
   'import-articles.php',
   'import-slideshows.php',
   'import-quizzes.php'
);

foreach($files as $file)
{
   $cmd = "php $dir/$file $args";
   echo "$cmd\n";
   passthru($cmd);
}

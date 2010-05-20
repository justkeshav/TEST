<?php

use_stylesheet('quiz');
use_javascript('quiz');

decorate_with(false);

if (!isset($score))
{
	$score = 0;
}
if (!isset($sid))
{
	$sid = 0;
}

// url params for next page in flow
$showResults = $quiz->result_format == QUIZ::RESULT_AFTER || $quiz->result_format == QUIZ::RESULT_BOTH;
$isQuestion = strcmp($action, 'question') == 0;
$isResults = strcmp($action, 'result') == 0;
$nextAction = "?" . ( $quiz->isLastPage() && ($isResults || (!$showResults && $isQuestion)) ? 'summary' :
                 ( $showResults && $isQuestion ? 'result' : 'question' ) );
$nextPage = "&page=" . ( strcmp($nextAction,'?question') == 0 ? $quiz->getPage() + 1 : $quiz->getPage() );

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
   <?php include_http_metas() ?>
   <?php include_metas() ?>
   <?php include_title() ?>
   <link rel="shortcut icon" href="/favicon.ico" />
   <?php include_stylesheets() ?>
</head>

<body>

<?php include($action . ".php") ?>

<?php include_javascripts() ?>

</body>

</html>

<?php

// calculate % score
$questionCount = $quiz->getPage() * $quiz->questions_per_page;
if ($questionCount == 0)
{
	$questionCount = $quiz->questions_per_page;
}
$percentage = (int)(($score / $questionCount) * 100);

?>

<div id="quiz">

   <?php if ($quiz->show_accuracy) : ?>
      <div id="accuracy">You are <?php echo $percentage; ?>% accurate.</div>
   <?php endif; ?>

   <?php  if ($quiz->result_format == Quiz::RESULT_END || $quiz->result_format == Quiz::RESULT_BOTH) : ?>

      <?php if ($quiz->format == Quiz::TYPE_QUIZ) : ?>
         <div id="score">
	        <p>Your Quiz result:<p>
	        <?php
	        echo "<span>";
	        if ($percentage <= 20)
	        {
		       echo $quiz->result20;
            }
            elseif ($percentage <= 40)
            {  
	           echo $quiz->result40;
	        }   
            elseif ($percentage <= 60)
            {
	           echo $quiz->result60; 
	        }
            elseif ($percentage <= 80)
            {
	           echo $quiz->result80;  
	        }
            else
            {
	           echo $quiz->result100;
            }
            echo "</span>";
            ?>
         </div>
      <?php endif; ?>

   <?php endif; ?>

   <div id="message"><?php echo $quiz->message, $quiz->id, $quiz->result60; ?></div>

</div>

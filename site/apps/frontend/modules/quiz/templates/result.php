
<?php foreach($quiz->getQuestions() as $q) : ?>

<?php
$selectedAnswer = $selectedAnswers["Q" . $q->id];
$answer = strtok($selectedAnswer->message, QuizContent::DELIMITER);
$response = strtok(QuizContent::DELIMITER);
?>

<div id="results">
	
   <?php if($quiz->format == Quiz::TYPE_QUIZ) : ?><p class="response"><?php echo $response; ?></p><?php endif; ?>

   <?php if($quiz->result_type == Quiz::SHOW_RESULTS) : ?>
      Your Answer:<br/>
      <span class="answer"><?php echo $answer; ?></span>

      <div id="graph">
	     <h4>What others answered to:</h4>
	     <?php echo $q->message; ?>
			
	     <div id="bars">
	        <?php
            $totalSelected = $quiz->getResponseCount($q);
	        foreach ($quiz->getAnswers($q) as $a)
   	        {
	           echo strtok($a->message, QuizContent::DELIMITER);
	           $percentage = (int)(($a->total_count / $totalSelected) * 100);
	           $graphWidth = (int)$percentage * 3.9;
	           $graphWidth = ($graphWidth == 0 ? 1 : $graphWidth);
	        ?>
	
	        <div id="bar">
	           <img src="/css/images/ui-graph.purple.1x15.gif" width="<?php echo $graphWidth; ?>" height="15">
	           <span><?php echo $percentage; ?>%</span>
	        </div>
	        <?php
	        }
	        ?>
	     </div>
      </div>
   <?php endif; ?>

</div>

<?php endforeach; ?>

<form action="<?php echo url_for("@content?url={$title->url}{$nextPage}{$nextAction}") ?>" method="POST">
   <input type=hidden name="score" value="<?php echo $score ?>">
   <input type=hidden name="sid" value="<?php echo $sid ?>">
   <input type="submit" value="Next >>" />
</form>


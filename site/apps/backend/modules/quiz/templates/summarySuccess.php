<?php use_helper('I18N', 'Date') ?>
<?php include_partial('quiz/assets') ?>

<?php use_stylesheet('quiz') ?>

<div id="sf_admin_container">

  <h1><?php echo __('Summary for Quiz \'%%title%%\'', array('%%title%%' => $quiz->getTitle()), 'messages') ?></h1>

  <?php include_partial('quiz/flashes') ?>

  <div id="sf_admin_content">

	<?php foreach($quiz->getQuestions() as $q) : ?>
	
	<?php $totalSelected = $quiz->getResponseCount($q); ?>
	<div id="results">
		<div id="graph" style="margin-bottom: 24px;">
		   <h4>Question: <?php echo $q->message; ?> (<?php echo $totalSelected ?> users)</h4>
		   
		   <div id="bars">
			  <?php if($totalSelected != 0) : ?>
		         <?php
		         foreach ($quiz->getAnswers($q) as $a)
	   	         {
		            echo strtok($a->message, QuizContent::DELIMITER);
		            $percentage = (int)(($a->total_count / $totalSelected) * 100);
		            $graphWidth = (int)$percentage * 3.9;
		            $graphWidth = ($graphWidth == 0 ? 1 : $graphWidth);	
		         ?>

		         <div id="bar">
		            <img src="/images/quiz_content/ui-graph.purple.1x15.gif" width="<?php echo $graphWidth; ?>" height="15">
		            <span>
		               <?php echo $percentage; ?>%
		               <?php if($a->total_count > 0) : ?>
			              (<?php echo $a->total_count; ?> user<?php echo ($a->total_count > 1 ? 's' : ''); ?>)
			           <?php endif; ?>
				    </span>
		         </div>
		         <?php
		         }
		         ?>
		      <?php else : ?>
			     No results available
			  <?php endif; ?>
		   </div>
		</div>
	</div>

	<?php endforeach; ?>

  </div>

</div>

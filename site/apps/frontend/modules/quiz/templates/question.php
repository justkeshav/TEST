<div id="quiz">

   <h1><?php echo $title->title ?></h1>

   <?php if($quiz->getPage() == 1): ?><p><?php echo $quiz->description1; ?></p><?php endif; ?>

   <form action="<?php echo url_for("@content?url={$title->url}{$nextPage}{$nextAction}") ?>" method="POST">
	
   <?php foreach($quiz->getQuestions() as $q) : ?>
	
      <div id="image">
         <?php if(!empty($q->image_link)): ?><a href="<?php echo $q->image_link ?>" rel="external,nofollow"><?php endif; ?>
         <img src="<?php echo $q->Image->genUrl('quiz_content') ?>" />
         <?php if(!empty($q->image_link)): ?></a><?php endif; ?>
      </div>

      <div id="question"><?php echo $quiz->getPage() . '. ' . $q->message; ?></div>

      <?php
      foreach ($quiz->getAnswers($q) as $a)
      {
         $choices[$a->id] = strtok($a->message, QuizContent::DELIMITER);
      }
      
      $form->setWidget("Q" . $q->id, new sfWidgetFormChoice(array(
         'choices'  => $choices,
         'label'    => '&nbsp;',
         'expanded' => ($q->format == QuizContent::FORMAT_MULTIPLE)
      )));
      ?>

   <?php endforeach; ?>

   <div id="answers">
      <table>
        <?php echo $form; ?>
      </table>
   </div>

   <?php if($quiz->getPage() == 1): ?><p><?php echo $quiz->description2; ?></p><?php endif; ?>
	
   <input type=hidden name="score" value="<?php echo $score ?>">
   <input type=hidden name="sid" value="<?php echo $sid ?>">
   <input type="submit" value="Next >>" />
</form>

</div>
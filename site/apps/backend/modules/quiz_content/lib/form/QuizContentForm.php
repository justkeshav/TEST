<?php

class BackendQuizContentForm extends autoQuizContentForm
{
  public function configure()
  {
	  $quiz = $this->getObject()->Quiz;
	
      if(!$this->isNew)
      {
         // fetch answers for this question
         $answers = $quiz->getAnswers($this->getObject());
      }

      $this->widgetSchema['quiz_id'] = new sfWidgetFormInputHidden();
      $this->setDefault('quiz_id', $this->getObject()->quiz_id);

      $this->widgetSchema['type'] = new sfWidgetFormInputHidden();
      $this->setDefault('type', $quiz->format);
      $this->setValidator('type', new sfValidatorPass());

      $this->widgetSchema['format'] = new sfWidgetFormChoice(array('choices' => array(
         QuizContent::FORMAT_MULTIPLE => 'Multiple Choice',
         QuizContent::FORMAT_FREETEXT => 'Free Text',
         QuizContent::FORMAT_DROPDOWN => 'Dropdown',
      )));

      $this->widgetSchema['image_id'] = new ltksfWidgetFormImage(array(
         'image' => $this->getObject()->Image,
         'channel_id' => $quiz->channel_id
      ));

      // append input fields for question answers/responses
      for($row=1; $row<=10; $row++)
      {
         $field = 'answer' . $row;
         $this->widgetSchema[$field] = new sfWidgetFormInput(array('label'=>'#'.$row));
         $this->setValidator($field, new sfValidatorPass()); 
         $this->widgetSchema[$field . '_response'] = new sfWidgetFormInput(array('label'=>'&nbsp;'));
         $this->setValidator($field . '_response', new sfValidatorPass());
         $this->widgetSchema[$field . '_valid'] = new sfWidgetFormInputCheckbox(array('label'=>'Correct?'));
         $this->setValidator($field . '_valid', new sfValidatorPass());

         // if !new pre-populate from database
         if ( !$this->isNew() && $row <= sizeof($answers) )
         {     
            $this->setDefault($field, strtok($answers[$row-1]->message, QuizContent::DELIMITER));
            $this->setDefault($field . '_response', strtok(QuizContent::DELIMITER));
            $this->setDefault($field . '_valid', $answers[$row-1]->is_valid);
         }
       }
  }

  protected function doSave($con = null)
  {
     parent::doSave($con);

     $quiz_content = $this->getObject();

     // save image
     if($quiz_content->image_id != $quiz_content->Image->id)
     {
        $image = Doctrine::getTable('Image')->find($quiz_content->image_id);
        if(!$image->hasSize('quiz_content'))
        {
           if(ltksfImageProvider::getInstance()->createSize($image, 'quiz_content', 600, 500))
           {
              $image->save($con);
           }
           else
           {
              throw new RuntimeException("There was a problem saving the question image.");
           }
        }
     }

     // save answers

     $quiz = $this->getObject()->Quiz;

     if (!$this->isNew())
     {
           $answers = $quiz->getAnswers($this->getObject());
     }

     for ($row=1; $row<=10; $row++)
     {
        $isEmpty = (strcmp($this->getValue('answer' . $row), '') == 0);
        if( $isEmpty && ($this->isNew() || $row > sizeof($answers)) )
        {
           continue;
        }
        else if( $this->isNew() || $row > sizeof($answers) )
        {
           $answer = new QuizContent();
           $answer->quiz_id = $this->getObject()->quiz_id;
           $answer->parent_id = $quiz_content->id;
        }
        else 
        {
           $answer = $answers[$row-1]; 
        }

        $message = $this->getValue('answer' . $row);
        if (strcmp($message, '') != 0)
        {
           $answer->message = $message . QuizContent::DELIMITER;
           if ($quiz->format == Quiz::TYPE_QUIZ)
           {
	          $answer->message .= $this->getValue('answer' . $row . '_response');
	          $answer->is_valid = ( strcmp($this->getValue('answer' . $row . '_valid'),'on') == 0 );
	       }
           $answer->save($con);
        }
        else
        {
           $answer->delete($con);
        }
     }

   }
}

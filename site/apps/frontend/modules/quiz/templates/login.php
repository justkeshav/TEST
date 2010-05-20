<?php

?>

<div>

<p><?php echo $quiz->login_text ?></p>

<form action="<?php echo url_for("@content?url={$title->url}{$nextPage}{$nextAction}") ?>" method="POST">

    <table>
      <?php echo $form; ?>
    </table>

   <input type=hidden name="score" value="<?php echo $score ?>">
   <input type=hidden name="sid" value="<?php echo $sid ?>">

   <input type="submit" value="Submit" />
</form>

<?php if ($quiz->login_position == Quiz::LOGIN_OPTIONAL) : ?>
   <p>
   <form action="<?php echo url_for("@content?url={$title->url}{$nextPage}{$nextAction}") ?>" method="POST">
	  <input type="hidden" name="login" value="anonymous">
      <input type="submit" value="No thanks, skip this step >>">
   </form>
   </p>
<?php endif; ?>

</div>

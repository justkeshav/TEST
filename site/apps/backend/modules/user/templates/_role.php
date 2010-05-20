<?php
   if ($sf_guard_user->isAdmin())
   {
      echo 'Admin';
   }
   else
   {
      switch ($sf_guard_user->getRole())
      {
         case sfGuardUser::ROLE_EDITOR:
            echo 'Editor';
            break;
         case sfGuardUser::ROLE_WRITER:
            echo 'Writer';
            break;
         case sfGuardUser::ROLE_TITLER:
            echo 'Titler';
           break;
         default:
            break;
      }
   }
?>
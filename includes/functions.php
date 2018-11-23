<?php
// Function library used on most pages
session_start();
function CheckStatus($StatusArray){
   $Status = '';
   $return = FALSE;
   if(isset($_SESSION['UserStatus'])){
      foreach($StatusArray as $Status){
         if($_SESSION['UserStatus'] == $Status){
            $return = TRUE;
         }
      }
   }
   return $return;   
}
?>

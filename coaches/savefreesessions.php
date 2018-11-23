<?php
include('../includes/functions.php');
if(CheckStatus(array('Admin','Coach'))){ 
   require("../config/dbconfig.php");
   $PageMode = $_REQUEST['PageMode'];
   $dsn = "mysql:dbname=".DB_NAME.";host=".DB_HOST;
   $user = DB_USER;
   $pass = DB_PASS; 
   
   if($PageMode == "AddSession"): 
      if(1){
         $sql = "INSERT INTO Sessions (CoachID, ClientID, Status, SessionTime) "
            ."VALUES (0".$_SESSION['UserID'].",0,'Session'," 
            ."'".$_REQUEST['Year']."-".$_REQUEST['Month']."-".$_REQUEST['Day']
            ." ".$_REQUEST['Hour'].":00:00') ";
      }else{
         $sql = "INSERT INTO Sessions (CoachID, ClientID, Status, SessionTime) "
            ."VALUES (?,0,'Session','?')";
         echo $sql;
         $stmt = $dbh->prepare($sql);
         $stmt->execute(array('0'.$_SESSION['UserID'],
             $_REQUEST['Year'].'-'.$_REQUEST['Month'].'-'.$_REQUEST['Day']
             .' '.$_REQUEST['Hour'].':00:00'));
      }
    endif;
    if($PageMode == "DelSession"):
      $sql = "DELETE FROM Sessions WHERE Status = 'Session' "
         ."AND CoachID = 0".$_SESSION['UserID']." "  
         ."AND SessionTime = '".$_REQUEST['Year']
         ."-".$_REQUEST['Month']
         ."-".$_REQUEST['Day']
         ." ".$_REQUEST['Hour']
         .":00:00'";
    endif;
    try{
      $dbh = new PDO($dsn, $user, $pass);
      $dbh->query($sql); 
      echo $sql;
      echo "Done";
   } catch (PDOException $e) {
      echo $sql;
      echo 'Connection failed: '.$e->getMessage();
   }
   unset($dbh);
}?>
<script onload="parent.setfocus(); window.close();"/>

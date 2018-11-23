<?php
include('../includes/functions.php');
if(!CheckStatus(array('Admin','Client','Visitor'))){ 
   Header("Location: ../Public/Login.php");
} else { 
   $Members = 'Visitor';
   require("../config/dbconfig.php");
   $dsn = "mysql:dbname=".DB_NAME.";host=".DB_HOST;
   $user = DB_USER;
   $pass = DB_PASS;
   try{
      $dbh = new PDO($dsn, $user, $pass);
   } catch (PDOException $e) {
      echo 'Connection failed: '.$e->getMessage();
   }?>
<?php include('../includes/header.php'); ?>
<?php include('../includes/leftmenu.php'); ?>
<DIV class="content"><?php 
   $UserID = (isset($_SESSION['UserID'])?$_SESSION['UserID']:0);
   $Step = (isset($_REQUEST['Step'])?$_REQUEST['Step']:0);
   $PageMode = (isset($_REQUEST['PageMode'])?$_REQUEST['PageMode']:'');
   $ValueName = (isset($_REQUEST['ValueName'])?$_REQUEST['ValueName']:'');
   $ValueDesc = (isset($_REQUEST['ValueDesc'])?$_REQUEST['ValueDesc']:'');
   $DelVal = (isset($_REQUEST['DelVal'])?$_REQUEST['DelVal']:'');
   if($PageMode=='Back' and $Step>1) $Step--;
   if($PageMode=='Continue') $Step++;
   if($PageMode = 'Add' and !trim($ValueName) == ''){ 
      $sql = "SELECT MAX(Value_Order) AS Max_Order FROM User_Values "
         ."WHERE UserID = 0".$UserID." ";
      $query = $dbh->prepare($sql);
      $query->execute();
      $row = $query->fetch();
      $ValueOrder = 0;
      if($row){
         $ValueOrder = $row['Max_Order'];
      }
      if(!isset($ValueOrder)){
         $ValueOrder = 1;
      }else{ 
         $ValueOrder++; 
      }
      $sql = "INSERT INTO User_Values (UserID, " 
         ."Value_Order, Value_Name, Value_Desc, Value_Score " 
         .") VALUES (".$UserID.", " 
         .$ValueOrder.", '".trim($ValueName)."', " 
         ."'".trim($ValueDesc)."', 0 "
         .") ";
      echo $sql;
      $query = $dbh->query($sql);
      $query->execute();
   }
   if($PageMode==='Del' and !trim($DelVal) === ''){
      $sql = "DELETE FROM User_Values WHERE UserID = 0".$UserID." " 
         ."AND Value_Order IN (".trim($DelVal).") ";
      $query = $dbh->query($sql);
      $query->execute();
   }?>
<form name="Values" method="post"><?php
if($Step==0){ // Introduction ?>
<h3>An Introduction to Values - What Matters Most</h3>
<p>This pages provides a tool that allows you to list and prioritise your core values. 
Core values are important because they have a profound influence over the decisions you
make, the way you respond to particular situations, and ... </p>
<p>Becoming aware of your deeply help values will help you understand yourself better 
when you feel stressed or frustrated, and by being very clear in your own mind what 
matters most to you, you are able to be more decisive and confident in new situations.</p>
<table width="400">
<tr><td align="right"><input name="PageMode" type="submit" value="Continue" /></td></tr>
</table><?php
}
if($Step==1){ // Listing your values
   $sql = "SELECT Value_Name, Value_Desc, Value_Order, Value_Score, Value_Added "
      ."FROM User_Values WHERE UserID = ".$UserID." " 
      ."ORDER BY Value_Order ";
   $query = $dbh->prepare($sql);
   $query->execute();
   $row = $query->fetch();
  // if($row){?>
   <h3><?php echo $_SESSION['FirstName'];?>'s Values</h3>
   <table width="400">
   <tr>
      <td><B>Value</B></td>
      <td><b>Description</b></td>
      <td><b>Score</b></td>
      <td></td>
   </tr>
   <tr>
      <td colspan=4><hr /></td>
   </tr><?php
      while($row){?>
   <tr>
      <td><?php echo $row['Value_Name'];?></td>
      <td><?php echo $row['Value_Desc'];?></td>
      <td align="right"><?php echo $row['Value_Score'];?></td>
      <td><input name="DelVal" type="checkbox" 
         value="<?php echo $row['Value_Order'];?>" /></td>
   </tr><?php
      $row = $query->fetch();
      }?>
   <tr>
      <td><input name="ValueName" type="text" size=15 value=""/></td>
      <td><input name="ValueDesc" type="text" size=50 value="" /></td>
      <td><input name="PageMode" type="submit" value="Add" /></td>
      <td><input name="PageMode" type="submit" value="Del"/></td>
   </tr>
   <tr>
      <td colspan=4><hr /></td>
   </tr>
   <tr>
      <td colspan=2><input name="PageMode" type="submit" value="Back" /></td>
      <td colspan=2><input name="PageMode" type="submit" value="Continue" /></td>
   </tr>
   </table><?php
   unset($row);
   unset($query);
   unset($dbh);
}
if($Step==2){ // Prioritising your values ?>
<h3>Prioritising your Values</h3>
<p>Now that you have created a list of values, we are going to do a very quick exercise
to help put the things that matter most to you in some kind of order.</P>
<p>The best way to handle the following exercise is to do it as quickly as you can, 
not spending too much time thinking about it, but rather going with your first instinctive
reaction, as this is usually the most honest. If you try and analyse what you are doing
or trying to guess the result you are less likely to discover the truth.</p>
<p>You will now be presented with all your values one pair at a time and the idea is 
to pick simply pick the one that sticks you as most important to you. When finished 
your values will be ordered for you according to the choices you made. </p><?php 
   $sql = "SELECT COUNT(0) AS Value_Count FROM User_Values " 
      ."WHERE UserID = 0".$UserID." AND Value_Name <> '' "; 
   $query = $dbh->prepare($sql);
   $query->execute();
   $row = $query->fetch();
   if($row){
   $ValueCount = $row['Value_Count'];
   if($ValueCount > 2){ 
      $sql = "SELECT Value_Name, Value_Desc FROM User_Values "
         ."WHERE UserID = 0".$UserID." "; 
      $query = $dbh->prepare($sql);
      $query->execute();
      $row = $query->fetch();
      $Values = array();
      $ValueKey = 0; 
      while($row){
         $Values[$ValueKey] = $row['Value_Name']; 
   //      echo $row['Value_Name']."\n";
         $ValueKey++;
         $row = $query->fetch();
      }  
      $ValuePairs = 0; 
      $ValueStart = 1;
      $Toggle = 0; 
      $sql = "UPDATE User_Values SET Value_Score = 0 "
         ."WHERE UserID = 0".$UserID." ";
      $query = $dbh->query($sql);
      $query->execute();
      $sql = "DELETE FROM ValuesArray WHERE UserID = 0".$UserID." ";
      $query = $dbh->query($sql);
      $query->execute();
      for($Value1=0;$Value1<$ValueCount;$Value1++){
  //       echo "Value1:".$Value1;
         for($Value2=0;$Value2<$ValueCount;$Value2++){
  //          echo "Value2:".$Value2;
            $sql = "INSERT INTO valuesarray VALUES (".$UserID.", " 
               .rand(1,100).", '";
            if($Toggle==0){ 
               $sql.=" ".$Values[$Value1]."', '".$Value[$Value2]."') ";
               $Toggle = 1;
            }else{
               $sql.=" ".$Values[$Value2]."', '".$Values[$Value1]."') ";
               $Toggle = 0;
            }
  //          echo $sql;
            $query = $dbh->query($sql);
           // $query->execute();            
            $ValuePairs++; 
         }
         $ValueStart++;
     }
   }
   unset($row);
   unset($query);?>
<table width="400">
<tr><td align="right"><input name="PageMode" type="submit" value="Continue" /></td></tr>
</table><?php
}
if($Step == 3){ // Prioritising your values
   if(!$ValueName ==''){
      $sql = "UPDATE User_Values SET Value_Score = Value_Score + 1 " 
         ."WHERE UserID = ".$UserID." AND Value_Name = '".$ValueName."' ";
         $query = $dbh->query($sql);
      //   $query->execute();            
   }
   $Value1=(isset($_REQUEST['Value1'])?$_REQUEST['Value1']:'');
   $Value2=(isset($_REQUEST['Value2'])?$_REQUEST['Value2']:'');
   if($Value1=='' and $Value2==''){ 
      $sql = "DELETE FROM ValueArray WHERE UserID = ".$UserID." " 
         ."AND Value1 = '".$Value1."' AND Value2 = '".$Value2."' ";
      $query = $dbh->query($sql);
      $query->execute();            
   }
   $sql = "SELECT Value1, Value2 FROM ValueArray "  
      ."WHERE USERID = ".$UserID." " 
      ."ORDER BY ListOrder ";
      $query = $dbh->prepare($sql);
      $query->execute();            
      $row=$query->fetch();
      if($row){?>
<h3>My Values</h3>
<table width="400">
    <tr><td colspan=3 align="center"><h4>Which one is most important to you?</h4></td></tr>
   <tr><td colspan=3 align="center">&nbsp;</td></tr>
   <tr>
      <td width=45% align="right">
         <input type="hidden" name="value1" value="<?php echo $row['Value1'];?>" />
         <b><?php echo $row['Value1'];?></b> 
         <input type="radio" name="value_name" value="<?php echo $row['Value1'];?>" 
          onclick="this.form.submit();"/>
      </td>
      <td width=10% align="center">OR</td>
      <td width=45% align="left">
         <input type="radio" name="value_name" value="<?php echo $row['Value2'];?>" 
            onclick="this.form.submit();"/>
         <b><?php echo $row['Value2'];?></b>
         <input type="hidden" name="value2" value="<?php echo $row['Value2'];?>" />
      </td>
   </tr>
   </table><?php
      }else{
         $sql = "SELECT Value_Name FROM User_Value " 
            ."WHERE UserD = ".$UserID." " 
            ."ORDER BY Value_Score DESC ";
         $query = $dbh->prepare($sql);
         $query->execute();            
         $row = $query->fetch();
         $ValueKey = 1;
         while($row){
            $sql = "UPDATE User_Values SET "
               ."Value_Order = ".$ValueKey." " 
               ."WHERE UserID = ".$UserID." " 
               ."AND Value_Name = '".$row['Value_Name']."' ";
            $ValueKey++;
            $query = $dbh->query($sql);
            $query->execute();            
            $row = $query->fetch();
         }
         $sql = "SELECT Value_Name, Value_Desc, Value_Order, Value_Score, Value_Added " 
            ."FROM User_Values WHERE UserID = ".$UserID." ORDER BY VALUE_ORDER ";
         $query = $dbh->prepare($sql);
         $query->execute();            
         $row = $query->fetch();
      }?>   
   <h3><?php echo $_SESSION['FirstName'];?>'s Values</h3>
   <table width="400">
   <tr>
      <td><B>Value</B></td>
      <td><b>Description</b></td>
      <td align="right"><b>Score</b></td>
   </tr>
   <tr>
      <td colspan=3><hr /></td>
   </tr><?php
      while($row){?>
   <tr>
      <td><?php echo $row['Value_Name'];?></td>
      <td><?php echo $row['Value_Desc'];?></td>
      <td align="right"><?php echo $row['Value_Score'];?></td>
   </tr><?php
         $row = $query->fetch();
      }?>
   <tr>
      <td colspan=3><hr /></td>
   </tr>
   </table>
   <h4>What do you think about your results above?</h4>
   <p>Are there any surprises?<br />    
   </p><?php
   }
   unset($row);
   unset($query);
}
unset($dbh);?>
<input name="Step" type="hidden" value="<?php echo $Step;?>" />
</form>  
</DIV> <!-- content -->
<?php include('../includes/footer.php'); ?><?php
}?>


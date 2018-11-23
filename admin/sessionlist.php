<?php
include('../includes/functions.php');
if(!CheckStatus(array('Admin'))){ 
   Header("Location: ../Public/Login.php");
} else {
   $Members = "Admin";
require("../config/dbconfig.php");
$dsn = "mysql:dbname=".DB_NAME.";host=".DB_HOST;
$user = DB_USER;
$pass = DB_PASS;   
if(isset($_REQUEST['PageMode'])){
   $PageMode = $_REQUEST['PageMode'];
} else {
   $PageMode = "List";
}
if(isset($_REQUEST['ListOrder'])){
   $ListOrder = $_REQUEST['ListOrder'];
} else {
   $ListOrder = "SESSIONTIME";
}
$qDay = (isset($_REQUEST['qDay'])?$_REQUEST['qDay']:"");
$qDOW = (isset($_REQUEST['qDOW'])?$_REQUEST['qDOW']:"");
$qMonth = (isset($_REQUEST['qMonth'])?$_REQUEST['qMonth']:"");
$qYear = (isset($_REQUEST['qYear'])?$_REQUEST['qYear']:"");
$qHour = (isset($_REQUEST['qHour'])?$_REQUEST['qHour']:"");
$qStatus = (isset($_REQUEST['qStatus'])?$_REQUEST['qStatus']:"");
$qCoachID = (isset($_REQUEST['qCoachID'])?$_REQUEST['qCoachID']:"");
$qClientID = (isset($_REQUEST['qClientID'])?$_REQUEST['qClientID']:"");
$pDay = (isset($_REQUEST['pDay'])?$_REQUEST['pDay']:"");
$pDOW = (isset($_REQUEST['pDOW'])?$_REQUEST['pDOW']:"");
$pMonth = (isset($_REQUEST['pMonth'])?$_REQUEST['pMonth']:"");
$pYear = (isset($_REQUEST['pYear'])?$_REQUEST['pYear']:"");
$pHour = (isset($_REQUEST['pHour'])?$_REQUEST['pHour']:"");
$pStatus = (isset($_REQUEST['pStatus'])?$_REQUEST['pStatus']:"");
$pCoachID = (isset($_REQUEST['pCoachID'])?$_REQUEST['pCoachID']:"");
$pClientID = (isset($_REQUEST['pClientID'])?$_REQUEST['pClientID']:"");
$pSessionID = (isset($_REQUEST['pSessionID'])?$_REQUEST['pSessionID']:"");
$pSessionRate = (isset($_REQUEST['pSessionRate'])?$_REQUEST['pSessionRate']:"");
$pFeesPaid = (isset($_REQUEST['pFeesPaid'])?$_REQUEST['pFeesPaid']:"");
$pCoachPaid = (isset($_REQUEST['pCoachPaid'])?$_REQUEST['pCoachPaid']:"");
if($PageMode == "Update"){ 
   if(!$pSessionID){ 
      if($pStatus == ""){
         $pStatus = "Session";
      }
      $sql = "UPDATE Sessions SET " 
         ."SessionTime = '".$pMonth."/".$pDay 
         ."/".$pYear." ".$pHour.":00:00', "
         ."CoachID = 0".$pCoachID.", "
         ."ClientID = 0".$pClientID.", "
         ."SessionRate = 0".$pSessionRate.", "
         ."FeesPaid = 0".$pFeesPaid.", "
         ."CoachPaid = 0".$pCoachPaid.", "  
         ."Status = '".$pStatus."' " 
         ."WHERE SessionID = " & pSessionID & "; ";
      try{
         $dbh = new PDO($dsn, $user, $pass);
      } catch (PDOException $e) {
         echo 'Connection failed: '.$e->getMessage();
      }
      $results = $dbh->query($sql);
   }
   $PageMode = "List";
   $pSessionID = "";
}
if($PageMode == "Insert"){ 
   $sql = "INSERT INTO Sessions (" 
      ."   SessionTime, CoachID, ClientID, Status, SessionRate, FeesPaid, CoachPaid " 
      .") VALUES ( " 
      ." '".$pMonth."/".$pDay."/".$pYear." ".$pHour.":00:00', "
      ." 0".$pCoachID.", 0".$pClientID.", " 
      ." '".$pStatus."', 0".$pSessionRate.", " 
      ." 0".$pFeesPaid.", 0".$pCoachPaid."); ";  
   try{
      $dbh = new PDO($dsn, $user, $pass);
   } catch (PDOException $e) {
     echo 'Connection failed: '.$e->getMessage();
   }
   $results = $dbh->query($sql);
   $PageMode = "List";
   $pSessionID = "";
}
if($PageMode == "Delete"){ 
   if(!$pSessionID){
      $sql = "DELETE FROM SESSIONS WHERE SESSIONID = ".$pSessionID."; ";
      try{
         $dbh = new PDO($dsn, $user, $pass);
      } catch (PDOException $e) {
         echo 'Connection failed: '.$e->getMessage();
      }
      $results = $dbh->query($sql);
      $PageMode = "List";
      $pSessionID = "";
   }
}
//
// Delete all expired and open sessions
//
$EarliestDate = date('Y-m-d H:i:s');
if(!isset($ThisYear) or $ThisYear == ""){ 
   $ThisYear = date('Y');
}
if(!isset($ThisMonth) or $ThisMonth == ""){ 
   $ThisMonth = date('m');
}
if(!isset($ThisDay) or $ThisDay == ""){ 
   $ThisDay = date('d');
}
//$FirstDayThisMonth = DateSerial(Year($EarliestDate),Month($EarliestDate), 1);
//$FirstDayThisMonth = mktime(Time('01/'.Month($EarliestDate).'.'.Year($EarliestDate));
//$FirstDayThisMonth = date("t/m/Y", strtotime("this month"));
$FirstDayThisMonth = date("t/m/Y", strtotime($EarliestDate));
$sql = "DELETE FROM Sessions " 
    ."WHERE SessionTime < '".date("Y", strtotime($EarliestDate))."/"
    .date("m", strtotime($EarliestDate))."/"
    .date("d", strtotime($EarliestDate))."' " 
    ."AND Status = 'Session' ";
try{
   $dbh = new PDO($dsn, $user, $pass);
} catch (PDOException $e) {
   echo 'Connection failed: '.$e->getMessage();
}
$results = $dbh->query($sql);?>
<?php include('../includes/header.php'); ?>
<?php include('../admin/adminmenu.php'); ?>
<DIV class="content">   
   <p><a href="../public/lifespeak.asp">Home</a> | 
   <a href="../admin/adminpage.asp">Admin</a> | 
   <b>Session Listing</b></p>
  <form name="pageform" method="post">
   <input name="UserID" type="hidden" value="" />
   <input name="ListOrder" type="hidden" value="<?php echo $ListOrder;?>" />
   <input name="PageMode" type="hidden" value="<?php echo $PageMode;?>" />
   <table>
   <tr>
   <td valign="top">
        <table style="<?php
if($PageMode=="Add" OR !$pSessionID == ""){
   echo "visibility:Hidden; position:absolute;";
} else {
   echo "visibility:Visible; position:absolute;";
}?>">
      <tr><td colspan=2><h3>Session Listing Filter</h3></td></tr>
      <tr>
         <td>Time</td>
         <td>
            <select name="qHour">
            <option></option>
            <option value="08" <?php if($qHour=="08"){echo "Selected";}?>>08:00</option>
            <option value="09" <?php if($qHour=="09"){echo "Selected";}?>>09:00</option>
            <option value="10" <?php if($qHour=="10"){echo "Selected";}?>>10:00</option>
            <option value="11" <?php if($qHour=="11"){echo "Selected";}?>>11:00</option>
            <option value="12" <?php if($qHour=="12"){echo "Selected";}?>>12:00</option>
            <option value="13" <?php if($qHour=="13"){echo "Selected";}?>>13:00</option>
            <option value="14" <?php if($qHour=="14"){echo "Selected";}?>>14:00</option>
            <option value="15" <?php if($qHour=="15"){echo "Selected";}?>>15:00</option>
            <option value="16" <?php if($qHour=="16"){echo "Selected";}?>>16:00</option>
            <option value="17" <?php if($qHour=="17"){echo "Selected";}?>>17:00</option>
            <option value="18" <?php if($qHour=="18"){echo "Selected";}?>>18:00</option>
            <option value="19" <?php if($qHour=="19"){echo "Selected";}?>>19:00</option>
            <option value="20" <?php if($qHour=="20"){echo "Selected";}?>>20:00</option>
            <option value="21" <?php if($qHour=="21"){echo "Selected";}?>>21:00</option>
            <option value="22" <?php if($qHour=="22"){echo "Selected";}?>>22:00</option>
            </select>
           </td>
      </tr>
        <tr>
         <td>Day</td>
         <td>
            <select name="qDOW" > 
            <option></option>
            <option value = 1 <?php if($qDOW=="1"){echo "Selected";}?>>Sun</option>
            <option value = 2 <?php if($qDOW=="2"){echo "Selected";}?>>Mon</option>
            <option value = 3 <?php if($qDOW=="3"){echo "Selected";}?>>Tue</option>
            <option value = 4 <?php if($qDOW=="4"){echo "Selected";}?>>Wed</option>
            <option value = 5 <?php if($qDOW=="5"){echo "Selected";}?>>Thu</option>
            <option value = 6 <?php if($qDOW=="6"){echo "Selected";}?>>Fri</option>
            <option value = 7 <?php if($qDOW=="7"){echo "Selected";}?>>Sat</option>
            </select>
          </td>
        </tr>
      <tr>
         <td>Date</td>
         <td>
            <select name="qDay" > 
            <option></option>
            <option <?php if($qDay=="1"){echo "Selected";}?>>1</option>
            <option <?php if($qDay=="2"){echo "Selected";}?>>2</option>
            <option <?php if($qDay=="3"){echo "Selected";}?>>3</option>
            <option <?php if($qDay=="4"){echo "Selected";}?>>4</option>
            <option <?php if($qDay=="5"){echo "Selected";}?>>5</option>
            <option <?php if($qDay=="6"){echo "Selected";}?>>6</option>
            <option <?php if($qDay=="7"){echo "Selected";}?>>7</option>
            <option <?php if($qDay=="8"){echo "Selected";}?>>8</option>
            <option <?php if($qDay=="9"){echo "Selected";}?>>9</option>
            <option <?php if($qDay=="10"){echo "Selected";}?>>10</option>
            <option <?php if($qDay=="11"){echo "Selected";}?>>11</option>
            <option <?php if($qDay=="12"){echo "Selected";}?>>12</option>
            <option <?php if($qDay=="13"){echo "Selected";}?>>13</option>
            <option <?php if($qDay=="14"){echo "Selected";}?>>14</option>
            <option <?php if($qDay=="15"){echo "Selected";}?>>15</option>
            <option <?php if($qDay=="16"){echo "Selected";}?>>16</option>
            <option <?php if($qDay=="17"){echo "Selected";}?>>17</option>
            <option <?php if($qDay=="18"){echo "Selected";}?>>18</option>
            <option <?php if($qDay=="19"){echo "Selected";}?>>19</option>
            <option <?php if($qDay=="20"){echo "Selected";}?>>20</option>
            <option <?php if($qDay=="21"){echo "Selected";}?>>21</option>
            <option <?php if($qDay=="22"){echo "Selected";}?>>22</option>
            <option <?php if($qDay=="23"){echo "Selected";}?>>23</option>
            <option <?php if($qDay=="24"){echo "Selected";}?>>24</option>
            <option <?php if($qDay=="25"){echo "Selected";}?>>25</option>
            <option <?php if($qDay=="26"){echo "Selected";}?>>26</option>
            <option <?php if($qDay=="27"){echo "Selected";}?>>27</option>
            <option <?php if($qDay=="28"){echo "Selected";}?>>28</option>
            <option <?php if($qDay=="29"){echo "Selected";}?>>29</option>
            <option <?php if($qDay=="30"){echo "Selected";}?>>30</option>
            <option <?php if($qDay=="31"){echo "Selected";}?>>31</option>
            </select>
          </td>
        </tr>
        <tr>
         <td>Month</td>
         <td>
            <select name="qMonth">
            <option></option>
            <option value="1" <?php if($qMonth=="1"){echo "Selected";}?>>Jan</option>
            <option value="2" <?php if($qMonth=="2"){echo "Selected";}?>>Feb</option>
            <option value="3" <?php if($qMonth=="3"){echo "Selected";}?>>Mar</option>
            <option value="4" <?php if($qMonth=="4"){echo "Selected";}?>>Apr</option>
            <option value="5" <?php if($qMonth=="5"){echo "Selected";}?>>May</option>
            <option value="6" <?php if($qMonth=="6"){echo "Selected";}?>>Jun</option>
            <option value="7" <?php if($qMonth=="7"){echo "Selected";}?>>Jul</option>
            <option value="8" <?php if($qMonth=="8"){echo "Selected";}?>>Aug</option>
            <option value="9" <?php if($qMonth=="9"){echo "Selected";}?>>Sep</option>
            <option value="10" <?php if($qMonth=="10"){echo "Selected";}?>>Oct</option>
            <option value="11" <?php if($qMonth=="11"){echo "Selected";}?>>Nov</option>
            <option value="12" <?php if($qMonth=="12"){echo "Selected";}?>>Dec</option>
            </select>
          </td>
        </tr>
        <tr>
         <td>Year</td>
         <td>
            <select name="qYear">
            <option></option>
            <option <?php if($qYear=="2012"){echo "Selected";}?>>2012</option>
            <option <?php if($qYear=="2013"){echo "Selected";}?>>2013</option>
            <option <?php if($qYear=="2014"){echo "Selected";}?>>2014</option>
            <option <?php if($qYear=="2015"){echo "Selected";}?>>2015</option>
            <option <?php if($qYear=="2016"){echo "Selected";}?>>2016</option>
            </select>          
           </td>
        </tr>
      <tr><td>Status</td>
         <td><select name="qStatus">
              <option></option><?php 
      $sql = "SELECT Status FROM Sessions "
         ."GROUP BY Status "  
         ."ORDER BY Status ";
      try {
         $dbh = new PDO($dsn, $user, $pass);
      } catch (PDOException $e) {
         echo 'Connection failed: '.$e->getMessage();
      }
      $query = $dbh->prepare($sql);
      $query->execute();
      while($row = $query->fetch()){?>
               <option <?php
            if($qStatus == $row['Status']){?>Selected<?php
            }?>><?php echo $row['Status'];?></option><?php
        
      }?>
            </select>
         </td></tr>
      <tr><td>Coach</td>
         <td><select name="qCoachID">
               <option></option><?php
      $sql = "SELECT UserID, FirstName, LastName FROM Users "
         ."WHERE Status = 'Coach' OR Status = 'Admin' "
         ."ORDER BY LastName, FirstName, UserID ";
      $query = $dbh->prepare($sql);
      $query->execute();
      while($row = $query->fetch()){?>
               <option value="<?php echo $row['UserID'];?>" <?php
                  if($row['UserID'] == $qCoachID){?>Selected<?php
                  }?>><?php 
                  echo $row['FirstName'];?>&nbsp;<?php 
                  echo $row['LastName'];?>&nbsp;[<?php 
                  echo $row['UserID'];?>]</option><?php
      }?>
            </select>
          </td>
      </tr>
      <tr><td>Client</td>
               <td><select name="qClientID">
               <option></option><?php
      $sql = "SELECT UserID, FirstName, LastName FROM Users " 
         ."WHERE Status = 'Client' OR Status = 'Coach' OR Status = 'Admin' "
         ."ORDER BY LastName, FirstName, UserID ";
      $query = $dbh->prepare($sql);
      $query->execute();
      while($row = $query->fetch()){?>
               <option value="<?php echo $row['UserID'];?>" <?php
                  if($row['UserID'] == $qClientID){?>Selected<?php
                  }?>><?php echo $row['FirstName'];?>&nbsp;<?php 
                  echo $row['LastName'];?>&nbsp;[<?php 
                  echo $row['UserID'];?>]</option><?php
      }?>
            </select>
          </td>
      </tr>
      <tr><td colspan=2>&nbsp;</td></tr>
      <tr><td colspan=2 align="right">
         <input type="button" value="Add"             
            onclick="pageform.PageMode.value = 'Add'; 
               pageform.pSessionID.value = ''; pageform.submit();"/>
         <input type="reset" value="Reset" />
         <input type="button" value="Filter" 
            onclick="pageform.PageMode.value = ''; pageform.submit();" />
        </td>
      </tr>
      </table><?php
      $pCoachID = ""; 
      $pClientID = "";
      $pDOW = "";
      $pDay = "";
      $pMonth = "";
      $pStatus = "";
      $pSessionRate = "";
      $pFeesPaid = "";
      $pCoachPaid = "";
      if(!$pSessionID == "" and $PageMode == "Select"){ 
         $sql = "SELECT SessionID, "  
            ."HOUR(SessionTime) AS Hour, "  
            ."WEEKDAY(SessionTime) AS DOW, "  
            ."DAY(SessionTime) AS Day, "  
            ."MONTH(SessionTime) AS Month, "  
            ."YEAR(SessionTime) AS Year, "  
            ."Status, CoachID, ClientID, "  
            ."SessionRate, FeesPaid, CoachID "  
            ."FROM Sessions WHERE SessionID = ".$pSessionID." ";
         $query = $dbh->prepare($sql);
         $query->execute();
         if($row = $query->fetch()){
            $pCoachID = $row['CoachID']; 
            $pClientID = $row['ClientID']; 
            $pDOW = $row['DOW'];
            $pHour = $row['Hour']; 
            $pDay = $row['Day'];
            $pMonth = $row['Month']; 
            $pYear = $row['Year'];
            $pStatus = $row['Status']; 
            $pSessionRate = $row['SessionRate']; 
            $pFeesPaid = $row['FeesPaid'];
            $pCoachPaid = $row['CoachID'];          
         } else {
            $pSessionID = "";
         }
      } else {
         $pSessionID = "";
      }?>     
      <table style="<?php
      if($PageMode=="Add" or !$pSessionID == ""){
         echo "visibility:Visible;"; 
      } else {
         echo "visibility:Hidden;"; 
      }?>">
      <tr><td colspan=2><h3>Update Session Entry</h3></td></tr>
      <tr><td>Session ID</td>
         <td><input name="pSessionID" value="<?php echo $pSessionID;?>" size=10 readonly /></td>
      </tr>
        <tr>
         <td>Time</td>
         <td>
            <select name="pHour">
            <option></option>
            <option value="08" <?php if($qHour=="08"){echo "Selected";}?>>08:00</option>
            <option value="09" <?php if($qHour=="09"){echo "Selected";}?>>09:00</option>
            <option value="10" <?php if($qHour=="10"){echo "Selected";}?>>10:00</option>
            <option value="11" <?php if($qHour=="11"){echo "Selected";}?>>11:00</option>
            <option value="12" <?php if($qHour=="12"){echo "Selected";}?>>12:00</option>
            <option value="13" <?php if($qHour=="13"){echo "Selected";}?>>13:00</option>
            <option value="14" <?php if($qHour=="14"){echo "Selected";}?>>14:00</option>
            <option value="15" <?php if($qHour=="15"){echo "Selected";}?>>15:00</option>
            <option value="16" <?php if($qHour=="16"){echo "Selected";}?>>16:00</option>
            <option value="17" <?php if($qHour=="17"){echo "Selected";}?>>17:00</option>
            <option value="18" <?php if($qHour=="18"){echo "Selected";}?>>18:00</option>
            <option value="19" <?php if($qHour=="19"){echo "Selected";}?>>19:00</option>
            <option value="20" <?php if($qHour=="20"){echo "Selected";}?>>20:00</option>
            <option value="21" <?php if($qHour=="21"){echo "Selected";}?>>21:00</option>
            <option value="22" <?php if($qHour=="22"){echo "Selected";}?>>22:00</option>
            </select>
         </td>
      </tr>
        <tr>
         <td>Day</td>
         <td>
            <select name="pDOW" > 
            <option></option>
            <option value=1 <?php if($qDOW=="1"){echo "Selected";}?>>Sun</option>
            <option value=2 <?php if($qDOW=="2"){echo "Selected";}?>>Mon</option>
            <option value=3 <?php if($qDOW=="3"){echo "Selected";}?>>Tue</option>
            <option value=4 <?php if($qDOW=="4"){echo "Selected";}?>>Wed</option>
            <option value=5 <?php if($qDOW=="5"){echo "Selected";}?>>Thu</option>
            <option value=6 <?php if($qDOW=="6"){echo "Selected";}?>>Fri</option>
            <option value=7 <?php if($qDOW=="7"){echo "Selected";}?>>Sat</option>
            </select>
          </td>
        </tr>
      <tr>
         <td>Date</td>
         <td>
            <select name="pDay"> 
            <option></option>
            <option <?php if($qDay=="1"){echo "Selected";}?>>1</option>
            <option <?php if($qDay=="2"){echo "Selected";}?>>2</option>
            <option <?php if($qDay=="3"){echo "Selected";}?>>3</option>
            <option <?php if($qDay=="4"){echo "Selected";}?>>4</option>
            <option <?php if($qDay=="5"){echo "Selected";}?>>5</option>
            <option <?php if($qDay=="6"){echo "Selected";}?>>6</option>
            <option <?php if($qDay=="7"){echo "Selected";}?>>7</option>
            <option <?php if($qDay=="8"){echo "Selected";}?>>8</option>
            <option <?php if($qDay=="9"){echo "Selected";}?>>9</option>
            <option <?php if($qDay=="10"){echo "Selected";}?>>10</option>
            <option <?php if($qDay=="11"){echo "Selected";}?>>11</option>
            <option <?php if($qDay=="12"){echo "Selected";}?>>12</option>
            <option <?php if($qDay=="13"){echo "Selected";}?>>13</option>
            <option <?php if($qDay=="14"){echo "Selected";}?>>14</option>
            <option <?php if($qDay=="15"){echo "Selected";}?>>15</option>
            <option <?php if($qDay=="16"){echo "Selected";}?>>16</option>
            <option <?php if($qDay=="17"){echo "Selected";}?>>17</option>
            <option <?php if($qDay=="18"){echo "Selected";}?>>18</option>
            <option <?php if($qDay=="19"){echo "Selected";}?>>19</option>
            <option <?php if($qDay=="20"){echo "Selected";}?>>20</option>
            <option <?php if($qDay=="21"){echo "Selected";}?>>21</option>
            <option <?php if($qDay=="22"){echo "Selected";}?>>22</option>
            <option <?php if($qDay=="23"){echo "Selected";}?>>23</option>
            <option <?php if($qDay=="24"){echo "Selected";}?>>24</option>
            <option <?php if($qDay=="25"){echo "Selected";}?>>25</option>
            <option <?php if($qDay=="26"){echo "Selected";}?>>26</option>
            <option <?php if($qDay=="27"){echo "Selected";}?>>27</option>
            <option <?php if($qDay=="28"){echo "Selected";}?>>28</option>
            <option <?php if($qDay=="29"){echo "Selected";}?>>29</option>
            <option <?php if($qDay=="30"){echo "Selected";}?>>30</option>
            <option <?php if($qDay=="31"){echo "Selected";}?>>31</option>
            </select>
          </td>
        </tr>
        <tr>
         <td>Month</td>
         <td>
            <select name="pMonth">
            <option></option>
            <option value="1" <?php if($qMonth=="1"){echo "Selected";}?>>Jan</option>
            <option value="2" <?php if($qMonth=="2"){echo "Selected";}?>>Feb</option>
            <option value="3" <?php if($qMonth=="3"){echo "Selected";}?>>Mar</option>
            <option value="4" <?php if($qMonth=="4"){echo "Selected";}?>>Apr</option>
            <option value="5" <?php if($qMonth=="5"){echo "Selected";}?>>May</option>
            <option value="6" <?php if($qMonth=="6"){echo "Selected";}?>>Jun</option>
            <option value="7" <?php if($qMonth=="7"){echo "Selected";}?>>Jul</option>
            <option value="8" <?php if($qMonth=="8"){echo "Selected";}?>>Aug</option>
            <option value="9" <?php if($qMonth=="9"){echo "Selected";}?>>Sep</option>
            <option value="10" <?php if($qMonth=="10"){echo "Selected";}?>>Oct</option>
            <option value="11" <?php if($qMonth=="11"){echo "Selected";}?>>Nov</option>
            <option value="12" <?php if($qMonth=="12"){echo "Selected";}?>>Dec</option>
            </select>
          </td>
        </tr>
        <tr>
         <td>Year</td>
         <td>
            <select name="pYear">
            <option></option>
            <option <?php if($qYear=="2012"){echo "Selected";}?>>2012</option>
            <option <?php if($qYear=="2013"){echo "Selected";}?>>2013</option>
            <option <?php if($qYear=="2014"){echo "Selected";}?>>2014</option>
            <option <?php if($qYear=="2015"){echo "Selected";}?>>2015</option>
            <option <?php if($qYear=="2016"){echo "Selected";}?>>2016</option>
            </select>          
           </td>
        </tr>
      <tr><td>Status</td>
         <td><select name="pStatus">
              <option></option><?php 
      $sql = "SELECT Status FROM Sessions "
         ."GROUP BY Status "  
         ."ORDER BY Status ";
      try {
         $dbh = new PDO($dsn, $user, $pass);
      } catch (PDOException $e) {
         echo 'Connection failed: '.$e->getMessage();
      }
      $query = $dbh->prepare($sql);
      $query->execute();
      while($row = $query->fetch()){?>
               <option <?php
            if($qStatus == $row['Status']){?>Selected<?php
            }?>><?php echo $row['Status'];?></option><?php
        
      }?>
            </select>
         </td></tr>
      <tr><td>Coach</td>
         <td><select name="pCoachID">
               <option></option><?php
      $sql = "SELECT UserID, FirstName, LastName FROM Users "
         ."WHERE Status = 'Coach' OR Status = 'Admin' "
         ."ORDER BY LastName, FirstName, UserID ";
      $query = $dbh->prepare($sql);
      $query->execute();
      while($row = $query->fetch()){?>
               <option value="<?php echo $row['UserID'];?>" <?php
                  if($row['UserID'] == $qCoachID){?>Selected<?php
                  }?>><?php 
                  echo $row['FirstName'];?>&nbsp;<?php 
                  echo $row['LastName'];?>&nbsp;[<?php 
                  echo $row['UserID'];?>]</option><?php
      }?>
            </select>
          </td>
      </tr>
      <tr><td>Client</td>
               <td><select name="pClientID">
               <option></option><?php
      $sql = "SELECT UserID, FirstName, LastName FROM Users " 
         ."WHERE Status = 'Client' OR Status = 'Coach' OR Status = 'Admin' "
         ."ORDER BY LastName, FirstName, UserID ";
      $query = $dbh->prepare($sql);
      $query->execute();
      while($row = $query->fetch()){?>
               <option value="<?php echo $row['UserID'];?>" <?php
                  if($row['UserID'] == $qClientID){?>Selected<?php
                  }?>><?php echo $row['FirstName'];?>&nbsp;<?php 
                  echo $row['LastName'];?>&nbsp;[<?php 
                  echo $row['UserID'];?>]</option><?php
      }?>
            </select>
          </td>
      </tr>
      <tr><td>Session Rate</td><td><input name="pSessionRate" value="<?php echo $pSessionRate;?>" size=3  /></td></tr>
      <tr><td>FeesPaid</td><td><input name="pFeesPaid" value="<?php echo $pFeesPaid;?>" size=3 /></td></tr>
      <tr><td>CoachPaid</td><td><input name="pCoachPaid" value="<?php echo $pCoachPaid;?>" size=3 /></td></tr>
      <tr><td colspan=2 align="right"><?php
      if($pSessionID == ""){?>
         <input type="button" value="Cancel"             
            onclick="pageform.PageMode.value = ''; 
               pageform.submit();"/>
         <input type="button" value="Insert"             
            onclick="if(confirm('Insert this record?')){
               pageform.PageMode.value = 'Insert';
               pageform.submit();
            }"/><?php
      } else {?>
         <input type="button" value="Cancel"             
            onclick="pageform.PageMode.value = ''; 
               pageform.pSessionID.value = '';
               pageform.submit();"/>
         <input type="button" value="Delete" 
            onclick="if(pageform.pStatus.value=='Booked' 
                     || pageform.pStatus.value=='Booking'
                     || pageform.pStatus.value=='Complete') 
                        alert('A session that had been booked or complete cannot be deleted');
                     else
                        if(confirm('Delete this record?')){
                           pageform.PageMode.value = 'Delete';
                           pageform.submit();
                        }"/>
         <input type="button" value="Update"             
            onclick="if(confirm('Update this session?')){
               pageform.PageMode.value = 'Update';
               pageform.submit();
            }" /><?php
      }?>
        </td>
      </tr>
      </table>
   </td>
   <td valign="top"><br />
   <table class="report">
      <tr>
         <th style="cursor:hand;" onclick="pageform.ListOrder.value = 'S.SessionTime'; pageform.submit();">Session</th>
         <th style="cursor:hand;" onclick="pageform.ListOrder.value = 'S.Status'; pageform.submit();">Status</th>
         <th style="cursor:hand;" onclick="pageform.ListOrder.value = 'CO.LastName, CO.FirstName'; pageform.submit();">Coach</th>
         <th style="cursor:hand;" onclick="pageform.ListOrder.value = 'CL.LastName, CL.FirstName'; pageform.submit();">Client</th>
      </tr><?php 
    $sql = "DELETE FROM Sessions WHERE Status = 'Session' " 
      ."AND SessionTime < '".date('Y',strtotime($EarliestDate))."/"
      .date('m',strtotime($EarliestDate))."/".date('d',strtotime($EarliestDate))."' "; 
    $results = $dbh->query($sql);
    $sql = "SELECT S.SessionID, S.SessionTime, S.Status, S.CoachID, S.ClientID, "
      ."CL.FirstName AS ClientFirst, CL.LastName AS ClientLast, CL.Email AS ClientEmail, " 
      ."CO.FirstName AS CoachFirst, CO.LastName AS CoachLast, CO.Email AS CoachEmail " 
      ."FROM (Sessions S "
      ."LEFT JOIN Users CO ON S.CoachID = CO.UserID) " 
      ."LEFT JOIN Users CL ON S.ClientID = CL.UserID " 
      ."WHERE S.SessionID > 0 ";    
   if(!$qHour==""){ 
      $sql .= "AND HOUR(S.SessionTime)=".$qHour." ";
   }
   if(!$qDOW==""){ 
      $sql .= "AND WEEKDAY(S.SessionTime,1)=".$qDOW." ";
   }
   if(!$qDay==""){ 
      $sql .= "AND DAY(S.SessionTime)=".$qDay." ";
   }
   if(!$qMonth==""){ 
      $sql .= "AND MONTH(S.SessionTime)=".$qMonth." ";
   }
   if(!$qYear==""){ 
      $sql .= "AND YEAR(S.SessionTime)=".$qYear." ";
   }
   if(!$qStatus==""){ 
      $sql .= "AND S.Status='".$qStatus."' ";
   }
   if(!$qCoachID==""){ 
      $sql .= "AND S.CoachID=0".$qCoachID." ";
   }
   if(!$qClientID==""){ 
      $sql .= "AND S.ClientID=0".$qClientID." ";
   }
   $sql .= "ORDER BY ".$ListOrder;
   //echo $sql;
   $query = $dbh->prepare($sql);
   $query->execute();
   while($row = $query->fetch()){
      $ThisSession = $row['SessionTime'];
      $ThisHour = date('h',strtotime($ThisSession)); 
      $SessionDisplay = $ThisHour.":00 "
         .substr(date('l',strtotime($ThisSession)),0,3)." "
         .date('d',strtotime($ThisSession))." " 
         .date('M',strtotime($ThisSession))." "
         .date('Y',strtotime($ThisSession))." ";?>
   <tr class="<?php
      if(trim($row['SessionID']) == trim($pSessionID)){
         echo "Selected"; 
      } else {
         echo $row['Status'];
      }?>" 
      onclick="pageform.pSessionID.value='<?php echo $row['SessionID'];?>';
         pageform.PageMode.value='Select'; pageform.submit();">
      <td><?php echo $SessionDisplay;?></td>
      <td><?php echo $row['Status'];?></td>
      <td title="<?php 
         echo $row['CoachEmail'];?>"><?php
         echo $row['CoachFirst'];?>&nbsp;<?php 
         echo $row['CoachLast'];?></td>
      <td title="<?php
         echo $row['ClientEmail'];?>"><?php
         echo $row['ClientFirst'];?>&nbsp;<?php
         echo $row['ClientLast'];?></td>
   </tr><?php
   }
   unset($row);
   unset($query);
   unset($dbh);?>
   </table>
   </td>
   </tr>
   </table>
   </form>
</DIV> <!-- content -->
<?php include('../includes/footer.php'); ?><?php
}?>

<?php
include('../includes/functions.php');
if(!CheckStatus(array('Admin','Coach','Client'))){ 
   Header("Location: ../Public/Login.php");
}
include('../includes/header.php');
include('../clients/clientsmenu.php');
require("../config/dbconfig.php");
$dsn = "mysql:dbname=".DB_NAME.";host=".DB_HOST;
$user = DB_USER;
$pass = DB_PASS; 
try{
   $dbh = new PDO($dsn, $user, $pass);
} catch (PDOException $e) {
   echo 'Connection failed: '.$e->getMessage();
}
$PageMode = (isset($_REQUEST['PageMode'])?$_REQUEST['PageMode']:'');
$ThisYear = (isset($_REQUEST['Year'])?$_REQUEST['Year']:'');
$ThisMonth = (isset($_REQUEST['Month'])?$_REQUEST['Month']:'');
$ThisDay = (isset($_REQUEST['Day'])?$_REQUEST['Day']:'');
$ThisHour = (isset($_REQUEST['Hour'])?$_REQUEST['Hour']:'');
$ThisCoach = (isset($_REQUEST['CoachID'])?$_REQUEST['CoachID']:'');
$ThisClient = (isset($_REQUEST['UserID'])?$_REQUEST['UserID']:'');
$ListOrder = (isset($_REQUEST['ListOrder'])?$_REQUEST['ListOrder']:'UserID');
if($PageMode == "BookingSession"){
    if(BookingEmail($ThisClient, $ThisCoach, $ThisYear, $ThisMonth, $ThisDay, $ThisHour)){ 
        $sql = "UPDATE SESSIONS SET "
            ."CLIENTID = 0".$ThisClient.", " 
            ."SESSIONRATE = 55, " 
            ."FEESPAID = 0, "            
            ."STATUS = 'Booking' "
            ."WHERE SESSIONTIME = '".$ThisYear."-".$ThisMonth."-".$ThisDay 
            ." ".$ThisHour.":00:00' AND CoachID = 0".$ThisCoach."; ";
        
        $sql = "UPDATE Sessions SET "
            ."ClientID = 0".$ThisClient.", " 
            ."Status = 'Booking' " 
            ."WHERE SessionTime = '".$ThisYear."-".$ThisMonth."-".$ThisDay 
            ." ".$ThisHour.":00:00' AND CoachID = 0".$ThisCoach."; ";
      echo "<!-- ".$sql." -->";
        $query = $dbh->query($sql);
    } else {
        $PageMode = "Error";
    }
}
if($PageMode == "SessionBooked"){
    if(BookedEmail($ThisClient, $ThisCoach, $ThisYear, $ThisMonth, $ThisDay, $ThisHour)){
       $sql = "UPDATE Sessions SET " 
         ."ClientID = 0".$ThisClient.", " 
         ."SessionRate=53, FeesPaid=53, " 
         ."Status='Booked' " 
         ."WHERE SessionTime = '".$ThisYear."-".$ThisMonth."-".$ThisDay 
         ." ".$ThisHour.":00:00' AND CoachID = 0".$ThisCoach."; ";
       echo "<!-- ".$sql." -->";
        $query = $dbh->query($sql);
        // Also add code to upgrade visitor status to client if appropriate
    } else {
        $PageMode = "Error";
    }
}
if($PageMode == "PayPalCancelled"){
   $sql = "UPDATE Sessions SET " 
      ."SessionRate=55, FeesPaid=0, " 
      ."ClientID = 0, Status = 'Session' " 
      ."WHERE SessionTime = '".$ThisYear."-".$ThisMonth."-".$ThisDay 
      ." ".$ThisHour.":00:00' AND CoachID = 0".$ThisCoach." "
      ."AND ClientID=0".$ThisClient." ";
   echo "<!-- ".$sql." -->";
   $query = $dbh->query($sql);
}
if($PageMode == "CancelSession"){
   if(CancelEmail($ThisClient, $ThisCoach, $ThisYear, $ThisMonth, $ThisDay, $ThisHour  )){ 
      $sql = "UPDATE Sessions SET " 
         ."SessionRate=5, FeesPaid=5, " 
         ."Status = 'Cancelled' " 
         ."WHERE SessionTime = '".$ThisYear."-".$ThisMonth."-".$ThisDay 
         ." ".$ThisHour.":00:00' AND CoachID = 0".$ThisCoach." "
         ."AND ClientID=0".$ThisClient." ";
      echo "<!-- ".$sql." -->";
      $query = $dbh->query($sql);
    } else {
        $PageMode = "Error";
    }
}
if($PageMode == "RequestSession"){
    $sql = "INSERT INTO Session (CoachID, ClientID, Status, SesssionTime) " 
      ."VALUES (0,0".$ThisClient.",'Request'," 
      ."'".$ThisYear."-".$ThisMonth."-".$ThisDay." ".$ThisHour.":00:00') ";
      echo "<!-- ".$sql." -->";
      $dbh->query($sql);
}
if($PageMode == "UnrequestSession"){
    $sql = "DELETE FROM SESSIONS "
        ."WHERE STATUS = 'Request' " 
        ."AND ClientID=0".$ThisClient." "
        ."AND SessionTime = '".$ThisYear."-".$ThisMonth."-".$ThisDay 
        ." ".$ThisHour.":00:00' "; //AND CoachID = 0".$ThisCoach." ";
      echo "<!-- ".$sql." -->";
      $dbh->query($sql);
}?>
<DIV class="content">
   <p><a href="../public/home.asp">Home</a> > 
   <a href="../clients/clientsPage.asp">Clients</a>> 
   <b>Book a Session</b></p><?php
$EarliestDate = time();
if($ThisYear == ""){ 
   $ThisYear = date('Y',$EarliestDate);
}
if($ThisMonth == ""){ 
   $ThisMonth = date('m',$EarliestDate);
}
if($ThisDay == ""){ 
    $ThisDay = date('d',$EarliestDate);
}
$FirstDayThisMonth = strtotime(date('Y-m-01 H',$EarliestDate));
$sql = "SELECT SessionTime, CoachID, ClientID, Status FROM Sessions "
        ."WHERE SessionTime >= '".date('Y-m-d 00:00:00',$FirstDayThisMonth)."'"
        ." AND (Status = 'Session' " 
        ."    OR (ClientID = 0".$_SESSION['UserID']." " 
        ."       AND (Status = 'Booked' OR Status = 'Booking' " 
        ."          OR Status = 'Request' OR Status = 'Cancelled' "
        ."       ) " 
        ."    ) " 
        ." ) " 
        ."ORDER BY SessionTime, CoachID, Status "; 
//SessionTime > NOW() '".$ThisYear."-".$ThisMonth."-".$ThisDay."' "
//        ."AND 
//echo $sql;

$query = $dbh->prepare($sql);
$query->execute();
$row = $query->fetch();?>
    <table>
    <tr>
    <td valign="top"><?php
if($PageMode == "SessionBooked"){?>
    <H4>PayPal booking payment confirmed</H4>
    <p>Your PayPal payment has been received and you will shortly receiving 
    be an electronic receipt by email. You may also log into your account at 
    www.paypal.com/uk to view details of this transaction.</P>
    <p>You will also receive an email confirming the coaching session
   that you booked, together with details of your coach and and how
   to contact them.</p><?php
}elseif($PageMode == "PayPalCancelled"){?>
      <H4>PayPal booking payment cancelled</H4><p>
      Your PayPal payment for your coaching session has been cancelled 
      and therefore it is not been possible to confirm your booking. <?php
   if(strtotime($ThisYear.'-'.$ThisMonth.'-'.$ThisDay) > time() + (7*24*60*60)){?>
      As this session is more than a week away you still have the option to put a cheque 
      in the post.</P><?php
   }else{?>
      <p>As this session is less than a week away there is insufficient time for you to post 
      a cheque and for it to be cleared before the session. Therefore, you have the choice
      of either selecting a later date for your session, or making your payment using PayPal.
      </P><?php
   }?>
      <p><strong>Paying on-line using PayPal</strong><br />
      This is by far quickest and most secure way to pay for your coaching session.
      Because it also reduces our administration costs, you will only be charged 
      <b>£ 53</b>.
      <div align="center">
      <!-- New Button -->
      <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="LY6YW7JHC4W2L">
<input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
</form>
<!--
        // New Button
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="hosted_button_id" value="6FR7MW97GS4FN">
        <input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online!">
        <img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
        </form>-->

      </div>
      Click the button above to pay using most credit/debit cards, 
      alternatively you use or create your own PayPal account.</p><?php
   if(strtotime($ThisYear.'-'.$ThisMonth.'-'.$ThisDay) > time() + (7*24*60*60)){?>
      <p><strong>Paying by Cheque</strong><br/>
      Cheques should be made payable to <strong>LIFESPEAK Ltd</strong> and posted, 
      together with you contact details, to the address below.</p>
      <p style="text-align: center">
         <em>Session Bookings<br />
            LIFESPEAK Ltd<br />
            2 Kirby Corner Road<br />
            COVENTRY &nbsp; CV4 8GD</em>
      </p><?php
   }      
} elseif($PageMode == "BookingSession"){?>
      <H4>Booking a coaching session on-line</H4>
      <p>You have selected the following coaching session:</p>
      <p><b><?php echo $ThisHour;?>:00 on 
         <?php echo date('l',strtotime($ThisYear.'-'.$ThisMonth.'-'.$ThisDay))?>&nbsp;
         <?php echo $ThisDay;?>&nbsp;
         <?php echo date('N',strtotime($ThisYear.'-'.$ThisMonth.'-'.$ThisDay))?></b></p>
      <p>The standard rate for a single session is just <b>£ 55</b> and in order 
      to proceed with this booking it is necessary to pay in advance. <?php
      if(strtotime($ThisYear.'-'.$ThisMonth.'-'.$ThisDay) > time() + (7*24*60*60)){?>
      As this session is more than a week away you have two options for payment.</P><?php
      } else {?>
      <p>As this session is less than a week away there is insufficient time for you to post 
      a cheque and for it to be cleared before the session. Therefore, you have the choice
      of either selecting a later date for your session, or making your payment using PayPal.
      </P><?php
      }?>
      <p><strong>Paying on-line using PayPal</strong><br />
      This is by far quickest and most secure way to pay for your coaching session.
      Because it also reduces our administration costs, you will only be charged 
      <b>£ 53</b>.     
      <div align="center"><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but6.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
<img alt="" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
</form></div>
      Click the button above to pay using most credit/debit cards, 
      alternatively you use or create your own PayPal account.</p><?php
      if(strtotime($ThisYear.'-'.$ThisMonth.'-'.$ThisDay) > time() + (7*24*60*60)){?>
      <p><strong>Paying by Cheque</strong><br/>
      Cheques should be made payable to <strong>LIFESPEAK Ltd</strong> and posted, 
      together with you contact details, to the address below.</p>
      <p style="text-align: center">
         <em>Session Bookings<br />
            LIFESPEAK Ltd<br />
            2 Kirby Corner Road<br />
            COVENTRY &nbsp; CV4 8GD</em>
      </p><?php
      }
} elseif($PageMode == "Error"){?>
      <H4>Sorry, a booking error has occured</H4>
      <p>It appears that an error occured while processing you booking.</p>
      <p>Please check the status of the session that you were booking in 
      the calendar on this page. If you have just made a payment using PayPal
      you should have an email confirming payment and another confirming your
      booking.</P> 
      <p>In the event that you do not get either of these emails in next hour
      then email coaching@lifespeak.co.uk directly, and we will endeavour to 
      confirm your payment and your session booking.</p><?php
} else {?>
      <H4>Booking a coaching session on-line</H4>
      <p>The calendar show sessions dates and times offered by coaches, 
      that have not yet been booked by other clients, and are therefore
      still available for you to book.</p>
      <p>By clicking on an available session you may   
      book and pay for that session on-line.</p>
      <p>Once the booking is complete, 
      both you and the relevant coach will immediately be sent a 
      confirmation by email. You will also receive details of how to 
      contact the coach you have selected.</p>
      <p>The calendar also helps you keep track of sessions that you have booked.</p><?php
      
      if($_SESSION['UserStatus'] == 'Client' or $_SESSION['UserStatus'] == 'Admin'){?>
         <H4>Requesting a session not currently available</H4>
         <p>If you are already a client, then an additional feature allows you to 
         put in a request for a session date/time more convenient to yourself. This will 
         then be emailed to all coaches so that any one that can fit that session in has 
         the option to make it available. We cannot promise to match all such requests 
         but if coach makes a session available that you have requested, then you will be 
         notified immediately by email.</p><?php
      }
}?>
    </td>
    <td width=5>&nbsp;</td>
    <td valign="top" width="20%"><br /><?php
    // For 3 months
$FirstDayThisMonth = strtotime(date('m/01/Y'));  
for($m=1;$m<=3;$m++){
   $LastDayThisMonth = strtotime(date('Y-m-t',$FirstDayThisMonth));
   $FirstDayNextMonth = strtotime(date('Y-m-01',$LastDayThisMonth) + (24*60*60));
   $DayNum = 0;?>
    <table class="calendar">
    <tr><th colspan=7 align="center"><H4 style="margin-bottom:0;">
    <?php echo date('M Y',$FirstDayThisMonth);?>
    </H4></th></tr>
    <tr><th>S</th><th>M</th><th>T</th><th>W</th><th>T</th><th>F</th><th>S</th></tr>
    <tr><?php
   while($DayNum < (int)date('N',$FirstDayThisMonth)){?>
        <td class="Disabled">&nbsp;</td><?php
        $DayNum++;
   }
    $TempDate = $FirstDayThisMonth;
   while($TempDate < $EarliestDate){
      if($DayNum >6){echo "</tr><tr>"; $DayNum=0;}?>
        <td class="Disabled"><?php echo date('d',$TempDate);?></td><?php
        $TempDate +=(24*60*60);
      $DayNum ++;
   } 
   while(($row = $query->fetch()) and date('Y-m-d H',strtotime($row['SessionTime'])) < date('Y-m-d H',$TempDate)){
       // Cycle through dates too early
    }
    while(date('Y-m-d H',$TempDate) < date('Y-m-d H',$LastDayThisMonth)){
      if($ThisMonth == "" and $ThisDay == ""){ 
         $ThisMonth = date('m',$TempDate);
         $ThisDay = date('d',$TempDate);
         $ThisYear = date('Y',$TempDate);
      }
      if($DayNum > 6){echo "</tr><tr>"; $DayNum = 0;}
      $ThisClass = "Unavailable";
      while($row and date('Y-m-d H',strtotime($row['SessionTime']))
            < date('Y-m-d H',$TempDate)){       
         $row = $query->fetch();
      }
      while($row and date('Y-m-d',strtotime($row['SessionTime']))== date('Y-m-d',$TempDate)){  
         if($row['Status'] == "Request" and !($ThisClass == "Session" or $ThisClass == "Booked")){
            $ThisClass = "Request";
         }elseif($row['Status'] == "Session" and !($ThisClass == "Booked")){
            $ThisClass = "Session";
         }elseif($row['Status'] == "Booked") {
            $ThisClass = "Booked";
         }
         $row = $query->fetch();
      }
      if( date('m',$TempDate) == $ThisMonth and date('d',$TempDate) == $ThisDay){ 
         $ThisClass = "Selected";
      }?>
        <td align="right" class="<?php echo $ThisClass;?>" 
            onclick="pickDate(this,'<?php 
            echo date('m',$TempDate);?>','<?php 
            echo date('d',$TempDate);?>','<?php 
            echo date('Y',$TempDate);?>');">
            <?php echo date('d',$TempDate);?></td><?php
       $TempDate+=(24*60*60);
       $DayNum++;
    } 
    // Finish off the month
    while($DayNum <= 6){?>
        <td class="Disabled">&nbsp;</td><?php
        $DayNum++;
    }?>
    </tr>
    </table><br/><?php
      $FirstDayThisMonth = $LastDayThisMonth + (24*60*60);
      $LastDayThisMonth = strtotime(date('m/t/Y',$FirstDayThisMonth));
    }?>
    </td>
    <td width=5>&nbsp;</td>
    <td valign="top" width="20%"><br />
    <table class="calendar">
    <tr>
      <th colspan=2 width=150>
      <H4 style="margin-bottom:0;">
         <?php echo date('l',strtotime($ThisMonth."/".$ThisDay."/".$ThisYear));?><br/>
         <?php echo date('d M',strtotime($ThisMonth."/".$ThisDay."/".$ThisYear));?> 
      </H4>
      </th>
    </tr><?php
 unset($query);
 $sql = "SELECT SessionTime, CoachID, ClientID, Status FROM Sessions "
        ."WHERE SessionTime >= '".date('Y-m-d',time())."'"
        ." AND (Status = 'Session' " 
        ."    OR (ClientID = 0".$_SESSION['UserID']." " 
        ."       AND (Status = 'Booked' OR Status = 'Booking' " 
        ."          OR Status = 'Request' OR Status = 'Cancelled' "
        ."       ) " 
        ."    ) " 
        ." ) " 
        ."ORDER BY SessionTime, CoachID, Status "; 
//echo $sql;
$query = $dbh->prepare($sql);
$query->execute();
$row = $query->fetch();
if(1){
   while(($row) and !(
      date('m',strtotime($row['SessionTime'])) == $ThisMonth 
      and date('d',strtotime($row['SessionTime'])) == $ThisDay)){
      $row = $query->fetch();
   }
    $SessionTime = 8;
    while($SessionTime < 23){
        $ThisClass = "Unavailable";
        $ThisStatus = "";
        $ThisCoach = 0;
        $Coaches = 0; 
        if($row){  
            $ThisSession = (int)strtotime($row['SessionTime']);
            if(date('m',$ThisSession) == $ThisMonth 
               and date('d',$ThisSession) == $ThisDay
               and date('H',$ThisSession) == $SessionTime){ 
                $ThisClass = "Session";
                $ThisCoach = $row['CoachID'];
                $ThisStatus = $row['Status'];
                while($row and date('m',strtotime($row['SessionTime'])) == $ThisMonth 
                  and date('d',strtotime($row['SessionTime'])) == $ThisDay
                  and date('H',strtotime($row['SessionTime'])) == $SessionTime){
                    if($row['Status'] == "Request"){
                        $ThisClass = $row['Status'];
                        $ThisStatus = $row['Status'];
                    }
                    if($row['Status'] == "Booked" 
                            or $row['Status'] == "Booking" 
                            or $row['Status'] == "Cancelled"){
                        $ThisClass = $row['Status'];
                        $ThisCoach = $row['CoachID'];
                        $ThisStatus = $row['Status'];
                    }
                    $Coaches++;
                    $row = $query->fetch();
                    if($row){
                        $ThisSession = $row['SessionTime'];
                    }
               }
           }
        }?>
    <tr class="<?php echo $ThisClass;  ?>" align="right" width="10%" <?php 
      if($_SESSION['UserStatus'] == "Client" or $_SESSION['UserStatus'] == "Coach" or $_SESSION['UserStatus'] == "Admin"){?> 
         onclick="if(this.className == 'Unavailable' || this.className == 'Request'){
                     markSession(this,'<?php echo $SessionTime;?>');
                   } else {
                   pickSession(this,'<?php echo $SessionTime;?>',<?php echo $ThisCoach;?>);}" <?php
      }else{
         if($ThisClass == "Session"){
            if($Registered){?>
               onclick="pickSession(this,'<?php echo $SessionTime;?>',<?php echo $ThisCoach;?>);"<?php
            }else{?>
               onclick="register();"<?php
            }
         }
      }?>>
        <td align="right" width="10%"><?php echo $SessionTime;?>:00</td>
        <td align="left" width="90%">&nbsp;</td>      
     </tr><?php
        $SessionTime++;
    }?>
    </table><br />
    <div style="width:100%;height:100%;text-align:center;">
    <table style="padding:10px;">
    <tr><td class="Disabled"><b>Key :</b></td>
         <td class="Selected" title="Your current selection">Selected</td></tr>
    <tr><td>&nbsp;</td></td><td class="Unavailable" title="You are not listed as available">Unavailable</td></tr><?php
    if($_SESSION['UserStatus'] == "Client" or $_SESSION['UserStatus'] == "Admin"){?>
    <tr><td>&nbsp;</td><td class="Request" title="There is a request for a session here">Request</td></tr><?php
    }?>
    <tr><td>&nbsp;</td><td class="Session" title="You are listed as available here">Available</td></tr><?php
    if($_SESSION['UserStatus'] == "Client" or $_SESSION['UserStatus'] == "Admin"){?>
    <tr><td>&nbsp;</td><td class="Cancelled" title="You are listed as available here">Cancelled</td></tr><?php
    }?>
    <tr><td>&nbsp;</td><td class="Booking" title="You have a provisional booking here - payment due">Booking</td></tr>
    <tr><td>&nbsp;</td><td class="Booked" title="You currently have a session booked here">Booked</td></tr>
    </table></div>
    </td>  
    </tr>
    </table><?php
    unset($row);
    unset($query);
    unset($dbh);
}    ?>
</DIV>
<form name="pageform" method="post" action="" target="">
   <input name="UserID" type="hidden" value="" />
   <input name="SessionDateTime" type="hidden" value="<?php
   echo (isset($_REQUEST['SessionDateTime'])?$_REQUEST['SessionDateTime']:'');?>" />
   <input name="Status" type="hidden" value="" />
   <input name="ListOrder" type="hidden" value="<?php echo $ListOrder;?>" />
   <input name="PageMode" type="hidden" value="<?php echo $PageMode;?>" />
   <input name="CoachID" type="hidden" value="" />
   <input name="UserID" type="hidden" value="<?php echo $_SESSION['UserID'];?>" />
   <input name="Month" type="hidden" value="<?php echo $ThisMonth;?>" />
   <input name="Day" type="hidden" value="<?php echo $ThisDay;?>" />
   <input name="Year" type="hidden" value="<?php echo $ThisYear;?>" />
   <input name="Hour" type="hidden" value="<?php echo $ThisHour?>" />
</form><?php
$PageMode = "";?>
<iframe name="SaveFreeSessions" width="100%" height="0"> </iframe>
<Script>
function pickDate(obj,sMon,sDay,sYear) {
    document.pageform.Month.value = sMon;
    document.pageform.Day.value = sDay;
    document.pageform.Year.value = sYear;
    obj.className = 'Selected';
    document.pageform.action = '';
    document.pageform.target = ''
    document.pageform.submit();
} 
function pickSession(obj,sHour,nCoachID) {
    document.pageform.Hour.value = sHour;
    document.pageform.CoachID.value = nCoachID;
    if(obj.className == 'Booked' || obj.className == 'Booking'){
        if(confirm('Do you want to cancel this session booking?')){         
            cancelSession();
            obj.className = 'Session';
        }
    } else {
        if(confirm('Do you want to book this session?')){
            obj.className = 'Selected';
            bookSession();
        }
    }
}

function bookSession() {
    document.pageform.SessionDateTime.value = '\''
        + document.pageform.Month.value + '/' 
        + document.pageform.Day.value + '/'
        + document.pageform.Year.value + ' '
        + document.pageform.Hour.value + ':00' 
        + '\''; 
    document.pageform.PageMode.value = 'BookingSession';
    document.pageform.submit();
}
function cancelSession() {
    document.pageform.SessionDateTime.value = '\''
        + document.pageform.Month.value + '/' 
        + document.pageform.Day.value + '/'
        + document.pageform.Year.value + ' '
        + document.pageform.Hour.value + ':00' 
        + '\''; 
    document.pageform.PageMode.value = 'CancelSession';
    document.pageform.submit();
}
function markSession(obj,sHour) {
    document.pageform.Hour.value = sHour;
    document.pageform.CoachID.value = '';
    if(obj.className == 'Unavailable'){
        if(confirm('Do you want to request a coaching session at this time?')){         
            requestSession();
            obj.className = 'Request';
        }
    } else {
        if(confirm('Do you want to cancel this request for a session?')){
            unrequestSession();
            obj.className = 'Unavailable';
        }
    }
}
function requestSession() {
    document.pageform.SessionDateTime.value = '\''
        + document.pageform.Month.value + '/' 
        + document.pageform.Day.value + '/'
        + document.pageform.Year.value + ' '
        + document.pageform.Hour.value + ':00' 
        + '\''; 
    document.pageform.PageMode.value = 'RequestSession';
    document.pageform.submit();
}
function unrequestSession() {
    document.pageform.SessionDateTime.value = '\''
        + document.pageform.Month.value + '/' 
        + document.pageform.Day.value + '/'
        + document.pageform.Year.value + ' '
        + document.pageform.Hour.value + ':00' 
        + '\''; 
    document.pageform.PageMode.value = 'UnrequestSession';
    document.pageform.submit();
}
function register() {
   if(confirm('You need to be a registered user\n'
         + 'before you can book on-line.\n'
         + 'Would you like to register now?')){
      document.location.href = '../public/login.php?PageMode=NewUser';
   }
}
</Script><?php  
unset($query);
unset($dbh);

function GetClient($ClientID){
   global $ClientFirstName,$ClientLastName,$ClientEmail,$ClientPhone;
   $dsn = "mysql:dbname=".DB_NAME.";host=".DB_HOST;
   $user = DB_USER;
   $pass = DB_PASS; 
   try{
      $dbh = new PDO($dsn, $user, $pass);
   } catch (PDOException $e) {
      echo 'Connection failed: '.$e->getMessage();
   }
   $sql = "SELECT * FROM USERS WHERE USERID = 0".$ClientID;
   $query = $dbh->prepare($sql);
   $query->execute();       
   $row = $query->fetch();
   if($row){
      $ClientFirstName = $row['FirstName'];
      $ClientLastName = $row['LastName'];
      $ClientEmail = $row['Email'];
      $ClientPhone = $row['Phone'];
   }
   unset($row);
   unset($query);
   unset($dbh);
}
function GetCoach($CoachID){
   global $CoachFirstName,$CoachLastName,$CoachEmail,$CoachPhone;
   $dsn = "mysql:dbname=".DB_NAME.";host=".DB_HOST;
   $user = DB_USER;
   $pass = DB_PASS; 
   try{
      $dbh = new PDO($dsn, $user, $pass);
   } catch (PDOException $e) {
      echo 'Connection failed: '.$e->getMessage();
   }
   $sql = "SELECT * FROM USERS WHERE USERID = 0".$CoachID;
   $query = $dbh->prepare($sql);
   $query->execute();       
   $row = $query->fetch();
   if($row){
      $CoachFirstName = $row['FirstName'];
      $CoachLastName = $row['LastName'];
      $CoachEmail = $row['Email'];
      $CoachPhone = $row['Phone'];
   }
   unset($row);
   unset($query);
   unset($dbh);
}

function BookingEmail($ClientID, $CoachID, $Year, $Month, $Day, $Hour){
   global $ClientFirstName,$ClientLastName,$ClientEmail,$ClientPhone;
   global $CoachFirstName,$CoachLastName,$CoachEmail,$CoachPhone;
   // Get Client Details
   GetClient($ClientID);
   // Get Coach Details
   GetCoach($CoachID); 
   // Send Coach Email
   $to = $CoachEmail;
   $subject = "Coaching Session - ".$Hour.":00 on "
      .$Day."/".$Month."/".$Year." - Booking Notice."; 
   $emailtext = "Dear ".$CoachFirstName.",<br/>\n<br/>\n" 
      ."The following coaching session that you made available at "
      ."www.lifespeak.co.uk is in the process of being booked by a client;<br/>\n<br/>\n"
      ."Session:  ".$Hour.":00 on ".$Day."/".$Month."/".$Year."<br/>\n<br/>\n"
      //."Name:     ".$ClientFirstName." ".$ClientLastName."<br/>\n" 
      //."Email:    ".$ClientEmail."<br/>\n"
      //."Phone:    ".$ClientPhone."<br/>\n<br/>\n"
      ."The fee for this session has not yet been paid, therefore "
      ."the client has instructions to pay by cheque "  
      ."in order to confirm the booking. You will "
      ."receive the client's contact details, together with "
      ."a booking confirmation when payment has been confirmed.<br/>\n<br/>\n"
      ."Kind regards,<br/>\n<br/>\n"
      ."LIFESPEAK Coaching";
   $headers   = array();
   $headers[] = "MIME-Version: 1.0";
   $headers[] = "Content-type: text/HTML; charset=iso-8859-1";
   $headers[] = "From: LIFESPEAK Coaching <coaching@lifespeak.co.uk>";
   $headers[] = "Bcc: LIFESPEAK Coaching <coaching@lifespeak.co.uk>";
   $headers[] = "Reply-To: LIFESPEAK Coaching <coaching@lifespeak.co.uk>";
   $headers[] = "Subject: {$subject}";
   $headers[] = "X-Mailer: PHP/".phpversion();
   $return = FALSE;
   try {
      mail($to, $subject, $emailtext, implode("\r\n", $headers));
      $return = TRUE;
   } catch (PDOException $e) {
      echo 'Failed to send email: '.$e->getMessage();
   }
   // Send Client Email
   $to = $ClientEmail;
   $subject = "Coaching Session - ".$Hour.":00 on "
      .$Day."/".$Month."/".$Year." - Booking Confimation";
   $emailtext = "Dear ".$ClientFirstName.",<br/>\n<br/>\n" 
      ."Thank you for selecting the following coaching session on line at www.lifespeak.co.uk;<br/>\n<br/>\n"
      ."Session: ".$Hour.":00 on ".$Day."/".$Month."/".$Year."<br/>\n<br/>\n" 
      ."Although you have marked this session for a booking, we are afraid that can only be confirmed " 
      ."once payment has been received. At that time you will be provided with details of when and how " 
      ."to contact the coach who has offered the above session.<br/>\n<br/>\n"
     // ."Name:     ".$CoachFirstName." ".$CoachLastName."<br/>\n" 
     // ."Email:    ".$CoachEmail."<br/>\n"
     // ."Phone:    ".$CoachPhone."<br/>\n<br/>\n"
      ."If you have since paid for you session on-line, this email will be followed by a confirmation. " 
      ."The option to pay on-line remains open to you at any time simply by returning to the following " 
      ."page http:\\www.lifespeak.co.uk\clients\bookasession.php <br/>\n<br/>\n"  
      ."OR you can go <a href=\"https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6AVGAG3G8Z9H2\">PAY HERE</a><br/>\n<br/>\n"
      ."Cheques should be made payable to 'LIFESPEAK Ltd' and posted, " 
      ."together with you contact details, to the following address; <br/>\n"
      ."Session Bookings <br/>\n"  
      ."LIFESPEAK Ltd <br/>\n"
      ."2 Kirby Corner Road <br/>\n"  
      ."COVENTRY <br/>\n"
      ."CV4 8GD <br/>\n<br/>\n"  
      ."We look forward to hearing from you again soon. <br/>\n<br/>\n"
      ."Kind regards,<br/>\n<br/>\n"
      ."LIFESPEAK Coaching";
   $headers   = array();
   $headers[] = "MIME-Version: 1.0";
   $headers[] = "Content-type: text/HTML; charset=iso-8859-1";
   $headers[] = "From: LIFESPEAK Coaching <coaching@lifespeak.co.uk>";
   $headers[] = "Bcc: LIFESPEAK Coaching <coaching@lifespeak.co.uk>";
   $headers[] = "Reply-To: LIFESPEAK Coaching <coaching@lifespeak.co.uk>";
   $headers[] = "Subject: {$subject}";
   $headers[] = "X-Mailer: PHP/".phpversion();
   $return = FALSE;
   try {
      mail($to, $subject, $emailtext, implode("\r\n", $headers));
      $return = TRUE;
   } catch (PDOException $e) {
      echo 'Failed to send email: '.$e->getMessage();
   }
   return $return;
}
function BookedEmail($ClientID, $CoachID, $Year, $Month, $Day, $Hour){
   global $ClientFirstName,$ClientLastName,$ClientEmail,$ClientPhone;
   global $CoachFirstName,$CoachLastName,$CoachEmail,$CoachPhone;
   // Get Client Details
   GetClient($ClientID);
   // Get Coach Details
   GetCoach($CoachID); 
   // Send Coach Email
   $to = $CoachEmail;
   $subject = "Coaching Session - ".$Hour.":00 on "
      .$Day."/".$Month."/".$Year." - Booking Notice."; 
   $emailtext = "Dear ".$CoachFirstName.",<br/>\n<br/>\n"  
      ."A coaching session that you made available at www.lifespeak.co.uk has been booked by a client;<br/>\n<br/>\n"  
      ."Name:     ".$ClientFirstName." ".$ClientLastName."<br/>\n" 
      ."Email:    ".$ClientEmail."<br/>\n"
      ."Phone:    ".$ClientPhone."<br/>\n"."<br/>\n" 
      ."This session has been paid for online and therefore the client has instructions to phone you at;<br/>\n<br/>\n"
      ."Session:  ".$ThisHour.":00 on ".$ThisDay."/".$ThisMonth."/".$ThisYear."<br/>\n<br/>\n"
      ."If you need to re-schedule this appointment for any reason please contact the client directly. " 
      ."Please also bear in mind, that if you move the appointment to a date and time already offered "  
      ."to clients on-line, then you will need to remove that session in order to avoid a double booking.<br/>\n<br/>\n" 
      ."Once the coaching session is complete, please let LIFESPEAK know by replying to this email, so that " 
      ."you can be reimbursed for your coaching. <br/>\n<br/>\n"
      ."Best regards,<br/>\n<br/>\n"
      ."LIFESPEAK Coaching";
   $headers   = array();
   $headers[] = "MIME-Version: 1.0";
   $headers[] = "Content-type: text/HTML; charset=iso-8859-1";
   $headers[] = "From: LIFESPEAK Coaching <coaching@lifespeak.co.uk>";
   $headers[] = "Bcc: LIFESPEAK Coaching <coaching@lifespeak.co.uk>";
   $headers[] = "Reply-To: LIFESPEAK Coaching <coaching@lifespeak.co.uk>";
   $headers[] = "Subject: {$subject}";
   $headers[] = "X-Mailer: PHP/".phpversion();
   $return = FALSE;
   try {
      mail($to, $subject, $emailtext, implode("\r\n", $headers));
      $return = TRUE;
   } catch (PDOException $e) {
      echo 'Failed to send email: '.$e->getMessage();
   }
   // Send Client Email
   $to = $ClientEmail;
   $subject = "Coaching Session - ".$Hour.":00 on "
      .$Day."/".$Month."/".$Year." - Booking Confimation";
   $emailtext = "Dear ".$ClientFirstName.",<br/>\n<br/>\n"  
      ."Thank you for booking a coaching session on line at www.lifespeak.co.uk.<br/>\n<br/>\n"
      ."This email is confirmation that your booking has been forwarded to the "
      ."following life coach;<br/>\n<br/>\n" 
      ."Name:     ".$CoachFirstName." ".$CoachLastName."<br/>\n"
      ."Email:    ".$CoachEmail."<br/>\n"
      ."Phone:    ".$CoachPhone."<br/>\n<br/>\n" 
      ."As explained on-line, it is usual practise for client to phone the coach at "
      ."the appointed time. <br/>\n<br/>\n" 
      .$CoachFirstName." will be waiting for your call at " 
      .$ThisHour.":00 on ".$ThisDay."/".$ThisMonth."/".$ThisYear."<br/>\n<br/>\n"
      ."If you need to re-schedule this appointment for any reason please contact "
      ."the coach directly, giving them at least 24 hours notice.<br/>\n<br/>\n"
      ."As this session has now been booked with the coach they are no longer "
      ."able to offer this time to other clients and therefore failure " 
      ."to make this scheduled appointment may result in the forfeit of your "
      ."booking fee. <br/>\n<br/>\n"
      ."Once the coaching session is complete, please let us know by replying "
      ."to this email. We would also appreciate any feedback on "
      ."how your session went and how you found the online booking "
      ."facility. <br/>\n<br/>\n"
      ."We trust that your coaching session will help you reach your goals and "
      ."that you will be be booking another session with us soon.<br/>\n<br/>\n"
      ."Kind regards,<br/>\n<br/>\n"
      ."LIFESPEAK Coaching";
   $headers   = array();
   $headers[] = "MIME-Version: 1.0";
   $headers[] = "Content-type: text/HTML; charset=iso-8859-1";
   $headers[] = "From: LIFESPEAK Coaching <coaching@lifespeak.co.uk>";
   $headers[] = "Bcc: LIFESPEAK Coaching <coaching@lifespeak.co.uk>";
   $headers[] = "Reply-To: LIFESPEAK Coaching <coaching@lifespeak.co.uk>";
   $headers[] = "Subject: {$subject}";
   $headers[] = "X-Mailer: PHP/".phpversion();
   $return = FALSE;
   try {
      mail($to, $subject, $emailtext, implode("\r\n", $headers));
      $return = TRUE;
   } catch (PDOException $e) {
      echo 'Failed to send email: '.$e->getMessage();
   }
   return $return;
}
function CancelEmail($ClientID, $CoachID, $Year, $Month, $Day, $Hour){
   global $ClientFirstName,$ClientLastName,$ClientEmail,$ClientPhone;
   global $CoachFirstName,$CoachLastName,$CoachEmail,$CoachPhone;
   // Get Client Details
   GetClient($ClientID);
   // Get Coach Details
   GetCoach($CoachID); 
   // Send Coach Email
   $to = $CoachEmail;
   $subject = "Coaching Session - ".$Hour.":00 on "
      .$Day."/".$Month."/".$Year." - Cancellation Notice."; 
   $emailtext = "Dear ".$CoachFirstName.", <br/>\n<br/>/n"
      ."The coaching session at: " 
      .$ThisHour .":00 on ".$ThisDay ."/".$ThisMonth ."/".$ThisYear."<br/>\n<br/>/n" 
      ."that ".$ClientFirstName." ".$ClientLastName." <br/>\n<br/>\n" 
      ."which had been booked with you at www.lifespeak.co.uk, " 
      ."has now been cancelled by the client.<br/>\n<br/>\n"
      ."The session that you orginally offered is therefore now available to be "
      ."booked by another potential client.<br/>\n<br/>\n" 
      ."Kind regards,<br/>\n<br/>\n"
      ."LIFESPEAK Coaching";
   $headers   = array();
   $headers[] = "MIME-Version: 1.0";
   $headers[] = "Content-type: text/HTML; charset=iso-8859-1";
   $headers[] = "From: LIFESPEAK Coaching <coaching@lifespeak.co.uk>";
   $headers[] = "Bcc: LIFESPEAK Coaching <coaching@lifespeak.co.uk>";
   $headers[] = "Reply-To: LIFESPEAK Coaching <coaching@lifespeak.co.uk>";
   $headers[] = "Subject: {$subject}";
   $headers[] = "X-Mailer: PHP/".phpversion();
   $return = FALSE;
   try {
      mail($to, $subject, $emailtext, implode("\r\n", $headers));
      $return = TRUE;
   } catch (PDOException $e) {
      echo 'Failed to send email: '.$e->getMessage();
   }
   return $return;
}


/*<!-- Old PayPal Button --
      <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
      <input type="hidden" name="cmd" value="_s-xclick">
      <input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but6.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
      <img alt="" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1">
      <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----">
      </form>-->
*/
?>

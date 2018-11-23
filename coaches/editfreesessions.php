<?php
include('../includes/functions.php');
if(!CheckStatus(array('Admin','Coach'))){ 
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
$PageMode = (isset($_REQUEST['PageMode'])?$_REQUEST['PageMode']:'List');
$ListOrder = (isset($_REQUEST['ListOrder'])?$_REQUEST['ListOrder']:'UserID');
// ---
//$EarliestDate = date('d/m/Y',time()+(1*24*60*60));
$ThisYear = (isset($_REQUEST['Year'])?$_REQUEST['Year']:date('Y',time()));
$ThisMonth = (isset($_REQUEST['Month'])?$_REQUEST['Month']:date('m',time()));
$ThisDay = (isset($_REQUEST['Day'])?$_REQUEST['Day']:date('d',time()));
//$FirstDayThisMonth = FirstDayThisMonth();
$earliestdate = strtotime(date('Y:m:d',time()+(1*24*60*60)));
$sql = "SELECT S.SessionTime, S.ClientID, S.Status, U.FirstName, U.LastName "
   ."FROM Sessions S LEFT OUTER JOIN Users U ON S.ClientID = U.UserID "
   ."WHERE S.SessionTime >= '".date('Y-m-d',$earliestdate)."' "
   ."AND (S.CoachID = 0".$_SESSION['UserID']." OR S.Status = 'Request') "
   ."ORDER BY S.SessionTime ";
$query = $dbh->prepare($sql);
$query->execute();?>
<DIV class="content">
   <table top="0"><tr><td  valign="top" ><p><a href="../public/home.php">Home</a> | 
   <a href="../coaches/coachespage.php">Coaches</a> | 
   <b>My Coaching Sessions</b></p>
   <form name="pageform" method="post" action="savefreesessions.php" target="savefreesessions">
   <input name="UserID" type="hidden" value="" />
   <input name="SessionDateTime" type="hidden" value="<?php //echo $_REQUEST['SessionDateTime'];?>"/>
   <input name="Month" type="hidden" value="<?php echo $ThisMonth;?>"/>
   <input name="Day" type="hidden" value="<?php echo $ThisDay;?>"/>
   <input name="Year" type="hidden" value="<?php echo $ThisYear;?>"/>
   <input name="Hour" type="hidden" value="<?php //echo $ThisHour;?>"/>
   <input name="Status" type="hidden" value="" />
   <input name="ListOrder" type="hidden" value="<?php echo $ListOrder;?>"/>
   <input name="PageMode" type="hidden" value="<?php echo $PageMode;?>"/>
   <input name="UserID" type="hidden" value="<?php echo $_SESSION['UserID'];?>"/>
   <h4>Making sessions available to clients</h4>
    <p>Clients may only book coaching sessions at dates and times that have
    been offered by coaches. Therefore, as a coach, you need to declare when 
    you are available and ensure that you keep this system up to date with any 
    other appointment diaries you maintain.</p>
    <p>For convenience of both clients and coaches all sessions are deemed to start 
    on the hour and last 30 - 45 minutes.</P>
    <p>To declare yourself available to coach a particular session, simply select the 
    date and click the time. Marking yourself unavailable, (perhaps because one of your
    own coaches booked a session by phone), again simply select the date and click the time.</p> 
    <p>If a client books a session that you have made available, then you will be notified
    immediately by email, and you will not them be able to mark yourself unavailable</P>
    <p>It is also possible for clients to request a session at a time when no coach has 
    declared themselves available. These requests are marked in yellow, and notified to all 
    coaches when the request is made. To accept a requested session, simply make yourself available at that time and the client will automatically be notified that they 
    may now book that session.</p>
    </td>
    <td width=10>&nbsp;</td>
    <td valign="top" width="20%"><br/><?php
// $earliestdate is the earliest date a session may be offered or booked days from today.
$earliestdate = strtotime("tomorrow");
// $firstday in the current month 
$firstdate = strtotime(date('m/01/Y'));
// $lastday in the current month
$lastdate = strtotime(date('m/t/Y'));    
// For 3 months
for($m=1;$m<=3;$m++){
   // Calculate first active celldatetime
//   $cellday = strtotime(date('Y:m:d',time()+(2*24*60*60)));
//   $cellday = strtotime(date('Y:m:d',time()+(2*24*60*60)));
   
   // $cellday is the day of week pointer in the calendar
   //$nextWeek = time() + (7 * 24 * 60 * 60);
   // 7 days; 24 hours; 60 mins; 60secs
   //echo 'Now:       '. date('Y-m-d') ."\n";
   //echo 'Next Week: '. date('Y-m-d', $nextWeek) ."\n";
   // or using strtotime():
   //echo 'Next Week: '. date('Y-m-d', strtotime('+1 week')) ."\n";  
   $cellday=0;
   $celldate=$firstdate;
 //  $LastDayThisMonth = date("t/m/Y", $earliestdate);
//   $LastDayThisMonth = DateSerial(Year(FirstDayThisMonth),Month(FirstDayThisMonth)+1, 0)
   ?>
    <table class="Calendar">
    <tr><th colspan=7 align="center"><h4 style="margin-bottom:0;">
    <?php echo date('M Y',$firstdate);?>
    </h4></th></tr>
    <tr><th>S</th><th>M</th><th>T</th><th>W</th><th>T</th><th>F</th><th>S</th></tr>
    <tr><?php
    // Days before month begins
   while($cellday < (int)date('N',$celldate)){?>
      <td class="Disabled">&nbsp;</td><?php
      $cellday++;
   }
   // Dates before earliest date available
   while($celldate < $earliestdate){
       if($cellday>6){echo "</tr><tr>"; $cellday=0;}?>
       <td class="Disabled"><?php echo date('d',$celldate);?></td><?php
       $celldate+=(24*60*60); 
       $cellday++;
   }
   // Skip past dates in query that precede the active calendar
   while(($row = $query->fetch()) and date('Y-m-d h',strtotime($row['SessionTime'])) < date('Y-m-d h',$celldate)){
   }
   // Process remaining days of the month
   while(date('Y-m-d h',$celldate) < date('Y-m-d h',$lastdate)){
      if($ThisMonth=="" and $ThisDay == ""){ 
         $ThisMonth = date('m',$celldate);
         $ThisDay = date('d',$celldate);
         $ThisYear = date('Y',$celldate);
      }
      if($cellday>6){echo "</tr><tr>"; $cellday=0;}
      $ThisClass = "Unavailable";
      // Ensure that there are no preceeding entires before current cell date
      while($row and date('Y-m-d h',strtotime($row['SessionTime']))
            < date('Y-m-d h',$celldate)){       
         $row = $query->fetch();
      }
      while($row and date('Y-m-d h',strtotime($row['SessionTime']))== date('Y-m-d h',$celldate)){   
         if( $row['Status']=='Request' and !($ThisClass=='Session') and !($ThisClass=='Booked')){
            $ThisClass = 'Request';
         } elseif( $row['Status'] == 'Session' and $ThisClass <> 'Booked'){
            $ThisClass = 'Session';
         } elseif( $row['Status'] == 'Booked') {
            $ThisClass = 'Booked';
         }
         $row = $query->fetch();
      }
      if(date('m',$celldate) == $ThisMonth and date('d',$celldate) == $ThisDay){ 
         $ThisClass = 'Selected';
      }?>
        <td align="right" class="<?php echo $ThisClass;?>" 
            onclick="pickDate(this,'<?php 
            echo date('m',$celldate);?>','<?php 
            echo date('d',$celldate);?>','<?php 
            echo date('Y',$celldate);?>');">
            <?php echo date('d',$celldate);?></td><?php
        $celldate+=(24*60*60);
        $cellday++;
    } 
    // Finish off the month
    while($cellday<=6){?>
        <td class="Disabled">&nbsp;</td><?php
        $cellday++;
    }?>
    </tr>
    </table><br/><?php
      $firstdate = $lastdate + (24*60*60);
      $lastdate = strtotime(date('m/t/Y',$firstdate));
} ?>
    </td>
    <td width=5>&nbsp;</td>
    <td valign="top" width="20%"><br />
    <table class="calendar">
    <tr>
      <th colspan=2 width=150>
      <h4 style="margin-bottom:0;">
         <?php echo date('l',strtotime($ThisMonth."/".$ThisDay."/".$ThisYear));?><br/>
         <?php echo date('d M',strtotime($ThisMonth."/".$ThisDay."/".$ThisYear));?>         
      </h4>
      </th>
    </tr><?php
unset($query);

$sql = "SELECT S.SessionTime, S.ClientID, S.Status, FirstName, U.LastName "
   ."FROM Sessions S LEFT OUTER JOIN Users U ON S.ClientID = U.UserID "
   ."WHERE Month(S.SessionTime)= ".$ThisMonth." "
   ."AND DAY(S.SessionTime)= ".$ThisDay." "
   ."AND (S.CoachID = 0".$_SESSION['UserID']." OR S.Status = 'Request') "
   ."ORDER BY S.SessionTime ";


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
 //       $ThisStatus = "";
        $ThisClient = "";
        if($row){  
         $ThisSession = strtotime($row['SessionTime']);
         if(date('m',$ThisSession) == $ThisMonth
            and date('d',$ThisSession) == $ThisDay
            and date('h',$ThisSession) == $SessionTime){ 
            $ThisClass = $row['Status'];
            $ThisClient = $row['FirstName']." ".$row['LastName'];
            $row = $query->fetch();
        }
    }?>
    <tr onclick="if(this.className == 'Booked') 
            alert('You have a client booking at this time');
        else
            pickHour(this,'<?php echo$SessionTime;?>');" class="<?php echo $ThisClass;?>" <?php
            if($ThisClass == "Booked"){?> title="This session has already been booked"<?php
            }else{?> title='Click to offer or cancel a coaching session'<?php
            }?>>
        <td align="right" width="10%" ><?php echo $SessionTime;?>:00</td>
        <td align="left" width="90%" ><?php echo $ThisClient;?></td>
    </tr><?php
        $SessionTime++;
    }?>
    </table><br />
    <div style="width:100%;height:100%;text-align:center;">
    <table style="padding:10;">
    <tr><td class="Disabled"><b>Key :</b></td>
      <td class="Selected" title="Your current selection">Selected</td>  </tr>
    <tr><td>&nbsp;</td><td class="Unavailable" title="You are not listed as available">Unavailable</td>  </tr>
    <tr><td>&nbsp;</td><td class="Request" title="There is a request for a session here">Request</td>  </tr>
    <tr><td>&nbsp;</td><td class="Session" title="You are listed as available here">Available</td>  </tr>
    <tr><td>&nbsp;</td><td class="Cancelled" title="This session was cancelled by the client ">Cancelled</td>  </tr>
    <tr><td>&nbsp;</td><td class="Booking" title="This session is currently being booked">Booking</td>  </tr>
    <tr><td>&nbsp;</td><td class="Booked" title="You currently have a session booked here">Booked</td>  </tr>
    </table></div>
    </td>
    </tr>
    </table><?php
    unset($query);?>
    </form>
<iframe name="SaveFreeSessions" width="100%" height="0"> </iframe>
</DIV> <!-- content -->
<?php include('../includes/footer.php'); ?><?php
}?>
<Script>
function pickDate(obj,sMon,sDay,sYear) {
    pageform.Month.value = sMon;
    pageform.Day.value = sDay;
    pageform.Year.value = sYear;
    obj.className = 'Selected';
    pageform.action = '';
    pageform.target = '';
    //alert (pageform.Month.value + pageform.Day.value);
    pageform.submit();
} 
function pickHour(obj,sHour) {
    pageform.Hour.value = sHour;
    //alert (pageform.Month.value + pageform.Day.value);
    if(obj.className == 'Session'){
        obj.className = 'Unavailable';
        delSession();
    } else {
        obj.className = 'Session'
        addSession();
    }
}
function addSession() {
    pageform.SessionDateTime.value = '#'
        + pageform.Month.value + '/' 
        + pageform.Day.value + '/'
        + pageform.Year.value + ' '
        + pageform.Hour.value + ':00' 
        + '#'; 
    pageform.PageMode.value = 'AddSession';
    pageform.submit();
}
function delSession() {
    pageform.SessionDateTime.value = '#'
        + pageform.Month.value + '/' 
        + pageform.Day.value + '/'
        + pageform.Year.value + ' '
        + pageform.Hour.value + ':00' 
        + '#'; 
    pageform.PageMode.value = 'DelSession';
    pageform.submit();
}
</Script>
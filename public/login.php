<?php  
// Everytime a user returns to the login page they should be logged out.
session_unset();
include('../includes/functions.php');
unset($_SESSION['UserStatus']); 
require("../config/dbconfig.php");
$dsn = "mysql:dbname=".DB_NAME.";host=".DB_HOST;
$user = DB_USER;
$pass = DB_PASS;
$PageMode = "";
$UserEmail = "";
$Password = ""; 
$ActivationCode = "";
$NextPage = "";
if(isset($_REQUEST['PageMode'])){
    $PageMode = $_REQUEST['PageMode'];
}
if(isset($_REQUEST['UserEmail'])){
    $UserEmail = $_REQUEST['UserEmail'];
}
if(isset($_REQUEST['Password'])){
    $Password = $_REQUEST['Password'];
}
if(isset($_REQUEST['AC'])){
    $ActivationCode = $_REQUEST['AC'];
}
if(!$ActivationCode==""){
    $UserID = $ActivationCode - 132;
    if($UserID > 0){
        $sql = "SELECT Password FROM Users "
        ."WHERE Email = '".strtolower($UserEmail)."' " 
        ."AND UserID = ".$UserID." ";
        try{
            $dbh = new PDO($dsn, $user, $pass);
        } catch (PDOException $e) {
            echo 'Connection failed: '.$e->getMessage();
        }
        $results = $dbh->query($sql);
        $LoginMessage = "Sorry - Registration has encountered a problem";
        foreach($results AS $row){
            $Password = $row["Password"];
            $sql = "UPDATE Users SET Status = 'Visitor' "  
                ."WHERE UserID = ".$UserID." " 
                ."AND Email = '".strtolower($UserEmail)."' ";
            $dbh->query($sql);
            $LoginMessage = "Your login has been activated";
            $PageMode = "Login";
        }
        unset($results);
        unset($dbh); // Release database handle
    } else {
       $LoginMessage = "Sorry - These is a problem with your registration"; 
    }
}
if($PageMode == 'Login' Or $PageMode == 'Logout' Or $PageMode == ''){
   $PageMode = 'Login';
   try {
      $dbh = new PDO($dsn, $user, $pass);
   } catch (PDOException $e) {
      echo 'Connection failed: '.$e->getMessage();
   }
   $sql = "SELECT UserID, FirstName, ClientID, Status, Password, Reminder, "
        ."MD5('".$Password."') AS Entered FROM Users " 
        ."WHERE Email = '".strtolower($UserEmail)."'";
   $query = $dbh->prepare($sql);
   $query->execute();
   if(($row = $query->fetch()) AND isset($Password)){
      if($row["Entered"] == $row["Password"]){
         $_SESSION['UserID'] = $row["UserID"];
         $_SESSION['ClientID'] = $row["ClientID"];
         $_SESSION['FirstName'] = $row["FirstName"];
         $_SESSION['UserStatus'] = $row["Status"];
         if(isset($_SESSION['UserID'])){ 
            if($NextPage == "") { 
               if($_SESSION['UserStatus'] == "Client"){
                  $NextPage = "../clients/clientspage.php";
               } elseif($_SESSION['UserStatus'] == "Coach"){
                  $NextPage = "../coaches/coachespage.php";
               } elseif($_SESSION['UserStatus'] == "Admin"){
                  $NextPage = "../admin/adminpage.php";
               }else {
                  $NextPage = "../visitors/visitorspage.php";
               }
            }       
            $sql = "UPDATE Users SET LastVisited = '".date('Y-m-d H:i:s')."' " 
               ."WHERE Email = '".strtolower($UserEmail)."'";
            $dbh->query($sql);
            if(isset($NextPage)){
               Header("Location: $NextPage");
            }
         }
      } else {
         $LoginMessage = "Password Incorrect";
         $Reminder = $row["Reminder"];
         if(isset($Reminder)) {
            $PageMode = 'Login';
         }
      }
   } else {
      $LoginMessage = ""; //"Email address not registered";
   }
   unset($results);
   unset($dbh);
}
if($PageMode == 'Register'){ 
   if(!isset($_REQUEST["FirstName"]) 
      OR !isset($_REQUEST["LastName"]) 
      OR !isset($_REQUEST["UserEmail"])
      OR !isset($_REQUEST["Password"])
      OR !isset($_REQUEST["Confirm"])
      OR !isset($_REQUEST["Reminder"])){
      $PageMode = 'Register';
      $LoginMessage = "Please complete all fields to register.";  
   } elseif(strpos($_REQUEST["UserEmail"],"@") == 0 
      OR strpos($_REQUEST["UserEmail"],".") == 0 
      OR strlen(trim($_REQUEST["UserEmail"])) < 10){
      $LoginMessage = "Your email address does not appear to be valid";    
   } elseif($_REQUEST["Password"] != $_REQUEST["Confirm"]){ 
      $LoginMessage = "The passwords that you enter differ, please try again.";
   } else {
    try {
         $dbh = new PDO($dsn, $user, $pass);
      } catch (PDOException $e) {
         echo 'Connection failed: '.$e->getMessage();
      }
      $sql = "SELECT UserID, FirstName, Password, ClientID, Status, Reminder "
         ."FROM Users WHERE Email = '".strtolower($UserEmail)."'";
//echo "Checking for UserEmail $UserEmail ...";  
      $query = $dbh->prepare($sql);
      $query->execute();
      $row = $query->fetch();
      if($row){
         $PageMode = "Login";
         $LoginMessage = "User already registered - Please Login";
         if(strtoupper($row["Password"]) == strtoupper($_REQUEST["Password"])){ 
            $UserID = $row["UserID"];
            $ClientID = $row["ClientID"];
            $FirstName = $row["FirstName"];
            $UserStatus = $row["Status"];
         } else {
            $Reminder = $row["Reminder"];
            $LoginMessage = "User already registered - Please Login";
         }
      } else {
         $sql = "INSERT INTO Users "  
            ."   (FirstName,LastName,Email,Phone, Password,Reminder,Status) "
            ."VALUES "
            ."   ('".$_REQUEST['FirstName']."', "
            ."'".$_REQUEST['LastName']."', "
            ."'".strtolower($_REQUEST['UserEmail'])."', "
            ."'".$_REQUEST['UserPhone']."', "
            ."MD5('".$_REQUEST['Password']."'), "
            ."'".$_REQUEST['Reminder']."', "
            ."'Register') ";       
         $dbh->query($sql);
         $sql = "SELECT UserID FROM Users "
            ."WHERE Email = '".strtolower($UserEmail)."'"; 
         $query = $dbh->prepare($sql);
         $query->execute();
         $row = $query->fetch();
         if($row){
            $ActivationCode = "0".((string)($row["UserID"]+132));
         }
         if(!SendEmail($ActivationCode)){
            $LoginMessage = "ERROR sending registration email";
         } else {
            $PageMode = 'Registered';
         }
      }
      unset($results);
      unset($query);
      unset($dbh);
   }
}
if($PageMode == 'Forgotten'){ 
   $PageMode = 'Login';
  if(!isset($UserEmail)){ 
      $LoginMessage = "Please enter your user email address";
   } elseif(strpos($UserEmail,"@") == 0 
      OR strpos($UserEmail,".") == 0
      OR strlen(trim($UserEmail)) < 10){ 
      $LoginMessage = "Please enter your user email address";  
   }else{
      try {
         $dbh = new PDO($dsn, $user, $pass);
      } catch (PDOException $e) {
         echo 'Connection failed: '.$e->getMessage();
      }
      $sql = "SELECT UserID, ClientID, FirstName, Phone, Status, Password, Reminder "
         ."FROM Users WHERE Email = '".strtolower($UserEmail)."'";
      $query = $dbh->prepare($sql);
      $query->execute();
      if($row = $query->fetch()){
         $UserID = $row["UserID"];
         $ClientID = $row["ClientID"];
         $FirstName = $row["FirstName"];
         $UserPhone = $row["Phone"];
         $UserStatus = $row["Status"];
         $Password = $row["Password"];
         $Reminder = $row["Reminder"];
         if(!EmailReminder()){ 
            $LoginMessage = "ERROR sending reminder email";
         }else{
            $PageMode = 'Reminded';
         }
      }else{
         $LoginMessage = "ERROR - Email address not registered";
         $LoginMessage = "";
      }
      unset($results);
      unset($dbh);
   }
}
if($PageMode == ''){
   $PageMode = 'Login';
}?>
<?php include('../includes/header.php'); ?>
<?php include('../includes/leftmenu.php'); ?>
<DIV class="content">
<form name="Login2" action="login.php" method="post">
   <input name="PageMode" type="Hidden" value="<?php echo $PageMode;?>" />
<?php if($PageMode == 'Login'){ ?>
<H3>Login</H3>
   <div align=center>
   <p>Although some of ares of this web-site are available all visitors, 
   the majority of special features and resources are available only to 
   individuals who register with LIFESPEAK Coaching. <b>Registration is FREE</b> 
   and once confirmed by email, you will automatically gain access 
   to the Visitors area.</p>
   <p>Your registration will automatically upgraded to Client if you purchase
   a coaching session on line. Access to the Coaches and Admin areas of the 
   site are restricted to those who fulfill those roles for LIFESPEAK Coaching.</p>
   <table class="loginbox" cellpadding=5>
      <tr><td colspan=3>Registered Users Login</td>
      </tr><tr>  
         <td align="left">Email&nbsp;Address</td>
         <td align="left" colspan=2>
            <input name="UserEmail" type=text size=30 
            value="<?php echo $UserEmail; ?>"/></td>
      </tr><tr>  
         <td align="left">Password</td>
         <td align="left"><input name="Password" type=Password size=20/></td>
         <td align="left"><input name="Submit" type=Submit value="Login"/></td>
      </tr>
<?php if(isset($Reminder)) { ?>
      <tr>  
         <td align="left">Reminder</td>
         <td align="left" colspan=2><?php echo $Reminder; ?></td>
      </tr>
<?php }
if(isset($LoginMessage)) {?>
      <tr><td colspan=3 class="highlight"><?php echo $LoginMessage; ?></td></tr>
<?php }?>
      <tr>  
         <td align="left"><input name="NewUser" type="button" value="New User" 
                  onclick="document.Login2.PageMode.value = 'NewUser'; 
                           document.Login2.submit();"/>
         </td>
         <td align="left" colspan=2>
            <!--<input name="Forgotten" type="button" value="Forgotten Password" 
            onclick="if(Login2.UserEmail.value == '')
                  alert('Please enter your email address.\n' 
                     + 'This is the only address to which\n'
                     + 'your password will be sent.');
               else {
                  document.Login2.PageMode.value = 'Forgotten'; 
                  document.Login2.submit();
               }"/>-->
          </td>
      </tr>
   </table>
   <p>Existing users may login above. If you would like to register as a visitor
   please click the 'New User' link above.</p>
  <!-- <p>If you believe that you may have registered in the past but have forgotten your password,
   then simply enter your email address and click 'Forgotten Password' and we will send you a copy
   of the registration details provided for that address.</p>-->
   </div><?php 
}
if($PageMode == 'NewUser'){
   echo "<H3>New User - Registration</H3>\n"
      ."<div align=center>\n"
      ."<table class=\"loginbox\" cellpadding=5>\n"
      ."<tr><th colspan=3><h4>Please enter your details</h4></th></tr>\n"
      ."<tr><td colspan=3></td></tr>\n";
   if(isset($LoginMessage)){
      echo "<tr><td colspan=3><b>$LoginMessage</b></td></tr>";
   }?>
     <tr>  
         <td>First&nbsp;Name</td>
         <td><input name="FirstName" type=text size=30 value="<?php
         echo (isset($_REQUEST['FirstName'])?$_REQUEST['FirstName']:'');?>"/></td>
      </tr>
     <tr>  
         <td>Last&nbsp;Name</td>
         <td><input name="LastName" type=text size=30 value="<?php
         echo (isset($_REQUEST['LastName'])?$_REQUEST['LastName']:'');?>"/></td>
      </tr>
     <tr>  
         <td>Email&nbsp;Address</td>
         <td><input name="UserEmail" type=text size=30 value="<?php
         echo (isset($_REQUEST['UserEmail'])?$_REQUEST['UserEmail']:'');?>"/></td>
      </tr>
     <tr>  
         <td>Phone&nbsp;Number</td>
         <td><input name="UserPhone" type=text size=30 value="<?php
         echo (isset($_REQUEST['UserPhone'])?$_REQUEST['UserPhone']:'');?>"/></td>
      </tr>
      <tr>  
         <td>New&nbsp;Password</td>
         <td><input name="Password" type=password size=30 /></td>
      </tr>
      <tr>  
         <td>Confirm&nbsp;Password</td>
         <td><input name="Confirm" type=password size=30 /></td>
      </tr>
      <tr>  
         <td>Password&nbsp;Reminder</td>
         <td><input name="Reminder" type=text size=30 value="<?php
         echo (isset($_REQUEST['Reminder'])?$_REQUEST['Reminder']:'');?>"/></td>
      </tr>
      <tr>  
         <td colspan=3 align=right>
            <input name="Submit" type=button value="Register"
               onclick="document.Login2.PageMode.value = 'Register'; document.Login2.submit();"/>
         </td>
      </tr>
   </table>
   </div><?php
}elseif($PageMode == 'Registered') {?>
<H3>New User - Registration</H3>
   <div align=center>
   <table class="loginbox" cellpadding=5>
      <tr><td>Registration Accepted</td></tr>
      <tr><td>
         <p>You will now be sent an email to validate the address you supplied. 
            This email will also contain a reminder of your password.</p>
         <p>When you receive this email, click the link provided, that will 
            return you to this site and allow you to login.</p>
      </td></tr>
   </table>
   </div><?php
}elseif($PageMode == 'Reminded'){?>
<H3>Password Reminder</H3>
   <div class="loginbox">
      <h4>Reminder Sent</h4>
      <p>You have just been sent an email reminding you of the login details 
         you supplied when you registered.</p>
      <p>When you receive this email, click the enclosed web link and you will 
         be returned to this site and allowed to login.</p>
      <p>If you have changed your email address then, you will need to 
         register again as there is no way to send your account details
         to an email address other that the one registered.</p>
   </div><?php
}?>
</form>
</DIV> <!-- content -->
<?php include('../includes/footer.php'); ?>
<?php
function SendEmail($AC){
   $to = $_REQUEST['UserEmail'];
   $subject = "Your registration at lifespeak.co.uk"; 
   $emailtext = "Dear ".$_REQUEST['FirstName'].",<br/><br/>\n\n" 
      ."<b>Thank you for registering on-line with LIFESPEAK Coaching.</b><br/><br/>\n\n"
      ."The details you supplied where;<br/><br/>\n\n"
      ."Name:     ".$_REQUEST['FirstName']." ".$_REQUEST['LastName']."<br/>\n"
      ."Email:    ".$_REQUEST['UserEmail']."<br/>\n"
      ."Phone:    ".$_REQUEST['UserPhone']."<br/>\n"
      ."Password: ".$_REQUEST['Password']."<br/>\n"
      ."Reminder: ".$_REQUEST['Reminder']."<br/><br/>\n\n"
      ."Please keep this email safe for your future record.<br/><br/>\n\n"
      ."In order to activate your registration please click on the link below and login.\n\n"
      ."http://www.lifespeak.co.uk/public/Login.php?AC="
      .$AC."&UserEmail=".$_REQUEST['UserEmail']."<br/><br/>\n\n" 
      ."If you have any difficulty with the login process please email "
      ."coaching@lifespeak.co.uk <br/><br/>\n\n"
      ."New resources are being added to the site all the time and you can now " 
      ."booking a coaching sessions on-line.<br/><br/>\n\n"
      ."Best regards,<br/><br/>\n\n" 
      ."Andrew <br/>\n"
      ."LIFESPEAK Coaching";
   $headers   = array();
   $headers[] = "MIME-Version: 1.0";
   $headers[] = "Content-type: text/HTML; charset=iso-8859-1";
   $headers[] = "From: LIFESPEAK Coaching <coaching@lifespeak.co.uk>";
   $headers[] = "Bcc: Director <aps@lifespeak.co.uk>";
   $headers[] = "Reply-To: Life Coach <coaching@lifespeak.co.uk>";
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
function EmailReminder(){
   /*
   Dim objMail 
   Set objMail = Server.CreateObject("CDO.Message") 
   Set objConfig = Server.CreateObject("CDO.Configuration") 
   'Configuration: 
   objConfig.Fields(cdoSendUsingMethod) = cdoSendUsingPort
   objConfig.Fields(cdoSMTPServer)="auth.smtp.1and1.co.uk" 
   objConfig.Fields(cdoSMTPServerPort)=25 
   objConfig.Fields(cdoSMTPAuthenticate)=cdoBasic 
   objConfig.Fields(cdoSendUserName) = "m37098265-1"
   objConfig.Fields(cdoSendPassword) = "shaddai"

   'Update configuration 
   objConfig.Fields.Update 
   Set objMail.Configuration = objConfig 

   objMail.From ="coaching@lifespeak.co.uk" 
   objMail.To = Request("UserEmail")
   objMail.Subject = "Login details at lifespeak.co.uk" 
   CRLF = chr(13) & chr(10)
   EmailMessage = "Dear " & FirstName & ", " & CRLF & CRLF _ 
      & "Here is a reminder of your registration details on the web-site of LIFESPEAK Ltd." & CRLF & CRLF _ 
      & "The details you supplied where; " & CRLF & CRLF _
      & "Name:     " & FirstName & " " & Request("LastName") & CRLF _ 
      & "Email:    " & UserEmail & CRLF _ 
      & "Phone:    " & UserPhone & CRLF _ 
      & "Password: " & Password & CRLF _ 
      & "Reminder: " & Reminder & CRLF & CRLF _ 
      & "Please keep this email safe for future record." & CRLF & CRLF _ 
      & "In order to login directly click on the link below and login." & CRLF & CRLF _ 
      & "http://www.lifespeak.co.uk/public/Login.asp?Password=" & Password & "&UserEmail=" & Request("UserEmail") & CRLF & CRLF _ 
      & "If you have any difficulty with the login process please email coaching@lifespeak.co.uk." & CRLF & CRLF _ 
      & "New resources are being added to the site all the time " _ 
      & "and we hope that you will soon be booking a coaching session. " & CRLF & CRLF _ 
      & "Best regards, " & CRLF & CRLF _ 
      & "LIFESPEAK Coaching"
   objMail.TextBody = EmailMessage
   objMail.Send 
   If Err.Number = 0 Then
      EmailReminder = True
   Else
      Response.Write("Error sending mail. Code: " & Err.Number)
      EmailReminder = False
   Err.Clear
   End If
   Set objMail=Nothing 
   Set objConfig=Nothing 
    
    
    */
   return TRUE;
}

?>
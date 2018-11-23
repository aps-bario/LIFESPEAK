<?php
include('../includes/functions.php');
if(!CheckStatus(array('Admin'))){ 
   Header("Location: ../Public/Login.php");
} 
require("../config/dbconfig.php");
$dsn = "mysql:dbname=".DB_NAME.";host=".DB_HOST;
$user = DB_USER;
$pass = DB_PASS;
if(isset($_REQUEST['PageMode'])){
   $PageMode = $_REQUEST['PageMode'];
} else {
   $PageMode = "List";
}
if($PageMode == "Update"){
   if(isset($_REQUEST['UserID']) AND isset($_REQUEST['Status'])){
      if($_REQUEST['Status'] == "Delete") 
         $sql = "DELETE FROM Users WHERE UserID = ".$_REQUEST['UserID'];
      else
         $sql = "UPDATE Users SET Status = '".$_REQUEST['Status']."' "  
            ."WHERE UserID = ".$_REQUEST['UserID'];
      try {
         $dbh = new PDO($dsn, $user, $pass);
      } catch (PDOException $e) {
         echo 'Connection failed: '.$e->getMessage();
      }
      $query = $dbh->prepare($sql);
      $query->execute();
      $PageMode ="List";
   }
}
if(isset($_REQUEST['ListOrder'])){
   $ListOrder = $_REQUEST['ListOrder'];
} else {
   $ListOrder = "UserID";
}?>
<?php include('../includes/header.php'); ?>
<?php include('../admin/adminmenu.php'); ?>
<DIV class="content">
   <p><a href="../public/lifespeak.php">Home</a> | 
   <a href="../admin/adminpage.php">Admin</a> | 
   <b>User Listing</b></p>
   <small>
      <style>
         table, tr, th,td {font-size:x-small;}
      </style>
   <form name="pageform" method="post">
   <input name="UserID" type="hidden" value="" />
   <input name="Status" type="hidden" value="" />
   <input name="ListOrder" type="hidden" value="<?php echo $ListOrder;?>" />
   <input name="PageMode" type="hidden" value="<?php echo $PageMode;?>" />
   <table class="report" width="60">
      <tr>
         <th style="cursor:hand;" onclick="pageform.ListOrder.value = 'UserID'; pageform.submit();">ID</th>
         <th style="cursor:hand;" onclick="pageform.ListOrder.value = 'Email'; pageform.submit();">User Email</th>
         <th style="cursor:hand;" onclick="pageform.ListOrder.value = 'FirstName'; pageform.submit();">First Name</th>
         <th style="cursor:hand;" onclick="pageform.ListOrder.value = 'LastName'; pageform.submit();">Last Name</th>
         <th style="cursor:hand;" onclick="pageform.ListOrder.value = 'Status'; pageform.submit();">Status</th>
         <th style="cursor:hand;" onclick="pageform.ListOrder.value = 'Registered'; pageform.submit();">Registered</th>
         <th style="cursor:hand;" onclick="pageform.ListOrder.value = 'LastVisited'; pageform.submit();">Last Visited</th>
      </tr><?php
$sql = "SELECT UserID, Email, FirstName, LastName, Status, Registered, LastVisited "
   ."FROM Users ORDER BY ".$ListOrder;
try{
   $dbh = new PDO($dsn, $user, $pass);
} catch (PDOException $e) {
   echo 'Connection failed: '.$e->getMessage();
}
$results = $dbh->query($sql);
if($results){
   foreach($results AS $row){?>
   <tr>
      <td><?php echo $row['UserID'];?></td>
      <td><?php echo $row['Email'];?></td>
      <td><?php echo $row['FirstName'];?></td>
      <td><?php echo $row['LastName'];?></td>
      <td><Select onchange="if(this.value=='Delete'){
            if(!confirm('Delete user <?php echo $row['Email'];?>?')){
               return(false);
            }
         }
         this.form.UserID.value = '<?php echo $row['UserID'];?>'; 
         this.form.Status.value = this.value;
         this.form.PageMode.value = 'Update';
         this.form.submit();">
         <option value="Visitor"<?php echo ($row['Status'] == "Visitor"?" Selected":"");?>>Visitor</option>
         <option value="Coach"<?php echo ($row['Status'] == "Coach"?" Selected":"");?>>Coach</option>
         <option value="Client"<?php echo ($row['Status'] == "Client"?" Selected":"");?>>Client</option>
         <option value="Admin"<?php echo ($row['Status'] == "Admin"?" Selected":"");?>>Admin</option>
         <option value="Register"<?php echo ($row['Status'] == "Register"?" Selected":"");?>>Register</option>
         <option value="Expired"<?php echo ($row['Status'] == "Expired"?" Selected":"");?>>Expired</option>
         <option value="Delete">Delete</option>
      </Select>
      </td>
      <td nowrap><?php echo ($row['Registered']);?></td>
      <td nowrap><?php echo ($row['LastVisited']);?></td>
   </tr><?php
   }
}
unset($results);
unset($dbh);?>
   </table>
   </form>
   </small>
</DIV> <!-- content -->
<?php include('../includes/footer.php'); ?>
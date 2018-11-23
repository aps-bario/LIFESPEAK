<?php
include('../includes/functions.php');
if(!CheckStatus(array('Admin'))){?>
<?php include('../includes/header.php'); ?>
<?php include('../includes/leftmenu.php'); ?>
<DIV class="content">
<h3>Site Administration</h3>
<p>This area is restricted to those who administer the various 
dynamic aspects of this web-site.</p><?php
} else { 
   $Members = "Admin";?>
<?php include('../includes/header.php'); ?>
<?php include('../admin/adminmenu.php'); ?>
<DIV class="content">
<h3>Welcome <?php echo $_SESSION['FirstName']?></h3>
<p>You may access various resources by clicking on the menu items on your left.</p>
</DIV> <!-- content -->
<?php include('../includes/footer.php'); ?><?php
}?>



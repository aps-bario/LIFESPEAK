<?php
include('../includes/functions.php');
if(!CheckStatus(array('Admin','Client','Visitor'))){ 
   //Header("Location: ../Public/Login.php");?>
<?php include('../includes/header.php'); ?>
<?php include('../includes/leftmenu.php'); ?>
<DIV class="content"> 
<p><h3>We value our clients</h3>
Once registered visitors to LIFESPEAK Coaching have purchased their first 
coaching session they are automatically given access to additional features 
and resources on this web-site. They have access to personal information about 
coaches, the ability to manage their coaching appointments on-line and the use
of tools that will enable the processing of the nuggets of self awareness aquired
during coaching sessions.</p><?php
} else { 
   $Members = 'Clients';?>
<?php include('../includes/header.php'); ?>
<?php include('../clients/clientsmenu.php'); ?>
<DIV class="content"> 
<h3>Welcome <?php echo $_SESSION['FirstName'];?></h3>
<p>This area of the site is currently under construction. </p>
<p>It can only be accessed by users who are clients of LIFESPEAK Coaching.</p><?php
}?>
</DIV> <!-- content -->
<?php include('../includes/footer.php'); ?>


<?php
include('../includes/functions.php');
if(!CheckStatus(array('Admin','Client','Visitor'))){ 
   Header("Location: ../Public/Login.php");
} else { 
   $Members = 'Visitor';?>
<?php include('../includes/header.php'); ?>
<?php include('../includes/leftmenu.php'); ?>
<DIV class="content">   
<h3>Welcome <?php echo $_SESSION['FirstName'];?></h3>
<p>You have been successfully registered as a visitor and now have access to more information.</P>
This is a relatively new area of the site and is therefore still being developed. However, 
new information is being added, so please bookmark this page and next time you drop in, there will 
be more to see.
<p>Access to this page is restricted to people who have registered on this web-site.</p>
</DIV> <!-- Content -->
<?php include('../includes/footer.php'); ?><?php
}?>

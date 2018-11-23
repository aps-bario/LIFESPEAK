<?php
include('../includes/functions.php');
if(!CheckStatus(array('Admin','Client','Visitor'))){
   //Header("Location: ../Public/Login.php");?>
<?php include('../includes/header.php'); ?>
<?php include('../includes/leftmenu.php'); ?>
<DIV class="content">   
<h3>LIFESPEAK Coaching Welcomes Visitors</h3>
<p>The purpose of this web-site is not merely to provide information about 
LIFESPEAK Coaching. It also provides resources to both coaches and those 
interested in life coaching. Registering as a visitor on this site is the first
step to finding out more about LIFESPEAK and the services offered.</p>
<p>Registration as a visitor is FREE and secure, your contact details will not 
be passed to any third party. They will however, mean that you will on occasion 
receive news of new services and features on this site. Also, being a registered 
visitor enables you to take advantage of the on-line booking system, allowing us 
to very quickly put you in touch with a life coach.</p> 
<p>So why not register today and gain access to the more features and information?
</p>
</DIV> <!-- Content --><?php
} else {
   $Members = 'Visitor';?>
<?php include('../includes/header.php'); ?>
<?php include('../visitors/visitorsmenu.php'); ?>
<DIV class="content">   
<h3>Welcome <?php echo $_SESSION['FirstName'];?></h3>
<P align="left">You have been successfully registered as a visitor and now have access to more information.</P>
This is a relatively new area of the site and is therefore still being developed. However, 
new information is being added, so please bookmark this page and next time you drop in, there will 
be more to see.
<p>Access to this page is restricted to people who have registered on this web-site.</p>
</DIV> <!-- content --><?php
}?>
<?php include('../includes/footer.php');?>
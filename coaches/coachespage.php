<?php
include('../includes/functions.php');
if(!CheckStatus(array('Admin','Coach'))){ 
   //Header("Location: ../Public/Login.php");?>
<?php include('../includes/header.php'); ?>
<?php include('../includes/leftmenu.php'); ?>
<DIV class="content">
<p><h3>Interested in coaching with LIFESPEAK?</h3>
If you are already a qualified life coach, with a recognised diploma in coaching
practise and at least one years experience of coaching professionally, then you may like to 
take the opportunity of coaching clients for LIFESPEAK Coaching. 
Email <a href="mailto:coaching@lifespeak.co.uk">coaching@lifespeak.co.uk</a> for more information.</p>
<p><h4>Ever considered becoming a life coach?</h4>
Although LIFESPEAK Coaching is not a training organisation, if you would like
to discuss your options for training as a life coach, we would be happy to recommend 
various organisations that do provide coach training to certificate and diploma level. 
For more information email 
<a href="mailto:coaching@lifespeak.co.uk">coaching@lifespeak.co.uk</a>.</p>
<p><h4>How about joining a coaching exchange?</h4>
Whether you are already a professional coach, currently studying for your certificate 
or diploma in coaching practise, or simply interested in finding out more about coaching, 
then there are real benefits in joining a local coaching exchange.</p> 
<p>These are informal gatherings of coaches and others, who meet all over the country, 
usually on a monthly basis, to discuss coaching techniques and best practise. If  
you are looking to maintain your CPD, discover new skills or simply looking for 
some fellowship and support. If you live in the Coventry/Warwick Area check out the 
our local exchange.</P>
</DIV> <!-- content --><?php
} else {?>
<?php include('../includes/header.php'); ?>
<?php include('../coaches/coachesmenu.php'); ?>
<DIV class="content">
<h3>Welcome <?php echo $_SESSION['FirstName']?></h3>
<p>This area of the site is currently under construction. </p>
<p>It can only be accessed by users who have registered as coaches with Lifespeak Coaching.</p>
</DIV> <!-- content --><?php
}?>
<?php include('../includes/footer.php');?>
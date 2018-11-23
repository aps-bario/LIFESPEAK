<?php
include('../includes/functions.php');
if(!CheckStatus(array('Admin','Client','Coach','Visitor'))){ 
   Header("Location: ../Public/Login.php");
} 
$Members = 'Visitor';?>
<?php include('../includes/header.php'); ?>
<?php include('../includes/leftmenu.php'); ?>
<DIV class="content"> 
   <H3>Solution Provider turned Life Coach</H3>
   <table>
   <tr>
      <td width=200><a href="../images/Andrew_MCMI.jpg" target="Andrew Smith - Life Coach"><img src="../images/Andrew_MCMI.jpg" width=200/></a></td>
      <td>
      <table width=150 border="0" cellspacing="0" cellpadding="0" style="margin-left:25">
         <tr> 
          <td height="15"> <input checked name="goals" type="checkbox" 
          id="goals" value="checkbox"/></td>
          <td>Achieving Your Goals</td>
        </tr>
        <tr> 
          <td width="25" height="15"></td>
          <td></td>
        </tr>
        <tr> 
          <td width="25" height="15"> <input checked name="relations" type="checkbox" 
          id="relations" value="checkbox"/></td>
          <td>Better Relationships</td>
        </tr>
        <tr> 
          <td width="25" height="15"></td>
          <td></td>
        </tr>
        <tr> 
          <td width="25" height="15"> <input name="career" type="checkbox" 
          id="career" value="checkbox" checked/></td>
          <td>Career Progression</td>
        </tr>
        <tr> 
          <td width="25" height="15"></td>
          <td width="125"></td>
        </tr>
        <tr> 
          <td height="15"> <input name="cross-cultural" type="checkbox" 
          id="cross-cultural" value="checkbox" checked/></td>
          <td>Cross-cultural</td>
        </tr>
        <tr> 
          <td width="25" height="15"></td>
          <td ></td>
        </tr>
         <tr> 
          <td width="25" height="15"> <input checked name="spiritual" type="checkbox" 
          id="spiritual" value="checkbox"/></td>
          <td>Spiritual Growth</td>
        </tr>
        <tr> 
          <td width="25" height="15"></td>
          <td></td>
        </tr>
        <tr> 
          <td width="25" height="15"> <input checked name="worklife" type="checkbox" 
          id="worklife" value="checkbox"/></td>
          <td>Work / Life Balance</td>
        </tr>
      </table>
      </td>
      <td valign=top>
         <img src="../images/Coach-&-Ment-Int-accredited.gif" />
         <!--<img src="../images/ukclc-accredited-coach.gif" />-->
      </td>
   </tr>
   </table>
    <H3>Andrew P Smith &nbsp; BSc MCLC MCMI</H3>
    <p>With many years experience of analysing problems and providing solutions in the IT industry, 
    Andrew knows that the best solutions usually come from those who have made space to reflect 
    and become aware of what they need to change. The same is true in almost every aspect of life.</p>
    <p class="quote" style="text-align:center"><b>People are usually more committed to<br />goals they set and solutions they own.</b></p>
    <p>No two people in the world are the same, and yet we all share a creative ability to dream 
    dreams and the potential to transform our situations. With personal knowledge of several 
    different cultures, and an active interest in the origin of ideas and beliefs, Andrew is 
    passionate about helping others become aware of where they are coming from, then supporting 
    them as they find their own way to what and where they want to be.</p>
    <p class="quote" style="text-align:center"><b>
      Whatever you want to achieve in life, the initiative needs to be yours,<br />
      but with a life coach you gain clarity, direction and motivation to reach that goal.</b></p>
   <p>Based right on the doorstep of the University of Warwick, in Coventry, 
    many of those Andrew has helped over the years have been scholars from Asia 
    and the Far East. Following the accreditation of his coaching skills and 
    becoming a full member of the UK College of Life Coaching, he has coached 
    people from very different walks of life. He has also discovered that the 
    location of his practice has become less important as the majority of people 
    feel more open and comfortable being coached over phone.</p>
    
    <p>Andrew is a director of LIFESPEAK Ltd.</p>
</DIV> 
</DIV> <!-- content -->
<?php include('../includes/footer.php'); ?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<!-- #Include File="../includes/head.asp" -->
<BODY>
<!-- #Include File="../includes/paper_tl.asp" -->
<!-- #Include File="../includes/coaches.asp" -->
<DIV class="body"><%
If Not Session("UserStatus") = "Coach" _ 
   And Not Session("UserStatus") = "Admin" Then
   Members = "Coaches"%>
<DIV class="leftmenu">
<!-- #Include File="../includes/leftmenu.asp" -->
</DIV>
<DIV class="main">
<h3>Interested in coaching with LIFESPEAK?</h3>
<p>If you are already a qualified life coach, with a recognised diploma in coaching
practise and at least one years experience of coaching professionally, then you may like to 
take the opportunity of coaching clients for Lifespeak Coaching. 
Email <a href="mailto:coaching@lifespeak.co.uk">coaching@lifespeak.co.uk</a> for more information.</p>
<h3>Ever considered becoming a life coach?</h3>
<p>Although Lifespeak Coaching is not a training organisation, if you would like
to discuss your options for training as a life coach, we would be happy to recommend various organistions that do provide coach training to certificate and 
diploma level. 
For more information email 
<a href="mailto:coaching@lifespeak.co.uk">coaching@lifespeak.co.uk</a>.</p>
<h3>How about joining a coaching exchange?</h3>
<p>Whether you are already a professional coach, currently studying for your certificate 
or diploma in coaching practise, or simply interested in finding out more abour coaching, 
then there are real benefits in joining a local coaching exchange.</p> 
<p>These are informal gatherings of coaches and others, who meet all over the country, 
usually on a monthly basis, to discuss coaching techiques and best practise. If  
you are looking to maintain your CPD, discover new skills or simply looking for 
some fellowship and support, why not check out 
<a target="_blank" href="http:\\www.coaching-exchange.com">www.coaching-exchange.com</a>. 
Alternatively, if you live in the Coventry/Warwick Area check out the our local exchange.</P>
</DIV> 
<!-- #Include File="../includes/rightmenu.asp" --><%
Else%>
<DIV class="leftmenu">
<!-- #Include File="../includes/coachesmenu.asp" -->
</DIV>
<DIV class="main" style="width:auto;">
<h3>Welcome <%=Session("FirstName")%></h3>
<p>This area of the site is currently under construction. </p>
<p>It can only be accessed by users who have registered as coaches with Lifespeak Coaching.</p>
</DIV><%
End If%>
</DIV> 
<!-- #Include File="../includes/footer.asp" -->
<!-- #Include File="../includes/paper_br.asp" -->
</BODY>
</HTML>


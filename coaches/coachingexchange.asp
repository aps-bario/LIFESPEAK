<%
If Not Session("UserStatus") = "Visitor"  _ 
   And Not Session("UserStatus") = "Client" _ 
   And Not Session("UserStatus") = "Coach" _ 
   And Not Session("UserStatus") = "Admin" Then 
   Response.Redirect "../Public/Login.asp"
End If%><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<!-- #Include File="../includes/head.asp" -->
<BODY>
<!-- #Include File="../includes/paper_tl.asp" -->
<!-- #Include File="../includes/visitors.asp" -->
<DIV class="body">
<DIV class="leftmenu">
<!-- #Include File="../includes/coachesmenu.asp" -->
</DIV>
<DIV class=main style="width:auto;">
   <H3>Coventry and Warwick Coaching Exchange</H3>
   <p>Details coming soon ...</p>
</DIV> 
</DIV>
<!-- #Include File="../includes/footer.asp" -->
<!-- #Include File="../includes/paper_br.asp" -->
</BODY>
</HTML>


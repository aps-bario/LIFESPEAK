<%
If Not Session("UserStatus") = "Admin" Then 
   Response.Redirect "../Public/Login.asp"
End If
<!-- #Include File="../includes/dbOpen.asp" -->
DSN = "DRIVER={Microsoft Access Driver (*.mdb)};DBQ=" & Server.Mappath("../db/lifespeak.mdb") & ";"
Set cnData = Server.CreateObject("ADODB.Connection")
cnData.Open(DSN)
PageMode = Request("PageMode")
If PageMode = "" Then 
   PageMode = "List"
End If
If PageMode = "Update" Then 
   If Not Request("UserID") = "" And Not Request("Status") = "" Then
      If Request("Status") = "Delete" Then 
         cSQL = "DELETE FROM USERS WHERE USERID = " & Request("UserID")
      Else
         cSQL = "UPDATE USERS SET STATUS = '" & Request("Status") & "' " _ 
            & "WHERE USERID = " & Request("UserID")
      End If
      cnData.execute(cSQL)
   End If
   PageMode = "List"
End If
ListOrder = Request("ListOrder")
If ListOrder = "" Then 
   ListOrder = "USERID"
End If%>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<!-- #Include File="../includes/head.asp" -->
<BODY>
<!-- #Include File="../includes/paper_tl.asp" -->
<!-- #Include File="../includes/admin.asp" -->
<DIV class="body">
   <p><a href="../public/lifespeak.asp">Home</a> | 
   <a href="../admin/admin.asp">Admin</a> | 
   <b>User Listing</b></p>
   <form name="pageform" method="post">
   <input name="UserID" type="hidden" value="" />
   <input name="Status" type="hidden" value="" />
   <input name="ListOrder" type="hidden" value="<%=ListOrder%>" />
   <input name="PageMode" type="hidden" value="<%=PageMode%>" />
   <table class="report">
      <tr>
         <th style="cursor:hand;" onclick="pageform.ListOrder.value = 'USERID'; pageform.submit();">ID</th>
         <th style="cursor:hand;" onclick="pageform.ListOrder.value = 'EMAIL'; pageform.submit();">User Email</th>
         <th style="cursor:hand;" onclick="pageform.ListOrder.value = 'FIRSTNAME'; pageform.submit();">First Name</th>
         <th style="cursor:hand;" onclick="pageform.ListOrder.value = 'LASTNAME'; pageform.submit();">Last Name</th>
         <th style="cursor:hand;" onclick="pageform.ListOrder.value = 'STATUS'; pageform.submit();">Status</th>
         <th style="cursor:hand;" onclick="pageform.ListOrder.value = 'REGISTERED'; pageform.submit();">Registered</th>
         <th style="cursor:hand;" onclick="pageform.ListOrder.value = 'LASTVISITED'; pageform.submit();">Last Visited</th>
      </tr><%
    cSQL = "SELECT * FROM USERS ORDER BY " & ListOrder
    Set rsUsers = cnData.execute(cSQL)
    Do While Not rsUsers.EOF%>
   <tr>
      <td><%=rsUsers("USERID")%></td>
      <td><%=rsUsers("EMAIL")%></td>
      <td><%=rsUsers("FIRSTNAME")%></td>
      <td><%=rsUsers("LASTNAME")%></td>
      <td><Select onchange="if(this.value=='Delete'){
            if(!confirm('Delete user <%=rsUsers("EMAIL")%>?')){
               return(false);
            }
         }
         this.form.UserID.value = '<%=rsUsers("USERID")%>'; 
         this.form.Status.value = this.value;
         this.form.PageMode.value = 'Update';
         this.form.submit();">
         <option value="Visitor" <%If rsUsers("STATUS") = "Visitor" Then%>selected<%End If%>>Visitor</option>
         <option value="Coach" <%If rsUsers("STATUS") = "Coach" Then%>selected<%End If%>>Coach</option>
         <option value="Client" <%If rsUsers("STATUS") = "Client" Then%>selected<%End If%>>Client</option>
         <option value="Admin" <%If rsUsers("STATUS") = "Admin" Then%>selected<%End If%>>Admin</option>
         <option value="Register" <%If rsUsers("STATUS") = "Register" Then%>selected<%End If%>>Register</option>
         <option value="Expired" <%If rsUsers("STATUS") = "Expired" Then%>selected<%End If%>>Expired</option>
         <option value="Delete">Delete</option>
      </Select>
      </td>
      <td><%=rsUsers("REGISTERED")%></td>
      <td><%=rsUsers("LASTVISITED")%></td>
   </tr><%
      rsUsers.MoveNext
    Loop
    rsUsers.Close
    Set rsUsers = Nothing%>
   </table>
   </form>
</DIV> 
<!-- #Include File="../includes/footer.asp" -->
<!-- #Include File="../includes/paper_br.asp" -->
</BODY>
</HTML><%
cnData.Close
Set cnData=Nothing%>
<!-- #Inc lude File="../includes/dbClose.asp" -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><BODY><%
If Session("UserStatus") = "Coach" _ 
    Or Session("UserStatus") = "Admin" Then 
    DSN = "DRIVER={Microsoft Access Driver (*.mdb)};DBQ=" & Server.Mappath("../db/lifespeak.mdb") & ";"
    Set cnData = Server.CreateObject("ADODB.Connection")
    cnData.Open(DSN)
    PageMode = Request("PageMode")
    If PageMode = "AddSession" Then 
        cSQL = "INSERT INTO SESSIONS (COACHID, CLIENTID, STATUS, SESSIONTIME) " _ 
            & "VALUES (0" & Session("UserID") & ",0,'Session'," _ 
            & "#" & Request("Month") & "/" & Request("Day") & "/"& Request("Year") _ 
            & " " & Request("Hour") & ":00:00#) "
        Response.Write cSQL
        cnData.execute(cSQL)
    End If 
    If PageMode = "DelSession" Then 
        cSQL = "DELETE FROM SESSIONS " _ 
            & "WHERE STATUS = 'Session' " _ 
            & "AND COACHID = 0" & Session("UserID") & " " _ 
            & "AND SESSIONTIME = #" & Request("Month") _
                & "/" & Request("Day") _
                & "/"& Request("Year") _ 
                & " " & Request("Hour") _
                & ":00:00# "
        Response.Write cSQL
        cnData.execute(cSQL)
    End If
    cnData.Close()
    Set cnData = Nothing%>Done<%
End If%>
</body></html>

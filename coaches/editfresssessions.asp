<%
If Not Session("UserStatus") = "Coach" _ 
   And Not Session("UserStatus") = "Admin" Then 
   Response.Redirect "../Public/Login.asp"
End If
DSN = "DRIVER={Microsoft Access Driver (*.mdb)};DBQ=" & Server.Mappath("../db/lifespeak.mdb") & ";"
Set cnData = Server.CreateObject("ADODB.Connection")
cnData.Open(DSN)
PageMode = Request("PageMode")
If PageMode = "" Then 
   PageMode = "List"
End If
ListOrder = Request("ListOrder")
If ListOrder = "" Then 
   ListOrder = "USERID"
End If
ThisMonth = Request("Month")
ThisDay = Request("Day")%>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<head>
<style>
.Disabled {background-color:#FFFFFF; color:#000000;}
.NoSession {background-color:#DDDDDD; color:#000000; cursor:hand;}
.Session {background-color:#80AA80; color:#FFFFFF; cursor:hand;}
.Booked {background-color:#600080; color:#FFFFFF;}
.Selected {background-color:#880000; color:#FFFFFF;}
.Request {background-color:#FFFF88; color:#000000;}
</style>
</head>
<!-- #Include File="../includes/head.asp" -->
<BODY>
<!-- #Include File="../includes/paper_tl.asp" -->
<!-- #Include File="../includes/admin.asp" -->
<DIV class="body">
   <p><a href="../public/lifespeak.aspx">Home</a> > 
   <a href="../coaches/coachesPage.asp">Coaches</a> > 
   <b>My Coaching Sessions</b></p>
   <form name="pageform" method="post" action="SaveSession.asp" target="SaveSession">
   <input name="UserID" type="hidden" value="" />
   <input name="SessionDateTime" type="hidden" value="<%=Request("SessionDateTime")%>" />
   <input name="Month" type="hidden" value="<%=ThisMonth%>" />
   <input name="Day" type="hidden" value="<%=ThisDay%>" />
   <input name="Year" type="hidden" value="<%=Request("year")%>" />
   <input name="Hour" type="hidden" value="<%=Request("hour")%>" />
   <input name="Status" type="hidden" value="" />
   <input name="ListOrder" type="hidden" value="<%=ListOrder%>" />
   <input name="PageMode" type="hidden" value="<%=PageMode%>" />
   <input name="UserID" type="hidden" value="<%=Session("UserID")%>" /><%
    EarliestDate = Now() 
    If ThisMonth = "" Then 
        ThisMonth = CStr(Month(Now))
    End If
    If ThisDay = "" Then 
        ThisDay = CStr(Day(EarliestDate+1))
    End If
'    cSQL = "UPDATE SESSIONS SET STATUS = 'Session' WHERE STATUS = 'Free'"
'    cnData.execute(cSQL)
    cSQL = "SELECT S.SESSIONTIME, S.CLIENTID, S.STATUS, " _ 
        & " U.FIRSTNAME, U.LASTNAME " _ 
        & "FROM SESSIONS S LEFT JOIN USERS U ON S.CLIENTID = U.USERID " _
        & "WHERE S.SESSIONTIME > #" & FormatDateTime(EarliestDate+1,2) & "# " _ 
        & "AND S.COACHID = 0" & Session("UserID") & " " _ 
        & "ORDER BY SESSIONTIME "
    Set rsSessions = cnData.execute(cSQL)
    FirstDayThisMonth = DateSerial(Year(Date()),Month(Date()), 1)
    LastDayThisMonth = DateSerial(Year(Date()),Month(Date())+1, 0)
    FirstDayNextMonth = DateSerial(Year(Date()),Month(Date())+1, 1)
    LastDayNextMonth = DateSerial(Year(Date()),Month(Date())+2, 0)
    LatestDate = Now() + 30
    DayNum = 1%>
    <table><tr><td valign="top">
    <table class="calendar">
    <tr><th colspan=7 align="center"><h3 style="margin-bottom:0;">
    <%=MonthName(Month(FirstDayThisMonth))%> &nbsp; <%=Year(FirstDayThisMonth)%>
    </h3></th></tr>
    <tr><th>S</th><th>M</th><th>T</th><th>W</th><th>T</th><th>F</th><th>S</th></tr>
    <tr><%
    Do While DayNum < WeekDay(FirstDayThisMonth)%>
        <td class="Disabled">&nbsp;</td><%
        DayNum = DayNum + 1
    Loop
    TempDate = FirstDayThisMonth
    Do While TempDate < EarliestDate
        If DayNum > 7 Then%>
        </tr><tr><% 
           DayNum = 1
        End If%>
        <td class="Disabled"><%=Day(TempDate)%></td><%
        TempDate = TempDate + 1
        DayNum = DayNum + 1
    Loop 
    Do While TempDate < FirstDayNextMonth
        If DayNum > 7 Then%>
        </tr><tr><% 
           DayNum = 1
        End If
        ThisClass = "NoSession"
        If Not rsSessions.EOF Then  
            Do While FormatDateTime(rsSessions("SESSIONTIME"),2) = FormatDateTime(TempDate,2)
                If rsSessions("STATUS") = "Request" _ 
                    And Not ThisClass = "Session" _ 
                    And Not ThisClass = "Booked" Then
                    ThisClass = "Request"
                ElseIf rsSessions("STATUS") = "Session" _ 
                    And Not ThisClass = "Booked" Then
                    ThisClass = "Session"
                ElseIf rsSessions("STATUS") = "Booked" Then
                    ThisClass = "Booked"
                End If
                rsSessions.MoveNext
                If rsSessions.EOF Then Exit Do
             Loop
        End If
        If Month(TempDate) = CInt(ThisMonth) And Day(TempDate) = CInt(ThisDay) Then 
            ThisClass = "Selected"
        End If %>
        <td align="right" class="<%=ThisClass%>" 
            onclick="pickDate(this,'<%=Month(TempDate)%>','<%=Day(TempDate)%>','<%=Year(TempDate)%>');">
            <%=Day(TempDate)%></td><%
        TempDate = TempDate + 1
        DayNum = DayNum + 1
    Loop 
    Do While DayNum <= 7%>
        <td class="Disabled">&nbsp;</td><%
        DayNum = DayNum + 1
    Loop%>
    </tr>
    </table><br/>
    <table class="calendar">
    <tr><th colspan=7 align="center"><h3 style="margin-bottom:0;">
    <%=MonthName(Month(FirstDayNextMonth))%> &nbsp; 
    <%=Year(FirstDayNextMonth)%></h3></th></tr>
    <tr><th>S</th><th>M</th><th>T</th><th>W</th><th>T</th><th>F</th><th>S</th></tr>
    <tr><%
    DayNum = 1
    Do While DayNum < WeekDay(FirstDayNextMonth)%>
        <td class="Dead">&nbsp;</td><%
        DayNum = DayNum + 1
    Loop
    TempDate = FirstDayNextMonth
    Do While TempDate <= LastDayNextMonth
        If DayNum > 7 Then%>
        </tr><tr><%
           DayNum = 1
        End If
        ThisClass = "NoSession"
        If Not rsSessions.EOF Then  
            Do While FormatDateTime(rsSessions("SESSIONTIME"),2) = FormatDateTime(TempDate,2)
                If rsSessions("STATUS") = "Request" _ 
                    And Not ThisClass = "Session" _ 
                    And Not ThisClass = "Booked" Then
                    ThisClass = "Request"
                ElseIf rsSessions("STATUS") = "Session" _ 
                    And Not ThisClass = "Booked" Then
                    ThisClass = "Session"
                ElseIf rsSessions("STATUS") = "Booked" Then
                    ThisClass = "Booked"
                End If
                rsSessions.MoveNext
                If rsSessions.EOF Then Exit Do
             Loop
        End If
        If Month(TempDate) = CInt(ThisMonth) And Day(TempDate) = CInt(ThisDay) Then 
            ThisClass = "Selected"
        End If%>
        <td align="right" class="<%=ThisClass%>" 
            onclick="pickDate(this,'<%=Month(TempDate)%>','<%=Day(TempDate)%>','<%=Year(TempDate)%>');">
            <%=Day(TempDate)%></td><%
        TempDate = TempDate + 1
        DayNum = DayNum + 1
    Loop 
    Do While DayNum <= 7%>
        <td class="Disabled">&nbsp;</td><%
        DayNum = DayNum + 1
    Loop%>
    </tr>
    </table></td>
    <td width=5>&nbsp;</td>
    <td valign="top">
    <table class="calendar">
    <tr><th colspan=2><h3 style="margin-bottom:0;"><%=ThisDay%>&nbsp;<%=MonthName(ThisMonth)%>&nbsp;Sessions</h3></th></tr><%
    If Not (rsSessions.BOF And rsSessions.EOF) Then 
        rsSessions.MoveFirst
    End If
    If Not rsSessions.EOF Then  
        Do While Not (Month(CDate(rsSessions("SESSIONTIME"))) = CInt(ThisMonth) _ 
            And Day(CDate(rsSessions("SESSIONTIME")))= CInt(ThisDay)) 
            rsSessions.MoveNext
            If rsSessions.EOF Then 
                Exit Do
            End If
        Loop
    End If
    SessionTime = 8
    Do While SessionTime < 23
        ThisClass = "NoSession"
        ThisStatus = ""
        ThisClient = ""
        If Not rsSessions.EOF Then  
            ThisSession = CDate(rsSessions("SESSIONTIME"))
            If Month(ThisSession) = CInt(ThisMonth) _ 
                And Day(ThisSession)= CInt(ThisDay) _
                And Hour(ThisSession)= SessionTime Then 
                ThisClass = rsSessions("STATUS")
                ThisClient = rsSessions("FIRSTNAME") & " " & rsSessions("LASTNAME")
                rsSessions.MoveNext
            End If
        End If%>
    <tr onclick="if(this.className == 'Booked') 
            alert('You have a client booking at this time');
        else
            pickHour(this,'<%=SessionTime%>');" class="<%=ThisClass%>" <%
        If ThisClass = "Booked" Then%>title="This session has already been booked"<%
        Else%> title="Click to offer or cancel a coaching session"<%
        End If%>>
        <td align="right" width="10%" ><%=SessionTime%>:00</td>
        <td align="left" width="90%" ><%=ThisClient%></td>
    </tr><%
        SessionTime = SessionTime + 1
    Loop%>
    </table>
    </td>
    </tr>
    </table><%
If False Then    
    If Not (rsSessions.BOF And rsSessions.EOF) Then 
        rsSessions.MoveFirst
    End If
    If Not (rsSessions.BOF And rsSessions.EOF) Then%>
    <table class="report">
    <tr>
         <th style="cursor:hand;" onclick="pageform.ListOrder.value = 'SESSIONTIME'; pageform.submit();">Session Time</th>
    </tr><%
        Do While Not rsSessions.EOF%>
       <tr>
          <td><%=rsSessions("SESSIONTIME")%></td>
        </tr><% 
        rsSessions.MoveNext
        Loop%>
    </table><%
    Else%>
    <h2>You currently have no free sessions listed.</h2><%
    End If
End If    
    rsSessions.Close
    Set rsSessions = Nothing%>
    </form>
</DIV> 
<iframe name="SaveSession" width="100%" height="100"> </iframe>
<!-- #Include File="../includes/footer.asp" -->
<!-- #Include File="../includes/paper_br.asp" -->
</BODY>
</HTML>
<!-- #Inc lude File="../includes/dbClose.asp" --><%
%>
<Script>
function pickDate(obj,sMon,sDay,sYear) {
    pageform.Month.value = sMon;
    pageform.Day.value = sDay;
    pageform.Year.value = sYear;
    obj.className = 'Selected';
    pageform.action = '';
    pageform.target = ''
    pageform.submit();
} 
function pickHour(obj,sHour) {
    pageform.Hour.value = sHour;
    if(obj.className == 'Session'){
        obj.className = 'NoSession';
        delSession();
    } else {
        obj.className = 'Session'
        addSession();
    }
}
function addSession() {
    pageform.SessionDateTime.value = '#'
        + pageform.Month.value + '/' 
        + pageform.Day.value + '/'
        + pageform.Year.value + ' '
        + pageform.Hour.value + ':00' 
        + '#'; 
    pageform.PageMode.value = 'AddSession';
    pageform.submit();
}
function delSession() {
    pageform.SessionDateTime.value = '#'
        + pageform.Month.value + '/' 
        + pageform.Day.value + '/'
        + pageform.Year.value + ' '
        + pageform.Hour.value + ':00' 
        + '#'; 
    pageform.PageMode.value = 'DelSession';
    pageform.submit();
}
</Script><%
cnData.Close()
Set cnData = Nothing%>

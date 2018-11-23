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
<!-- #Include File="../includes/head.asp" -->
<BODY>
<!-- #Include File="../includes/paper_tl.asp" -->
<!-- #Include File="../includes/admin.asp" -->
<DIV class="body">
   <p><a href="../public/lifespeak.asp">Home</a> | 
   <a href="../coaches/coachesPage.asp">Coaches</a> | 
   <b>My Coaching Sessions</b></p>
   <form name="pageform" method="post" action="SaveFreeSessions.asp" target="SaveFreeSessions">
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
    If ThisYear = "" Then 
       ThisYear = Year(EarliestDate)
    End If
    If ThisMonth = "" Then 
       ThisMonth = Month(EarliestDate)
    End If
    If ThisDay = "" Then 
        ThisDay = Day(EarliestDate)
    End If
    FirstDayThisMonth = DateSerial(Year(EarliestDate),Month(EarliestDate), 1)
    cSQL = "SELECT S.SESSIONTIME, S.CLIENTID, S.STATUS, " _ 
        & " U.FIRSTNAME, U.LASTNAME " _ 
        & "FROM SESSIONS S LEFT JOIN USERS U ON S.CLIENTID = U.USERID " _
        & "WHERE S.SESSIONTIME >= #" & Month(EarliestDate) & "/" _
        & Day(EarliestDate) & "/"& Year(EarliestDate) & "# " _ 
        & "AND (S.COACHID = 0" & Session("UserID") & " " _ 
        & "OR S.STATUS = 'Request') " _ 
        & "ORDER BY S.SESSIONTIME "
'    Response.Write cSQL
    Set rsSessions = cnData.execute(cSQL)
'    Response.Write "[" & rsSessions("SESSIONTIME") & "]" %>
    <table><tr><td valign="top"><h3>Making sessions available to clients</h3>
    <p>Clients may only book coaching sessions at dates and times that have
    been offered by coaches. Therefore, as a coach, you need to declare when 
    you are available and ensure that you keep this system up to date with any 
    other appointment diaries you maintain.</p>
    <p>For convenience of both clients and coaches all sessions are deemed to start 
    on the hour and last 30 - 45 minutes.</P>
    <p>To declare yourself available to coach a particular session, simply select the 
    date and click the time. Marking yourself unavailable, (perhaps because one of your
    own coaches booked a session by phone), again simply select the date and click the time.</p> 
    <p>If a client books a session that you have made available, then you will be notified
    immediately by email, and you will not them be able to mark yourself unavailable</P>
    <p>It is also possible for clients to request a session at a time when no coach has 
    declared themselves available. These requests are marked in yellow, and notified to all 
    coaches when the request is made. To accept a requested session, simply make yourself available at that time and the client will automatically be notified that they 
    may now book that session.</p>
    </td>
    <td width=5>&nbsp;</td>
    <td valign="top"><br /><%
    For m = 1 to 3
      LastDayThisMonth = DateSerial(Year(FirstDayThisMonth),Month(FirstDayThisMonth)+1, 0)
      FirstDayNextMonth = DateSerial(Year(FirstDayThisMonth),Month(FirstDayThisMonth)+1, 1)
      DayNum = 1%>
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
    If Not rsSessions.EOF Then  
        Do While rsSessions("SESSIONTIME") < TempDate
'Response.Write        rsSessions("SESSIONTIME")     
            rsSessions.MoveNext
            If rsSessions.EOF Then Exit Do
        Loop
    End If
    Do While TempDate < FirstDayNextMonth
        If DayNum > 7 Then%>
        </tr><tr><% 
           DayNum = 1
        End If
        ThisClass = "Unavailable"
        If Not rsSessions.EOF Then  
            If FormatDateTime(CDate(rsSessions("SESSIONTIME")),2) = FormatDateTime(TempDate,2) Then
               If ThisMonth = "" And ThisDay = "" Then 
                  ThisMonth = Month(TempDate)
                  ThisDay = Day(TempDate)
                  ThisYear = Year(TempDate)
               End If
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
    </table><br/><%
      FirstDayThisMonth = FirstDayNextMonth
    Next %>
    </td>
    <td width=5>&nbsp;</td>
    <td valign="top"><br />
    <table class="calendar">
    <tr>
      <th colspan=2 width=150>
      <h3 style="margin-bottom:0;">
         <%=WeekDayName(WeekDay(DateSerial(ThisYear,ThisMonth,ThisDay)))%>&nbsp;
         <%=ThisDay%>&nbsp;<%=MonthName(ThisMonth)%>
      </h3>
      </th>
    </tr><%
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
        ThisClass = "Unavailable"
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
    </table><br />
    <div style="width:100%;height:100%;text-align:center;">
    <table style="padding:10;">
    <tr><td class="Disabled"><b>Key :</b></td>
      <td class="Selected" title="Your current selection">Selected</td>  </tr>
    <tr><td>&nbsp;</td><td class="Unavailable" title="You are not listed as available">Unavailable</td>  </tr>
    <tr><td>&nbsp;</td><td class="Request" title="There is a request for a session here">Request</td>  </tr>
    <tr><td>&nbsp;</td><td class="Session" title="You are listed as available here">Available</td>  </tr>
    <tr><td>&nbsp;</td><td class="Cancelled" title="This session was cancelled by the client ">Cancelled</td>  </tr>
    <tr><td>&nbsp;</td><td class="Booking" title="This session is currently being booked">Booking</td>  </tr>
    <tr><td>&nbsp;</td><td class="Booked" title="You currently have a session booked here">Booked</td>  </tr>
    </table></div>
    </td>
    </tr>
    </table><%
    rsSessions.Close
    Set rsSessions = Nothing%>
    </form>
</DIV> 
<iframe name="SaveFreeSessions" width="100%" height="0"> </iframe>
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
        obj.className = 'Unavailable';
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

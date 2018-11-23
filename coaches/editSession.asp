<%
'If Not Session("UserStatus") = "Coach" _ 
'   And Not Session("UserStatus") = "Admin" Then 
'   Response.Redirect "../Public/Login.asp"
'End If

<!-- #Include File="../includes/dbOpen.asp" -->
DSN = "DRIVER={Microsoft Access Driver (*.mdb)};DBQ=" & Server.Mappath("../db/lifespeak.mdb") & ";"
Set cnData = Server.CreateObject("ADODB.Connection")
cnData.Open(DSN)
PageMode = Request("PageMode")
If PageMode = "" Then 
   PageMode = "List"
End If
If PageMode = "AddSession" Then 
    cSQL = "INSERT INTO SESSIONS (COACHID, CLIENTID, STATUS, SESSIONTIME) " _ 
        & "VALUES (0" & Session("UserID") & ",1, 'Free'," _ 
        & "#" & Request("Month") & "/" & Request("Day") & "/"& Request("Year") _ 
        & " " & Request("Hour") & ":00:00 #) "
    Response.Write cSQL
  '  cSQL = "DELETE FROM SESSIONS"    
    cnData.execute(cSQL)
End If
ListOrder = Request("ListOrder")
If ListOrder = "" Then 
   ListOrder = "USERID"
End If%>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<head>
<style>
.Selected {background-color:#880000;color:#FFFFFF;}
.Active {background-color:#CCCCCC; cursor:hand;}
.Dead {background-color:#FFFFFF;}
.Session {background-color:#8888FF;color:#FFFFFF;}
.Active {background-color:#CCCCCC; cursor:hand;}
.Dead {background-color:#FFFFFF;}
</style>
</head>
<!-- #Include File="../includes/head.asp" -->
<BODY>
<!-- #Include File="../includes/paper_tl.asp" -->
<!-- #Include File="../includes/admin.asp" -->
<DIV class="body">
   <p><a href="../public/lifespeak.aspx">Home</a> > 
   <a href="../coaches/coachesPage.asp">Coaches</a> > 
   <b>My Free Sessions</b></p>
   <form name="pageform" method="post">
   <input name="UserID" type="hidden" value="" />
   <input name="SessionDateTime" type="hidden" value="<%=Request("SessionDateTime")%>" />
   <input name="Month" type="hidden" value="<%=Request("month")%>" />
   <input name="Day" type="hidden" value="<%=Request("day")%>" />
   <input name="Year" type="hidden" value="<%=Request("year")%>" />
   <input name="Hour" type="hidden" value="<%=Request("hour")%>" />
   <input name="Status" type="hidden" value="" />
   <input name="ListOrder" type="hidden" value="<%=ListOrder%>" />
   <input name="PageMode" type="hidden" value="<%=PageMode%>" />
      <%
    cSQL = "SELECT SESSIONTIME " _ 
        & "FROM SESSIONS " _
        & "ORDER BY SESSIONTIME "
        '_ 
'        & "WHERE COACHID = " & Session("UserID") & " " _ 
    '    & "ORDER BY SESSIONTIME "
    Set rsSessions = cnData.execute(cSQL)
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
    rsSessions.Close
    Set rsSessions = Nothing
    
    EarliestDate = Now() 
    cSQL = "SELECT SESSIONTIME FROM SESSIONS " _
        & "WHERE SESSIONTIME >= #" & FormatDateTime(EarliestDate,2) & "# " _ 
        & "GROUP BY SESSIONTIME "
    '    & "WHERE COACHID = " & Session("UserID") & " " _ 
    Set rsSessions = cnData.execute(cSQL)
    
    FirstDayThisMonth = DateSerial(Year(Date()),Month(Date()), 1)
    LastDayThisMonth = DateSerial(Year(Date()),Month(Date())+1, 0)
    FirstDayNextMonth = DateSerial(Year(Date()),Month(Date())+1, 1)
    LastDayNextMonth = DateSerial(Year(Date()),Month(Date())+2, 0)
    LatestDate = Now() + 30
    DayNum = 1%>
    <table><tr><td>
    <table border=1 bordercolor="gray">
    <tr><td colspan=7><h3><%=MonthName(Month(FirstDayThisMonth))%> &nbsp; <%=Year(FirstDayThisMonth)%></h3></td></tr>
    <tr><%
    Do While DayNum < WeekDay(FirstDayThisMonth)%>
        <td class="Dead">&nbsp;</td><%
        DayNum = DayNum + 1
    Loop
    TempDate = FirstDayThisMonth
    Do While TempDate < EarliestDate
        If DayNum > 7 Then%>
        </tr><tr><% 
           DayNum = 1
        End If%>
        <td class="Dead"><%=Day(TempDate)%></td><%
        TempDate = TempDate + 1
        DayNum = DayNum + 1
    Loop 
    Do While TempDate < FirstDayNextMonth
        If DayNum > 7 Then%>
        </tr><tr><% 
           DayNum = 1
        End If
 '       SessionOnDate%>
        <td align="right" class="Active"
            onclick="pickDate(this,'<%=Month(TempDate)%>','<%=Day(TempDate)%>','<%=Year(TempDate)%>');
             //   addSession();"><%=Day(TempDate)%></td><%
        TempDate = TempDate + 1
        DayNum = DayNum + 1
    Loop 
    Do While DayNum <= 7%>
        <td class="Dead">&nbsp;</td><%
        DayNum = DayNum + 1
    Loop%>
    </tr>
    </table>
    <table border=1 bordercolor="gray">
    <tr><td colspan=7><h3><%=MonthName(Month(FirstDayNextMonth))%> &nbsp; <%=Year(FirstDayNextMonth)%></h3></td></tr>
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
        End If%>
        <!-- id="D<%=Year(TempDate)%><%=Month(TempDate)%><%=Day(TempDate)%>" -->
        <td align=right class="Active"
            onclick="pickDate(this,'<%=Month(TempDate)%>','<%=Day(TempDate)%>','<%=Year(TempDate)%>');
                //addSession();"><%=Day(TempDate)%></td><%
        TempDate = TempDate + 1
        DayNum = DayNum + 1
    Loop 
    Do While DayNum <= 7%>
        <td class="Dead">&nbsp;</td><%
        DayNum = DayNum + 1
    Loop
    rsSessions.close
    Set rsSessions = Nothing%>
    </tr>
    </table></td>
    <td width=20>&nbsp;</td>
    <td>
    <table border=1 bordercolor="gray">
    <tr><td colspan=2>Session Start Times</td></tr><%
    SessionTime = 8
    Do While SessionTime < 23%>
    <tr><td align="right" width=20  class="Active"
        onclick="pickHour(this,'<%=SessionTime%>'); 
            //addSession();"><%=SessionTime%>:00</td>
        <td > &nbsp;</td>
    </tr><%
        SessionTime = SessionTime + 1
    Loop%>
    </table>
    </td>
    </tr>
    </table>
    </form>
</DIV> 
<!-- #Include File="../includes/footer.asp" -->
<!-- #Include File="../includes/paper_br.asp" -->
</BODY>
</HTML><%
cnData.Close
Set cnData=Nothing
%>
<Script>
var oDate = null;
var oTime = null;
function pickDate(obj,sMon,sDay,sYear) {
   if(oDate != null){
        oDate.className = 'Active';
        oTime.className = 'Session';
    }
    pageform.Month.value = sMon;
    pageform.Day.value = sDay;
    pageform.Year.value = sYear;
    obj.className = 'Selected';
    oDate = obj;
    addSession();
} 
function pickHour(obj,sHour) {
    if(oTime != null){
        oTime.className = 'Active';
        oDate.className = 'Session';
    }
    pageform.Hour.value = sHour;
    obj.className = 'Selected';
    oTime = obj;
    addSession();
}
function addSession() {
    pageform.SessionDateTime.value = '#'
        + pageform.Month.value + '/' 
        + pageform.Day.value + '/'
        + pageform.Year.value + ' '
        + pageform.Hour.value + ':00' 
        + '#'; 
    pageform.PageMode.value = 'AddSession';
    if(confirm('Add Session: ' + pageform.SessionDateTime.value))
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
    if(confirm('Delete Session: ' + pageform.SessionDateTime.value))
        pageform.submit();
}
</Script>
<!-- #Inc lude File="../includes/dbClose.asp" -->
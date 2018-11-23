<%@ Language="VBScript" %>
<!--METADATA TYPE="typelib" 
   UUID="CD000000-8B95-11D1-82DB-00C04FB1625D" 
   NAME="CDO for Windows 2000 Library" -->
<!--METADATA TYPE="typelib" 
   UUID="00000205-0000-0010-8000-00AA006D2EA4" 
   NAME="ADODB Type Library" --><%

If Not Session("UserStatus") = "Client" _ 
   And Not Session("UserStatus") = "Visitor" _ 
   And Not Session("UserStatus") = "Admin" Then 
   Registered = False
'   Response.Redirect "../Public/Login.asp"
Else
   Registered = True
End If
DSN = "DRIVER={Microsoft Access Driver (*.mdb)};DBQ=" & Server.Mappath("../db/lifespeak.mdb") & ";"
Set cnData = Server.CreateObject("ADODB.Connection")
cnData.Mode = 3 '3 = adModeReadWrite
cnData.Open(DSN)
'
' Global declarations
'
ClientFirstName = ""
ClientLastName = ""
ClientEmail = ""
ClientPhone = ""
CoachFirstName = ""
CoachLastName = ""
CoachEmail = ""
CoachPhone = ""

PageMode = Request("PageMode")
ThisYear = Request("Year")
ThisMonth = Request("Month")
ThisDay = Request("Day")
ThisHour = Request("Hour")
ThisCoach = Request("CoachID")
ThisClient = Session("UserID")

If PageMode = "BookingSession" Then
    If BookingEmail() Then 
        cSQL = "UPDATE SESSIONS SET " _ 
            & "CLIENTID = 0" & ThisClient & ", " _ 
            & "SESSIONRATE = 55, " _ 
            & "FEESPAID = 0, " _            
            & "STATUS = 'Booking' " _ 
            & "WHERE SESSIONTIME = #" & ThisMonth & "/" & ThisDay _ 
            & "/"& ThisYear & " " & ThisHour & ":00:00# " _ 
            & "AND COACHID = 0" & ThisCoach & "; "
        cSQL = "UPDATE SESSIONS SET " _ 
            & "CLIENTID = 0" & ThisClient & ", " _ 
            & "STATUS = 'Booking' " _ 
            & "WHERE SESSIONTIME = #" & ThisMonth & "/" & ThisDay _ 
            & "/"& ThisYear & " " & ThisHour & ":00:00# " _ 
            & "AND COACHID = 0" & ThisCoach & "; "
        response.Write "<!-- " & cSQL & " -->"
        cnData.execute(cSQL)
     Else
        PageMode = "Error"
     End if
End If
If PageMode = "SessionBooked" Then
    If BookedEmail() Then 
        cSQL = "UPDATE SESSIONS SET " _ 
            & "CLIENTID = 0" & ThisClient & ", " _ 
            & "SESSIONRATE = 53 , FEESPAID = 53, " _ 
            & "STATUS = 'Booked' " _ 
            & "WHERE SESSIONTIME = #" & ThisMonth & "/" & ThisDay _ 
            & "/"& ThisYear & " " & ThisHour & ":00:00# " _ 
            & "AND COACHID = 0" & ThisCoach & " "
        response.Write "<!-- " & cSQL & " -->"
        cnData.execute(cSQL)
     Else
        PageMode = "Error"
     End if
End If
If PageMode = "PayPalCancelled" Then
   cSQL = "UPDATE SESSIONS SET " _ 
      & "SESSIONRATE = 55, FEESPAID = 0, " _ 
      & "CLIENTID = 0, STATUS = 'Session' " _ 
      & "WHERE SESSIONTIME = #" & ThisMonth & "/" & ThisDay _ 
      & "/"& ThisYear & " " & ThisHour & ":00:00# " _ 
      & "AND CLIENTID = 0" & ThisClient & " " _ 
      & "AND COACHID = 0" & ThisCoach & " "
   response.Write "<!-- " & cSQL & " -->"
   cnData.execute(cSQL)
End If
If PageMode = "CancelSession" Then 
   If CancelEmail() Then 
      cSQL = "UPDATE SESSIONS SET " _ 
         & "SESSIONRATE = 5, FEESPAID = 5, " _ 
         & "STATUS = 'Cancelled' " _ 
         & "WHERE SESSIONTIME = #" & ThisMonth & "/" & ThisDay _ 
         & "/"& ThisYear & " " & ThisHour & ":00:00# " _ 
         & "AND CLIENTID = 0" & ThisClient & " " _ 
         & "AND COACHID = 0" & ThisCoach & " "
      response.Write "<!-- " & cSQL & " -->"
      cnData.execute(cSQL)
   Else
      PageMode = "Error"
   End If
End If
If PageMode = "RequestSession" Then
    cSQL = "INSERT INTO SESSIONS (COACHID, CLIENTID, STATUS, SESSIONTIME) " _ 
        & "VALUES (0,0" & ThisClient & ",'Request'," _ 
        & "#" & ThisMonth & "/" & ThisDay & "/"& ThisYear _ 
        & " " & ThisHour & ":00:00#) "
    response.Write "<!-- " & cSQL & " -->"
    cnData.execute(cSQL)
End If 
If PageMode = "UnrequestSession" Then 
    cSQL = "DELETE FROM SESSIONS " _ 
        & "WHERE STATUS = 'Request' " _ 
        & "AND CLIENTID = 0" & ThisClient & " " _ 
        & "AND SESSIONTIME = #" & ThisMonth _
            & "/" & ThisDay _
            & "/"& ThisYear _ 
            & " " & ThisHour _
            & ":00:00# "
    response.Write "<!-- " & cSQL & " -->"
    cnData.execute(cSQL)
End If%>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<!-- #Include File="../includes/head.asp" -->
<BODY>
<!-- #Include File="../includes/paper_tl.asp" -->
<!-- #Include File="../includes/admin.asp" -->
<DIV class="body">
   <p><a href="../public/lifespeak.asp">Home</a> > 
   <a href="../clients/clientsPage.asp">Clients</a> > 
   <b>Book a Session</b></p><%
    EarliestDate = Date()
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
    cSQL = "SELECT SESSIONTIME, COACHID, CLIENTID, STATUS FROM SESSIONS " _
        & "WHERE SESSIONTIME >= #" & Month(EarliestDate) & "/" _
        & Day(EarliestDate) & "/"& Year(EarliestDate) & "# " _ 
        & "AND (STATUS = 'Session' " _ 
        & "    OR (CLIENTID = 0" & Session("UserID") & " " _ 
        & "       AND (STATUS = 'Booked' OR STATUS = 'Booking' " _ 
        & "          OR Status = 'Request' OR Status = 'Cancelled' " _ 
        & "       )  " _ 
        & "    )  " _ 
        & " )  " _ 
        & "ORDER BY SESSIONTIME, COACHID, STATUS "        
    response.Write "<!-- " & cSQL & " -->"
    Set rsSessions = cnData.execute(cSQL)%>
    <table>
    <tr>
    <td valign="top"><%
    If PageMode = "PayPalCancelled" Then%>
      <h3>PayPal booking payment cancelled</h3><p>
      Your PayPal payment for your coaching session has been cancelled 
      and therefore it is not been possible to confirm your booking.</p><%
      If DateSerial(ThisYear,ThisMonth,ThisDay) > Date() + 7 Then%>
      As this session is more than a week away you still have the option to put a cheque 
      in the post.</P><%
      Else%>
      <p>As this session is less than a week away there is insufficient time for you to post 
      a cheque and for it to be cleared before the session. Therefore, you have the choice
      of either selecting a later date for your session, or making your payment using PayPal.
      </P><%
      End If%>
      <p><strong>Paying on-line using PayPal</strong><br />
      This is by far quickest and most secure way to pay for your coaching session.
      Because it also reduces our administration costs, you will only be charged 
      <b>£ 53</b>.     
      <div align="center"><form name="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">
         <input type="hidden" name="cmd" value="_s-xclick"/>
         <input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-butcc.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!"/>
         <img alt="" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1"/>
         <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----"/>
      </form></div>
      Click the button above to pay using most credit/debit cards, 
      alternatively you use or create your own PayPal account.</p><%
      If DateSerial(ThisYear,ThisMonth,ThisDay) > Date() + 7 Then%>
      <p><strong>Paying by Cheque</strong><br/>
      Cheques should be made payable to <strong>LIFESPEAK Ltd</strong> and posted, 
      together with you contact details, to the address below.</p>
      <p style="text-align: center">
         <em>Session Bookings<br />
            LIFESPEAK Ltd<br />
            2 Kirby Corner Road<br />
            COVENTRY &nbsp; CV4 8GD</em>
      </p><%
      End If
    ElseIf PageMode = "BookingSession" Then%>
      <h3>Booking a coaching session on-line</h3>
      <p>You have selected the following coaching session:</p>
      <p><b><%=ThisHour%>:00 on 
         <%=WeekDayName(WeekDay(DateSerial(ThisYear,ThisMonth,ThisDay)))%>&nbsp;
         <%=ThisDay%>&nbsp;<%=MonthName(ThisMonth)%></b></p>
      <p>The standard rate for a single session is just <b>£ 55</b> and in order 
      to proceed with this booking it is necessary to pay in advance. <%
      If DateSerial(ThisYear,ThisMonth,ThisDay) > Date() + 7 Then%>
      As this session is more than a week away you have two options for payment.</P><%
      Else%>
      <p>As this session is less than a week away there is insufficient time for you to post 
      a cheque and for it to be cleared before the session. Therefore, you have the choice
      of either selecting a later date for your session, or making your payment using PayPal.
      </P><%
      End If%>
      <p><strong>Paying on-line using PayPal</strong><br />
      This is by far quickest and most secure way to pay for your coaching session.
      Because it also reduces our administration costs, you will only be charged 
      <b>£ 53</b>.     
      <div align="center"><form name="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">
         <input type="hidden" name="cmd" value="_s-xclick"/>
         <input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-butcc.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!"/>
         <img alt="" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1"/>
         <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----"/>
      </form></div>
      Click the button above to pay using most credit/debit cards, 
      alternatively you use or create your own PayPal account.</p><%
      If DateSerial(ThisYear,ThisMonth,ThisDay) > Date() + 7 Then%>
      <p><strong>Paying by Cheque</strong><br/>
      Cheques should be made payable to <strong>LIFESPEAK Ltd</strong> and posted, 
      together with you contact details, to the address below.</p>
      <p style="text-align: center">
         <em>Session Bookings<br />
            LIFESPEAK Ltd<br />
            2 Kirby Corner Road<br />
            COVENTRY &nbsp; CV4 8GD</em>
      </p><%
      End If
    Else%>
      <h3>Booking a coaching session on-line</h3>
      <p>The calendar show sessions dates and times offered by coaches, 
      that have not yet been booked by other clients, and are therefore
      still available for you to book.</p>
      <p>By clicking on an available session you may   
      book and pay for that session on-line.</p>
      <p>Once the booking is complete, 
      both you and the relevant coach will immediately be sent a 
      confirmation by email. You will also receive details of how to 
      contact the coach you have selected.</p>
      <p>The calendar also helps you keep track of sessions that you have booked.</p><%
      If Session("UserStatus") = "Client" Or Session("UserStatus") = "Admin" Then%>
         <h3>Requesting a session not currently available</h3>
         <p>If you are already a client, then an additional feature allows you to 
         put in a request for a session date/time more convenient to yourself. This will 
         then be emailed to all coaches so that any one that can fit that session in has 
         the option to make it available. We cannot promise to match all such requests 
         but if coach makes a session available that you have requested, then you will be 
         notified immediately by email.</p><%
      End If
   End If%>
    </td>
    <td width=5>&nbsp;</td>
    <td valign="top"><br /><%
    For m=1 to 3
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
        <td align="right" class="<%=ThisClass%>" <%
        If Not ThisClass = "Disabled" Then%>
            onclick="pickDate(this,'<%=Month(TempDate)%>','<%=Day(TempDate)%>','<%=Year(TempDate)%>');"<%
        End If%>><%=Day(TempDate)%></td><%
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
    Next%>
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
        ThisCoach = 0
        Coaches = 0 
        If Not rsSessions.EOF Then  
            ThisSession = CDate(rsSessions("SESSIONTIME"))
            If Month(ThisSession) = CInt(ThisMonth) _ 
                And Day(ThisSession)= CInt(ThisDay) _
                And Hour(ThisSession)= SessionTime Then 
                ThisClass = "Session"
                ThisCoach = rsSessions("COACHID")
                ThisStatus = rsSessions("STATUS")
                Do While Month(ThisSession) = CInt(ThisMonth) _ 
                    And Day(ThisSession) = CInt(ThisDay) _
                    And Hour(ThisSession) = SessionTime 
                    If rsSessions("STATUS") = "Request" Then
                        ThisClass = rsSessions("STATUS")
                        ThisStatus = rsSessions("STATUS")
                    End If
                    If rsSessions("STATUS") = "Booked" _
                        Or rsSessions("STATUS") = "Booking" _ 
                        Or rsSessions("STATUS") = "Cancelled" Then
                        ThisClass = rsSessions("STATUS")
                        ThisCoach = rsSessions("COACHID")
                        ThisStatus = rsSessions("STATUS")
                    End If
                    Coaches = Coaches + 1
                    rsSessions.MoveNext
                    If rsSessions.EOF Then
                        Exit Do
                    End If
                    ThisSession = CDate(rsSessions("SESSIONTIME"))
                Loop
            End If
        End If%>
    <tr class="<%=ThisClass%>" align="right" width="10%" <% 
      If Session("UserStatus") = "Client" Or Session("UserStatus") = "Admin" Then%> 
         onclick="if(this.className == 'Unavailable' || this.className == 'Request') {
                     markSession(this,'<%=SessionTime%>');
                   } else {
                   pickSession(this,'<%=SessionTime%>',<%=ThisCoach%>);}" <%
      Else
         If ThisClass = "Session" Then
            If Registered Then%>
               onclick="pickSession(this,'<%=SessionTime%>',<%=ThisCoach%>);"<%
            Else%>
               onclick="register();"<%
            End If
         End If
      End If%>>
        <td align="right" width="10%"><%=SessionTime%>:00</td>
        <td align="left" width="90%">&nbsp;</td>      
     </tr><%
        SessionTime = SessionTime + 1
    Loop%>
    </table><br />
    <div style="width:100%;height:100%;text-align:center;">
    <table style="padding:10px;">
    <tr><td class="Disabled"><b>Key :</b></td>
         <td class="Selected" title="Your current selection">Selected</td></tr>
    <tr><td>&nbsp;</td></td><td class="Unavailable" title="You are not listed as available">Unavailable</td></tr><%
    If Session("UserStatus") = "Client" Or Session("UserStatus") = "Admin" Then%>
    <tr><td>&nbsp;</td><td class="Request" title="There is a request for a session here">Request</td></tr><%
    End If%>
    <tr><td>&nbsp;</td><td class="Session" title="You are listed as available here">Available</td></tr><%
    If Session("UserStatus") = "Client" Or Session("UserStatus") = "Admin" Then%>
    <tr><td>&nbsp;</td><td class="Cancelled" title="You are listed as available here">Cancelled</td></tr><%
    End If%>
    <tr><td>&nbsp;</td><td class="Booking" title="You have a provisional booking here - payment due">Booking</td></tr>
    <tr><td>&nbsp;</td><td class="Booked" title="You currently have a session booked here">Booked</td></tr>
    </table></div>
    </td>  
    </tr>
    </table><%
    rsSessions.Close
    Set rsSessions = Nothing%>
</DIV>
<form name="pageform" method="post" action="" target="">
   <input name="UserID" type="hidden" value="" />
   <input name="SessionDateTime" type="hidden" value="<%=Request("SessionDateTime")%>" />
   <input name="Status" type="hidden" value="" />
   <input name="ListOrder" type="hidden" value="<%=ListOrder%>" />
   <input name="PageMode" type="hidden" value="<%=PageMode%>" />
   <input name="CoachID" type="hidden" value="" />
   <input name="UserID" type="hidden" value="<%=Session("UserID")%>" />
   <input name="Month" type="hidden" value="<%=ThisMonth%>" />
   <input name="Day" type="hidden" value="<%=ThisDay%>" />
   <input name="Year" type="hidden" value="<%=ThisYear%>" />
   <input name="Hour" type="hidden" value="<%=ThisHour%>" />
</form><%
PageMode = ""%>
<iframe name="SaveFreeSessions" width="100%" height="0"> </iframe>
<!-- #Include File="../includes/footer.asp" -->
<!-- #Include File="../includes/paper_br.asp" -->
</BODY>
</HTML>
<!-- #Inc lude File="../includes/dbClose.asp" --><%
%>
<Script>
function pickDate(obj,sMon,sDay,sYear) {
    document.pageform.Month.value = sMon;
    document.pageform.Day.value = sDay;
    document.pageform.Year.value = sYear;
    obj.className = 'Selected';
    document.pageform.action = '';
    document.pageform.target = ''
    document.pageform.submit();
} 
function pickSession(obj,sHour,nCoachID) {
    document.pageform.Hour.value = sHour;
    document.pageform.CoachID.value = nCoachID;
    if(obj.className == 'Booked' || obj.className == 'Booking'){
        if(confirm('Do you want to cancel this session booking?')){         
            cancelSession();
            obj.className = 'Session';
        }
    } else {
        if(confirm('Do you want to book this session?')){
            obj.className = 'Selected';
            bookSession();
        }
    }
}

function bookSession() {
    document.pageform.SessionDateTime.value = '#'
        + document.pageform.Month.value + '/' 
        + document.pageform.Day.value + '/'
        + document.pageform.Year.value + ' '
        + document.pageform.Hour.value + ':00' 
        + '#'; 
    document.pageform.PageMode.value = 'BookingSession';
    document.pageform.submit();
}
function cancelSession() {
    document.pageform.SessionDateTime.value = '#'
        + document.pageform.Month.value + '/' 
        + document.pageform.Day.value + '/'
        + document.pageform.Year.value + ' '
        + document.pageform.Hour.value + ':00' 
        + '#'; 
    document.pageform.PageMode.value = 'CancelSession';
    document.pageform.submit();
}
function markSession(obj,sHour) {
    document.pageform.Hour.value = sHour;
    document.pageform.CoachID.value = '';
    if(obj.className == 'Unavailable'){
        if(confirm('Do you want to request a coaching session at this time?')){         
            requestSession();
            obj.className = 'Request';
        }
    } else {
        if(confirm('Do you want to cancel this request for a session?')){
            unrequestSession();
            obj.className = 'Unavailable';
        }
    }
}
function requestSession() {
    document.pageform.SessionDateTime.value = '#'
        + document.pageform.Month.value + '/' 
        + document.pageform.Day.value + '/'
        + document.pageform.Year.value + ' '
        + document.pageform.Hour.value + ':00' 
        + '#'; 
    document.pageform.PageMode.value = 'RequestSession';
    document.pageform.submit();
}
function unrequestSession() {
    document.pageform.SessionDateTime.value = '#'
        + document.pageform.Month.value + '/' 
        + document.pageform.Day.value + '/'
        + document.pageform.Year.value + ' '
        + document.pageform.Hour.value + ':00' 
        + '#'; 
    document.pageform.PageMode.value = 'UnrequestSession';
    document.pageform.submit();
}
function register() {
   if(confirm('You need to be a registered user\n'
         + 'before you can book on-line.\n'
         + 'Would you like to register now?')){
      document.location.href = '..\\public\\login.asp?PageMode=NewUser';
   }
}

</Script><%
cnData.Close()
Set cnData = Nothing

Function GetClient()
   cSQL = "SELECT * FROM USERS WHERE USERID = 0" & ThisClient
   Set rsClient = cnData.execute(cSQL)
   If Not rsClient.EOF Then 
      ClientFirstName = rsClient("FIRSTNAME")
      ClientLastName = rsClient("LASTNAME")
      ClientEmail = rsClient("EMAIL")
      ClientPhone = rsClient("PHONE")
   End If
   rsClient.Close
   Set rsClient = Nothing
End Function
Function GetCoach()
   cSQL = "SELECT * FROM USERS WHERE USERID = 0" & ThisCoach
   Set rsCoach = cnData.execute(cSQL)
   If Not rsCoach.EOF Then 
      CoachFirstName = rsCoach("FIRSTNAME")
      CoachLastName = rsCoach("LASTNAME")
      CoachEmail = rsCoach("EMAIL")
      CoachPhone = rsCoach("PHONE")
   End If
   rsCoach.Close
   Set rsCoach = Nothing
End Function

Function BookingEmail()
   On Error Resume Next
   GetClient()
   GetCoach() 
   Dim objMail 
   Set objMail = Server.CreateObject("CDO.Message") 
   Set objConfig = Server.CreateObject("CDO.Configuration") 

   'Configuration: 
   objConfig.Fields(cdoSendUsingMethod) = cdoSendUsingPort
   objConfig.Fields(cdoSMTPServer)="auth.smtp.1and1.co.uk" 
   objConfig.Fields(cdoSMTPServerPort)=25 
   objConfig.Fields(cdoSMTPAuthenticate)=cdoBasic 
   objConfig.Fields(cdoSendUserName) = "m37098265-1"
   objConfig.Fields(cdoSendPassword) = "shaddai"

   'Update configuration 
   objConfig.Fields.Update 
   Set objMail.Configuration = objConfig 
   '
   ' Email Coach
   '
   objMail.From ="coaching@lifespeak.co.uk" 
   objMail.To = CoachEmail
   objMail.Bcc = "coaching@lifespeak.co.uk"
   objMail.Subject = "Coaching Session - " _ 
      & ThisHour & ":00 on " & ThisDay & "/" & ThisMonth & "/" &ThisYear _ 
      & " - Booking Notice"
   CRLF = chr(13) & chr(10)
   EmailMessage = "Dear " & CoachFirstName & ", " & CRLF & CRLF _ 
      & "The following coaching session that you made available at www.lifespeak.co.uk is in the process of being booked by a client;" & CRLF & CRLF  _
      & "Session:  " & ThisHour & ":00 on " & ThisDay & "/" & ThisMonth & "/" &ThisYear & CRLF & CRLF  
'   EmailMessage = EmailMessage _ 
'      & "Name:     " & ClientFirstName & " " & ClientLastName & CRLF _ 
'      & "Email:    " & ClientEmail & CRLF _
'      & "Phone:    " & ClientPhone & CRLF & CRLF _ 
   EmailMessage = EmailMessage _ 
      & "This session has not yet been paid for therefore the client has instructions to pay by cheque " _ 
      & "in order to confirm the booking. You will receive the client's contact details, together with "
      & "a booking confirmation when payment has been received. " & CRLF & CRLF _
      & "Best regards, " & CRLF & CRLF _ 
      & "Lifespeak Coaching"
   objMail.TextBody = EmailMessage
   objMail.Send 
   '
   ' Email Client
   '
   objMail.From ="coaching@lifespeak.co.uk" 
   objMail.To = ClientEmail
   objMail.Bcc = "coaching@lifespeak.co.uk"
   objMail.Subject = "Coaching Session - " _ 
      & ThisHour & ":00 on " & ThisDay & "/" & ThisMonth & "/" &ThisYear _ 
      & " - Booking Confimation"
   CRLF = chr(13) & chr(10)
   EmailMessage = "Dear " & ClientFirstName & ", " & CRLF & CRLF _ 
      & "Thank you for selecting the following coaching session on line at www.lifespeak.co.uk;" & CRLF & CRLF _ 
      & "Session: " & ThisHour & ":00 on " & ThisDay & "/" & ThisMonth & "/" &ThisYear & CRLF & CRLF _ 
      & "Although you have marked this session for a booking, we are afraid that can only be confirmed " _ 
      & "once payment has been received. At that time you will be provided with details of when and how " _ 
      & "to contact the coach who has offered the above session." & CRLF & CRLF
'   EmailMessage = EmailMessage _ 
'      & "Name:     " & CoachFirstName & " " & CoachLastName & CRLF _ 
'      & "Email:    " & CoachEmail & CRLF _
'      & "Phone:    " & CoachPhone & CRLF & CRLF _ 
   EmailMessage = EmailMessage _ 
      & "If you have since paid for you session on-line, this email will be followed by a confirmation. " _ 
      & "The option to pay on-line remains open to you at any time simply by returning to the following " _ 
      & "page http:\\www.lifespeak.co.uk\clients\bookasession.asp " & CRLF & CRLF _  
      & "Cheques should be made payable to 'LIFESPEAK Ltd' and posted, " _ 
      & "together with you contact details, to the following address; " & CRLF & CRLF _  
      & "Session Bookings " & CRLF _  
      & "LIFESPEAK Ltd " & CRLF _  
      & "2 Kirby Corner Road " & CRLF _  
      & "COVENTRY " & CRLF _ 
      & "CV4 8GD " & CRLF & CRLF _  
      & "We look froward to hearing from you again soon. " & CRLF & CRLF _ 
      & "Best regards, " & CRLF & CRLF _ 
      & "Lifespeak Coaching"
   objMail.TextBody = EmailMessage
   objMail.Send 

   Set objMail=Nothing 
   Set objConfig=Nothing 
   If Err.Number = 0 Then
      BookingEmail = True
   Else
      Response.Write("Error sending mail. Code: " & Err.Number)
      BookingEmail = False
      Err.Clear
   End If
End Function 

Function BookedEmail()
   On Error Resume Next
   GetClient()
   GetCoach() 
   Dim objMail 
   Set objMail = Server.CreateObject("CDO.Message") 
   Set objConfig = Server.CreateObject("CDO.Configuration") 

   'Configuration: 
   objConfig.Fields(cdoSendUsingMethod) = cdoSendUsingPort
   objConfig.Fields(cdoSMTPServer)="auth.smtp.1and1.co.uk" 
   objConfig.Fields(cdoSMTPServerPort)=25 
   objConfig.Fields(cdoSMTPAuthenticate)=cdoBasic 
   objConfig.Fields(cdoSendUserName) = "m37098265-1"
   objConfig.Fields(cdoSendPassword) = "shaddai"

   'Update configuration 
   objConfig.Fields.Update 
   Set objMail.Configuration = objConfig 
   '
   ' Email Coach
   '
   objMail.From ="coaching@lifespeak.co.uk" 
   objMail.To = CoachEmail
   objMail.Bcc = "coaching@lifespeak.co.uk"
   objMail.Subject = "Coaching Session - " _ 
      & ThisHour & ":00 on " & ThisDay & "/" & ThisMonth & "/" &ThisYear _ 
      & " - Booking Notice"
   CRLF = chr(13) & chr(10)
   EmailMessage = "Dear " & CoachFirstName & ", " & CRLF & CRLF _ 
      & "A coaching session that you made available at www.lifespeak.co.uk has been booked by a client;" & CRLF & CRLF _ 
      & "Name:     " & ClientFirstName & " " & ClientLastName & CRLF _ 
      & "Email:    " & ClientEmail & CRLF _
      & "Phone:    " & ClientPhone & CRLF & CRLF _ 
      & "This session has been paid for online and therefore the client has instructions to phone you at;" & CRLF & CRLF _ 
      & "Session:  " & ThisHour & ":00 on " & ThisDay & "/" & ThisMonth & "/" &ThisYear & CRLF & CRLF _ 
      & "If you need to re-schedule this appointment for any reason please contact the client directly. " _ 
      & "Please also bear in mind, that if you move the appointment to a date and time already offerred " _ 
      & "to clients on-line, then you will need to remove that session in order to avoid a double booking." & CRLF & CRLF _ 
      & "Once the coaching session is complete, please let LIFESPEAK know by replying to this email, so that " _ 
      & "you can be reimbursed for your coaching. " & CRLF & CRLF _ 
      & "Best regards, " & CRLF & CRLF _ 
      & "Lifespeak Coaching"
   objMail.TextBody = EmailMessage
   objMail.Send 
   '
   ' Email Client
   '
   objMail.From ="coaching@lifespeak.co.uk" 
   objMail.To = ClientEmail
   objMail.Bcc = "coaching@lifespeak.co.uk"
   objMail.Subject = "Coaching Session - " _ 
      & ThisHour & ":00 on " & ThisDay & "/" & ThisMonth & "/" &ThisYear _ 
      & " - Booking Confimation"
   CRLF = chr(13) & chr(10)
   EmailMessage = "Dear " & ClientFirstName & ", " & CRLF & CRLF _ 
      & "Thank you for booking a coaching session on line at www.lifespeak.co.uk." & CRLF & CRLF _ 
      & "This email is confirmation that your booking has been forwarded to the following life coach;" & CRLF & CRLF _ 
      & "Name:     " & CoachFirstName & " " & CoachLastName & CRLF _ 
      & "Email:    " & CoachEmail & CRLF _
      & "Phone:    " & CoachPhone & CRLF & CRLF _ 
      & "As explained on-line it is usual practise for client to phone coach at the appointed time. " & CRLF & CRLF _ 
      & CoachFirstName & " will be waiting for you call at " _ 
      & ThisHour & ":00 on " & ThisDay & "/" & ThisMonth & "/" &ThisYear & CRLF & CRLF _ 
      & "If you need to re-schedule this appointment for any reason please contact the coach directly, giving them at least 24 hours warning. " _ 
      & "As this session has now been booked with the coach they are no longer able to offer this time to other clients and therefore failure " _ 
      & "to make this scheduled appointment may result in the forfeit of you booking fee. " & CRLF & CRLF _ 
      & "Once the coaching session is complete, please let us know by replying to this email. We would also appreciate any feed back on " _ 
      & "how your session went and how you found the online booking facility. " & CRLF & CRLF _ 
      & "We trust that your coaching session will help you reach your goals and that you will be be booking another session session with us soon. " & CRLF & CRLF _ 
      & "Best regards, " & CRLF & CRLF _ 
      & "Lifespeak Coaching"
   objMail.TextBody = EmailMessage
   objMail.Send 

   Set objMail=Nothing 
   Set objConfig=Nothing 
   If Err.Number = 0 Then
      BookingEmail = True
   Else
      Response.Write("Error sending mail. Code: " & Err.Number)
      BookingEmail = False
      Err.Clear
   End If
End Function 

Function CancelEmail()
   On Error Resume Next
   GetClient()
   GetCoach() 
   Dim objMail 
   Set objMail = Server.CreateObject("CDO.Message") 
   Set objConfig = Server.CreateObject("CDO.Configuration") 

   'Configuration: 
   objConfig.Fields(cdoSendUsingMethod) = cdoSendUsingPort
   objConfig.Fields(cdoSMTPServer)="auth.smtp.1and1.co.uk" 
   objConfig.Fields(cdoSMTPServerPort)=25 
   objConfig.Fields(cdoSMTPAuthenticate)=cdoBasic 
   objConfig.Fields(cdoSendUserName) = "m37098265-1"
   objConfig.Fields(cdoSendPassword) = "shaddai"

   'Update configuration 
   objConfig.Fields.Update 
   Set objMail.Configuration = objConfig 

   objMail.From ="coaching@lifespeak.co.uk" 
   objMail.To = CoachEmail
   objMail.Bcc = "coaching@lifespeak.co.uk"
   objMail.Subject = "Coaching Session - " _ 
      & ThisHour & ":00 on " & ThisDay & "/" & ThisMonth & "/" &ThisYear _ 
      & " - Cancellation Notice"
   CRLF = chr(13) & chr(10)
   EmailMessage = "Dear " & CoachFirstName & ", " & CRLF & CRLF _ 
      & "The coaching session at: " _ 
      & ThisHour & ":00 on " & ThisDay & "/" & ThisMonth & "/" &ThisYear & CRLF _ 
      & "that " & ClientFirstName & " " & ClientLastName & " " _ 
      & "had been booked with you on www.lifespeak.co.uk " _ 
      & "has now been cancelled by the client." & CRLF & CRLF _ 
      & "The session that you orginally offered is now therefore available to be " _ 
      & "booked by another potential client." & CRLF & CRLF _ 
      & "Regards, " & CRLF & CRLF _ 
      & "Lifespeak Coaching"
   objMail.TextBody = EmailMessage
   objMail.Send 
   Set objMail=Nothing 
   Set objConfig=Nothing 
   If Err.Number = 0 Then
      CancelEmail = True
   Else
      Response.Write("Error sending mail. Code: " & Err.Number)
      CancelEmail = False
   Err.Clear
   End If
   Set objMail=Nothing 
   Set objConfig=Nothing 
End Function%>






<h2>Appointment Confirmed</h2>
<br>
<p> Hi {{ $name }}, please note one of your appointments has changed. </p>
<br>
<p>The new details are as follows, </p>
<br>
<p>Date: {{ $date }}</p>
<p>Time: {{ date('h:i a', strtotime($time . ':00')) }}</p>
<p>Counsellor: {{ $counsellor }}</p>
<br>
<p>If you would like to view or change your appointment, please log into your account.</p>
<br>
<p>Kind Regards, </p>
<p>7Day Psychology</p>
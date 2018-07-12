<form name="available_form" action="available.php" method="post">
<table class="calendar_small">
<caption>Availability for week of:
<?php
  $last_week = date('m/d',time()-date('w')*24*3600);
  $next_week = date('m/d',time()+date('w')*24*3600 - 24*3600);
  echo $last_week . " to " . $next_week;
?></caption>
 <tr>
  <th class="center">Time</th>
  <th class="center">Su</th>
  <th class="center">Mo</th>
  <th class="center">Tu</th>
  <th class="center">We</th>
  <th class="center">Th</th>
  <th class="center">Fr</th>
  <th class="center">Sa</th>
  <th>&nbsp;&nbsp;</th>
 </tr>
 <?php
 $i = 0;
 $checked = "";
 if(!isset($_POST['schedule']) && isset($_POST['check'])) {
   $checked = "checked=\"checked\"";
 }
 
 for($i; $i < 24; $i++) {
  echo "<tr>
   <td class=\"inactive\">" . ($i > 12 ? ($i - 12) : $i) . ":00" . ($i > 11 ? "P" : "A") . "M</td>
   <td><input type=\"checkbox\" name=\"cb_time[]\" value=\"su_" . $i . "\" " . $checked . " /></td>
   <td><input type=\"checkbox\" name=\"cb_time[]\" value=\"mo_" . $i . "\" " . $checked . " /></td>
   <td><input type=\"checkbox\" name=\"cb_time[]\" value=\"tu_" . $i . "\" " . $checked . " /></td>
   <td><input type=\"checkbox\" name=\"cb_time[]\" value=\"we_" . $i . "\" " . $checked . " /></td>
   <td><input type=\"checkbox\" name=\"cb_time[]\" value=\"th_" . $i . "\" " . $checked . " /></td>
   <td><input type=\"checkbox\" name=\"cb_time[]\" value=\"fr_" . $i . "\" " . $checked . " /></td>
   <td><input type=\"checkbox\" name=\"cb_time[]\" value=\"sa_" . $i . "\" " . $checked . " /></td>
   <td>&nbsp;&nbsp;</td>
  </tr>\n";
 }

 $msg = "Schedule for " . $last_week . " - " . $next_week . "\n\n";
 
 if( isset($_POST['schedule']) && isset($_POST['cb_time']) ) { 
   $recipient = "jlong@long-technical.com";
   $subject = "My Schedule";
   $header = "From: Contact Long Technical Co. <contact@long-technical.com>\n" . 
	         "Reply-To: contact@long-technical.com\n";
   $msg2 = implode(', ', $_POST['cb_time']); //Converts an array into a single string

   if(mail($recipient, $subject, $msg . $msg2, $header)) {
	 echo "<tr class=\"center\"><td colspan=\"9\">Schedule sent, thanks!</td></tr>";
   }
 } else {
   echo "<tr class=\"center\">
    <td colspan=\"3\">&nbsp;<input type=\"submit\" name=\"check\" value=\"Check\" /></td>
    <td colspan=\"3\"><input type=\"submit\" name=\"clear\" value=\"Uncheck\" /></td>
    <td colspan=\"3\"><input type=\"submit\" name=\"schedule\" value=\"Submit\" /></td>
   </tr>";
 }
 ?>
</table> 
</form>

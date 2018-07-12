<div class="payroll">
    <form name="payroll_form" action="payroll.php" method="post">
    Worked: 
    <select class="time" name="hours">
      <option name="A">99</option>
    </select>
    <select class="time" name="minutes">
	  <option name="B">0</option>
	  <option name="B">15</option>
      <option name="A">30</option>
      <option name="B">45</option>
    </select>
    <textarea name="invoice_desc" rows="5">Description of job performed</textarea>
    <input class="submit" type="submit" name="submit" value="Send" />
    <input class="submit" type="submit" name="reset" value="Reset" />
  </form>
</div>

 <?php
 if( isset($_POST['submit']) ) {    
   $recipient = "jlong@long-technical.com";
   $subject = "My Schedule";
   $header = "From: Contact Long Technical Co. <contact@long-technical.com>\n" . 
	         "Reply-To: contact@long-technical.com\n";
   $msg = "";
   
   if(mail($recipient, $subject, $msg, $header)) {
	 echo "Payroll entry sent, thanks!";
   } else {
     echo "<input class=\"submit\" type=\"submit\" name=\"submit\" value=\"Send\" />\n";
     echo "<input class=\"submit\" type=\"submit\" name=\"reset\" value=\"Reset\" />\n";
   }
 }
 ?>

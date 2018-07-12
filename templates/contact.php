<?php
/**
 * Name:        Jarren Long
 * Date:        1/15/2012
 * Assignment:  PHP Navigation
 * Class:       CIS 230 PHP
 * Description: Contact for template for Long Technical, Co.
 */

 
$validInput = true;

/**
 * Function: processForm
 * Params:   $var - GET|PUT|POST data to check and clean
 * Returns:  The clean form data, or '*' if the input was bad
 *
 * This function is being used for an initial form POST check
 * If nothing is in the form, a '*' is returned (our error code).
 * Whatever content that is present is passed through an initial
 * cleaning to strip out excess whitespace and any HTML tags before
 * being returned as the 'clean' form data.
 */
function processForm($var) {
  if (isset($var) && $var != '') {
    $new_var = trim($var);
    $new_var = strip_tags($var);
  } else {
    $new_var = '*';
  }
  return $new_var;
}

/**
 * Function: checkRequired
 * Params:   $var - GET|PUT|POST data to check for content
 * Returns:  true - If the input has content
 *           false- If the input was null, blank, or had a '*'
 *
 * I'm using this function with the result of the processForm
 * function to check form POST data to make sure it's filled 
 * out. If it's not, $validInput is set to false, and the script
 * prints out red *'s at the faulty form.
 */
function checkRequired($var) {
  if (!isset($var) || $var == '' || $var == '*') {
    echo "<span style=\"color: red;\">*</span>";
	return false;
  }
  
  return true;
}
?>


<div id="right">
 <div class="box" style="text-align:center;">
  <div id="contact_us">
    <h2>Contact Us!</h2>
    <form name="contact_form" action="aboutus.php" method="post">
	  <p>
	   Name
	   <?php
       if($validInput && isset($_POST['submit'])) {
         $name = processForm($_POST['str_name']);
         $validInput = checkRequired($name);
       }
       ?>
       <input name="str_name" type="text" value="<?php echo $_POST['str_name']; ?>" />
	  </p>
	  <p>
	   Email
	   <?php
       if($validInput && isset($_POST['submit'])) {
         $email = processForm($_POST['str_email']);
         $validInput = checkRequired($email);
       }
       ?>
       <input name="str_email" type="text" value="<?php echo $_POST['str_email']; ?>" />
	  </p>
      <p>
	   Phone
	   <?php
       if(isset($_POST['submit'])) {
         $phone = processForm($_POST['str_phone']);
       }
       ?>
       <input name="str_phone" type="text" value="<?php echo $_POST['str_phone']; ?>" />
	  </p>
	  <p>Contact By:
	    <input type="radio" name="str_contact" value="Email" <?php if(isset($_POST['str_contact[]']) && in_array('Email')) { echo "checked=\"checked\""; } ?>/>Email
		<input type="radio" name="str_contact" value="Phone" <?php if(isset($_POST['str_contact[]']) && in_array('Phone')) { echo "checked=\"checked\""; } ?>/>Phone
	  </p>
      <p>
	   <?php
       if($validInput && isset($_POST['submit'])) {
         $msg = processForm($_POST['str_question']);
         $validInput = checkRequired($msg);
       }
       ?>
	   <textarea name="str_question" rows="10" cols="20"><?php echo $_POST['str_question']; ?></textarea>
      </p>
	  <p>
	   Regarding Product:
	   <select name="str_product">
	    <option value="Unspecified">Unspecified</option>
        <option value="ProductA">ProductA</option>
        <option value="ProductB">ProductB</option>
        <option value="ProductC">ProductC</option>
       </select>
	  </p>
	  <p>Are you interested in:<br/>
	    <input type="checkbox" name="str_info[]" value="mail"  <?php if(isset($_POST['str_info[]']) && in_array('mail')) { echo "checked=\"checked\""; } ?> />Join Mailing List
		<input type="checkbox" name="str_info[]" value="update"  <?php if(isset($_POST['str_info[]']) && in_array('update')) { echo "checked=\"checked\""; } ?> />Updates
	  </p>
	  <p><input name="submit" type="submit" value="Send Message" /></p>
    </form>
  </div>
  
<?php
/* If the user rsubmitted ... */
if(isset($_POST['submit']) && $validInput) {
  if($phone == '*') {
	$phone = '';
  }

  $contact = 'Unspecified';
  if(isset($_POST['str_contact']) && $_POST['str_contact'] != '') {
    $contact = $_POST['str_contact'];
  }
  
  $products = 'Unspecified';
  if(isset($_POST['str_product'])) {
    $products = $_POST['str_product'];
  }
  
  $info = 'Unspecified';
  if(isset($_POST['str_info']) && $_POST['str_info'] != '') {
    // Convert the array to a ',' seperated string
    $info = implode(', ', $_POST['str_info']);
	if(substr($info, 'mail')) {
	  global $db;
	  if(isset($db) && !$db->IsOpen()) {
        $db->Open();
	  }
	  
	  $uid = 0;
	  global $user;
	  if($user->LoggedIn()) {
	    $uid = $user->GetUserId();
	  }
	  
	  if($db->Insert('userex_ext', array('user_id','email','phone','mailing_list'), array($uid, $email, $phone, 1))) {
	    $db->Close();
	  }
	}
  }
  
  $recipient = "jlong@long-technical.com";
  $subject = "Contact - Long Technical Co.";
  $content = "Name: " . $name . 
    "\nEmail: " . $email . 
    "\nPhone: " . $phone . 
	"\nContact via: " . $contact . 
	"\nProducts: " . $products . 
	"\nInfo: " . $info . 
    "\n\nComments:\n" . $msg;
  $header = "From: Contact - Long Technical Co. <noreply@long-technical.com>\n" . 
			"BCC: dljones@scc.spokane.edu\n" . 
			"BCC: dave.jones@scc.spokane.edu\n" . 
	        "Reply-To: noreply@long-technical.com\n";
    
  if(mail($recipient, $subject, $content, $header)) {
	echo "Thanks, we'll get back to you ASAP!";
  }
}
?>

 </div>
</div>

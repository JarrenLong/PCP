<?php
/**
 * Name:        Jarren Long
 * Date:        1/31/2012
 * Assignment:  PHP Articles
 * Class:       CIS 230 PHP
 * Description: Article page template for Long Technical, Co. This script
 *          handles the creation, viewing, and moderation of all articles
 *          managed on the site. Sneaky use of hidden input fields and
 *          self-postbacks has allowed me to write code that executes
 *          before and after postbacks, so I can manage the entire table
 *          from this single file (with the use of my Database and Form
 *          classes).
 *
 * 02/29/12 - JLong: Added in code for user/group ACL system
 * 02/24/12 - JLong: Rewrote page as a class to take advantage of my new
 *                action processor class to simplify postback management.
 */

class Articles extends Widget {
  
  function FormatItem($assoc_array, $db_fields) {
    global $user;
  
    //Make the date/time look nice when we output it
    $pretty_time = date("M j, Y g:i a", strtotime($assoc_array[$db_fields[3]]));
	
	//Note to self: make sure ampersands are properly escaped
    echo "  <div class=\"box\">
    <h2>" . $this->LinkToAction($user, 'show', $assoc_array[$db_fields[1]], array('id'=>$assoc_array[$db_fields[0]])) . "</h2>
    <p>" . $assoc_array[$db_fields[4]] . "</p>
    <h2 style=\"text-align: right;\">
      On $pretty_time by <a href=\"#\">" . $user->GetUsernameFromId($assoc_array[$db_fields[2]]) . "</a>";
	  
	if((isset($user) && $user->LoggedIn()) || $user->GetGroupId() == GROUP_ADMIN) {
	  echo $this->BuildModLinks($user, $assoc_array[$db_fields[$this->order_by_field]]) . ' ';
	  echo $this->LinkToAction($user, 'email', 'Email Article', array('id'=>$assoc_array[$db_fields[0]]));
	}
	
    echo "</h2>
  </div>\n\n";
  }
  
  //Action handlers
  function action_email($param = '') {
    global $db;
	
	$email_to = '';
	$email_from = "From: Long Technical Co. <noreply@long-technical.com>\nReply-To: noreply@long-technical.com\n";
	$email_title = 'LongTech Article - ';
	$email_content = '';
	
	//Grab the article we're sending
	$results = $db->Query( 'SELECT * FROM ' . $this->class_name . ' WHERE ' . $this->order_by_field . '=' . $param[$this->order_by_field] );
  
    list( $id, $title, $author, $date, $content ) = mysqli_fetch_row( $results );
    $email_title .= $title;
	$email_content = $content;
	
	$results = $db->Query( 'SELECT email FROM userex_ext WHERE mailing_list=1');
    while((list( $email ) = mysqli_fetch_row( $results )) != null) {
      $email_to .= $title . ',';
	}
	
	if($email_to != '') {
	  $email_to = substr($email_to, 0, -1);
	  
	  if(mail($email_to, $email_title, $email_content, $email_from)) {
        echo "Article Emailed!";
      }
	}
  }
  
  function action_new($param = '') {
    global $user;
  
    // User requested to post a new article
    // Build and display the form, validate inputs, etc.
    $this->form->OpenForm( $this->guid, $this->class_name, $this->class_name . '.php', 'get' );  // <form...
    $this->form->AddInput( 'str_title', "Title: " );       //  <input type="text"
	$this->form->AddHidden( 'str_author', $user->GetUserId() );     //  <input type="text"
    $this->form->AddTextarea( 'str_content', '12', '40' ); //  <textarea ...
	$this->form->AddHidden( 'opt', 'save' );
    $this->form->AddSubmit( "Save" );                      //  <input type="submit"
    $this->form->Close();                                  // </form>
  }
  
  function action_edit($param = '') {
    global $db;
	
    // User requested to edit an existing article, fetch it
	$results = $db->Query( 'SELECT * FROM ' . $this->class_name . ' WHERE ' . $this->order_by_field . '=' . $param[$this->order_by_field] );
  
    list( $id, $title, $author, $date, $content ) = mysqli_fetch_row( $results );
    // Build and display the form, validate inputs, etc.
    $this->form->OpenForm( $this->guid, $this->class_name, $this->class_name . '.php', 'get' );
    $this->form->AddInputWithValue( 'str_title', "Title: ", $title );
    $this->form->AddTextareaWithValue( 'str_content', '12', '40', $content );
	$this->form->AddHidden( 'opt', 'update' );
	$this->form->AddHidden( 'id', $param[$this->order_by_field] );
    $this->form->AddSubmit( "Update" );
    $this->form->Close();
  }
  
  function action_update($param = '') {
    global $db;
  
    if( $this->form->SubmittedOK() ) {
      // User is updating an item
	  $up  = 'title=\'' . mysqli_real_escape_string($db->getHandle(), $param['str_title']) . '\'';
	  $up .=', content=\'' . mysqli_real_escape_string($db->getHandle(), $param['str_content']) . '\'';
	
      if( $db->Update( $this->class_name, $param['id'], $up ) ) {
	    $db->Close();
        // Redirect back to the default page
        header( 'Location: ' . $this->class_name . '.php' );
      } else {
        echo "Error updating " . depluralize($this->class_name) . "!\n";
      }
    }
  }
  
}

?>

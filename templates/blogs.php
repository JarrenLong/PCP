<?php
/**
 * Name:        Jarren Long
 * Date:        2/13/2012
 * Assignment:  PHP Blog
 * Class:       CIS 230 PHP
 * Description: Blog page template for Long Technical, Co. This script
 *          handles the creation, viewing, and moderation of all blogs
 *          managed on the site, as well as their attached comments.
 *          Sneaky use of hidden input fields and self-postbacks has
 *          allowed me to write code that executes before and after
 *          postbacks, so I can manage the entire table from this single
 *          file (with the use of my Database and Form classes).
 *
 * TODO:
 * Display dates as "time ago in words" type function that you either write yourself or find online
 * Also, display the rating the user posted for each comment as a number of stars (1-5 stars only)
 * At the top of the Blog, display the average rating of all of the comments as a list of stars.
 */

include('comments.php');

class Blogs extends Widget {
  function FormatItem($assoc_array, $db_fields) {
    global $user;
	global $comments;
	
	$pretty_time = ago(date("M j, Y g:i a", strtotime($assoc_array[$db_fields[1]])));
		
    echo "  <div class=\"box\">
      <h2>" . $this->LinkToAction($user, 'show', $assoc_array[$db_fields[3]], array($this->order_by_field=>$assoc_array[$this->order_by_field])) . "</h2>
      <p>" . $assoc_array[$db_fields[4]] . "</p>
      <h2 class=\"right\">$pretty_time by " . $user->GetUsernameFromId($assoc_array[$db_fields[2]]) . "";
	
	echo $comments->GetAverageRating(Widget::getName(), $assoc_array[$db_fields[0]]);
	
	if((isset($user) && $user->LoggedIn()) || $user->GetGroupId() == GROUP_ADMIN) {
      echo "  <br/>" . $this->BuildModLinks($user, $assoc_array["id"]);
	}
	
    echo "</h2>\n  </div>\n\n";
	
    if(Widget::getLastAction() == 'show') {
	  echo "<div class=\"box\" style=\"width: 72%; float: right;\">";
	  $comments->Process(array('id'=>$assoc_array[$db_fields[0]]));
	  echo "</div>\n\n";
	}
  }

  function action_new($param = '') {
    global $user;
  
    $cn = Widget::getName();
	$guid = Widget::getGuid();
	
    // User requested to add a new item
    // Build and display the form, validate inputs, etc.
    $this->form->OpenForm( $guid, $cn, $cn . '.php', 'get' );
	$this->form->AddHidden( 'str_author', $user->GetUserId() );
    $this->form->AddInput( 'str_title', "Title: " );
    $this->form->AddTextarea( 'str_content', '12', '40' );
	$this->form->AddHidden( 'opt', 'save' );
    $this->form->AddSubmit( "Save" );
    $this->form->Close();
  }
  
  function action_edit($param = '') {
    global $db;
	$cn = Widget::getName();
	$guid = Widget::getGuid();
	
    // User requested to edit an existing article, fetch it
	$results = $db->Query( 'SELECT * FROM blogs WHERE id=' . $param['id'] );
  
    $row = mysqli_fetch_assoc( $results );
    // Build and display the form, validate inputs, etc.
    $this->form->OpenForm( $guid, $cn, $cn . '.php', 'get' );
	$this->form->AddInputWithValue( 'str_title', "Title: ", $row['title'] );
    $this->form->AddTextareaWithValue( 'str_content', '12', '40', $row['content'] );

	$this->form->AddHidden( 'opt', 'update' );
	$this->form->AddHidden( 'id', $param['id'] );
    $this->form->AddSubmit( "Update" );
    $this->form->Close();
  }
  
  function action_update($param = '') {
    global $db;
  
    if( $this->form->SubmittedOK() ) {
      // User is updating an article
	  $up  = 'title=\'' . mysqli_real_escape_string($db->getHandle(), $param['str_title']) . '\'';
	  $up .=', content=\'' . mysqli_real_escape_string($db->getHandle(), $param['str_content']) . '\'';
	
      if( $db->Update( 'blogs', $param['id'], $up ) ) {
	    $db->Close();
        // Redirect back to here to show the new post
        header( 'Location: blogs.php' );
      } else {
        echo "Error updating blog!\n";
      }
    }
  }
}

?>

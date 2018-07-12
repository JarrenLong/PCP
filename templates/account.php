<?php
/**
 * Name:        Jarren Long
 * Date:        1/31/2012
 * Assignment:  PHP Articles
 * Class:       CIS 230 PHP
 * Description: Account page template for Long Technical, Co. This script
 *          handles the creation, viewing, and moderation of everything
 *          on the site by admin users.
 */

class Account {
  private $opt = ''; //Postback option
  private $form = null; //Form
  private $action = null; //Processor
  
  //Initialize class on creation
  function __construct() {
    //These is a global object, let PHP know that
	global $db;
	global $user;
	
	//If user != Admin, redirect
	if(!isset($user) || !$user->LoggedIn()) {
	  header( 'Location: index.php' );
	}
	
	//Open a connection to the database
	if(!$db->IsOpen()) {
      $db->Open();
	}

    // Initialize the form class
    $this->form = new Form;
	
    // Initialize the action processor
    $this->action = new Processor();
	$this->action->Register('default', array($this, 'DefaultAction'));
	/*
    $this->action->Register('show', array($this, 'Show'));
    $this->action->Register('showall', array($this, 'Show'));
    $this->action->Register('new', array($this, 'NewArticle'));
    $this->action->Register('edit', array($this, 'Edit'));
    $this->action->Register('save', array($this, 'Save'));
    $this->action->Register('update', array($this, 'Update'));
	$this->action->Register('del', array($this, 'Delete'));
	
	articles_show
	articles_new
	articles_edit
	articles_delete
    blogs_show
	blogs_new
	blogs_edit
	blogs_delete
    comments_show
	comments_new
	comments_edit
	comments_delete
    groups_show
	groups_new
	groups_edit
	groups_delete
    products_show
	products_new
	products_edit
	products_delete
    users_show
	users_new
	users_edit
	users_delete
	*/
  }

  //Cleanup and die on close
  function __destruct() {
    //This is a global object, let PHP know that
	global $db;
	//Close the database if it's still open
	if(isset($db) && $db->IsOpen()) {
      $db->Close();
	}
	
    //Be kind, rewind (explicitly call the class destructors)
	$this->action = null;
	$this->form = null;
  }
  
  //Fire this every time the page loads to process actions
  function Process($param = '') {
    if(isset($_GET['opt'])) {
	  //Callback defined, fire it
      $this->action->Action($_GET['opt'], $_GET);
    } else {
	  //Default, show first article
	  $this->action->Action('default');
	}
  }
  
  
  //Action handlers
  function DefaultAction($param = '') {
    global $db;
	global $user;
	
    $results = $db->Query( 'SELECT * FROM users WHERE id=' . $user->GetUserId() );
	if($results) {
      list( $id, $group_id, $user, $pass, $salt ) = mysqli_fetch_row( $results );
	  echo "  <p>Username: $user, Group: $group_id, Member ID: $id</p>\n";
	}
  }
  /*
  function Show($param = '') {
    global $db;
  
    if( $param['opt'] == 'show' && isset( $param['id'] ) ) {
	  // Show a specific article (by id)
      $results = $db->Query( 'SELECT * FROM articles WHERE id=' . $param['id'] );
	} else {
      // Fetch and list all articles in the database, show highest id first
      $results = $db->Query( 'SELECT * FROM articles ORDER BY id DESC' );
	}
  
    while( list( $id, $title, $author, $date, $content ) =
	         mysqli_fetch_row( $results ) ) {
      //Make the date/time look nice when we output it
      $pretty_time = date("M j, Y g:i a", strtotime($date));
	
	  echo "  <div class=\"box\">
    <h2><a href=\"articles.php?opt=show&amp;id=$id\">$title</a></h2>
    <p>$content</p>
    <h2 style=\"text-align: right;\">
      On $pretty_time by <a href=\"#\">$author</a>
      [ <a href=\"articles.php?opt=edit&amp;id=$id\">Edit</a> |
      <a href=\"articles.php?opt=del&amp;id=$id\">Delete</a> ]
    </h2>
  </div>\n\n";
    }
  }
  
  function NewArticle($param = '') {
    // User requested to post a new article
    // Build and display the form, validate inputs, etc.
    $this->form->OpenForm( 'articles', 'articles.php', 'get' );  // <form...
    $this->form->AddInput( 'str_title', "Title: " );       //  <input type="text"
    $this->form->AddInput( 'str_author', "Author: " );     //  <input type="text"
    $this->form->AddTextarea( 'str_content', '12', '40' ); //  <textarea ...
	$this->form->AddHidden( 'opt', 'save' );
    $this->form->AddSubmit( "Save" );                      //  <input type="submit"
    $this->form->Close();                                  // </form>
  }
  
  function Edit($param = '') {
    global $db;
	
    // User requested to edit an existing article, fetch it
	$results = $db->Query( 'SELECT * FROM articles WHERE id=' . $param['id'] );
  
    list( $id, $title, $author, $date, $content ) = mysqli_fetch_row( $results );
    // Build and display the form, validate inputs, etc.
    $this->form->OpenForm( 'articles', 'articles.php?opt=update', 'get' );
    $this->form->AddInputWithValue( 'str_title', "Title: ", $title );
    $this->form->AddInputWithValue( 'str_author', "Author: ", $author );
    $this->form->AddTextareaWithValue( 'str_content', '12', '40', $content );
	$this->form->AddHidden( 'opt', 'update' );
	$this->form->AddHidden( 'id', $param['id'] );
    $this->form->AddSubmit( "Update" );
    $this->form->Close();
  }
  
  function Update($param = '') {
    global $db;
  
    if( $this->form->SubmittedOK() ) {
      // User is updating an article
	  $up  = 'title=\'' . mysqli_real_escape_string($db->getHandle(), $param['str_title']) . '\'';
	  $up .=', author=\'' . mysqli_real_escape_string($db->getHandle(), $param['str_author']) . '\'';
	  $up .=', content=\'' . mysqli_real_escape_string($db->getHandle(), $param['str_content']) . '\'';
	
      if( $db->Update( 'articles', $param['id'], $up ) ) {
        //echo "Article updated\n";
	    $db->Close();
        // Redirect back to here to show the new post
        header( 'Location: articles.php' );
      } else {
        echo "Error updating article!\n";
      }
    }
  }
  
  function Save($param = '') {
    global $db;
  
    if( $this->article->SubmittedOK() ) {
      // Get our GET variables & prep our insert command
      $arr = array_merge( array('0'), array_slice( $param, 0, -2 ) );
	  //Set date to null so we always have the latest update timestamp
	  //$arr[3] = null;
	
      // and shove the data in the DB
      if( $db->Insert( 'articles', array( 'id','title','author','content' ), $arr ) ) {
        $db->Close();
        // Redirect back to here to show the new post
        header( 'Location: articles.php' );
      }
	}
  }
  
  function Delete($param = '') {
    global $db;
	
    // User is deleting an article
    if( $db->Delete( 'articles', 'id=' .$_GET['id'] ) ) {
      //echo "Article removed\n";
	  $db->Close();
      // Redirect back to here to show the new post
      header( 'Location: articles.php' );
    } else {
      echo "Error deleting article!\n";
    }
  }
  */
}

$a = new Account();
$a->Process();

?>

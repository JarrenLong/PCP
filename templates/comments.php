<?php
/**
 * Name:        Jarren Long
 * Date:        3/6/2012
 * Assignment:  PHP Comments
 * Class:       CIS 230 PHP
 * Description: Comments widget for Long Technical, Co.
 *
 */

class Comments extends Widget {
  private $comment_of = 0;
  private $widget_caller = 'Widget';
  private $widget_alias = 'Comment';
  
    //Initialize class on creation
  function __construct($class_name, $guid, $alias) {
    // Give this class a name and GUID to EXTEND
    $this->class_name = $class_name;
	$this->guid = $guid;
	$this->widget_alias = $alias;
	
	global $db;
	// Open a connection to the database
	if(isset($db) && !$db->IsOpen()) {
      $db->Open();
	}

    // Initialize the form class
    $this->form = new Form;
	
    // Initialize the action processor
    $this->action = new Processor();
	
	//Configure the table CRUD fields
    $this->RegisterTableFields(array('id','comment_of','author_id','comment','rating','created_date'), 0, 0);
    $this->RegisterNewFields(array('comment_of','author_id','comment','rating'));
    $this->RegisterEditFields(array('comment','rating'));
	
	//And register actions
	$this->RegisterActions(array('default_comment', 'new_comment','edit_comment','update_comment','save_comment','del_comment'));
  }
  
  public static function getCommentOf() {
    global $session;
	return $session->Get('pcp_comment_of');
  }
  
  public static function setCommentOf($comment_of) {
    global $session;
	$session->Set('pcp_comment_of', $comment_of);
  }
  
  //Fire this every time the page loads to process actions
  function Process($param = '') {
    global $user;
	//Forward our widget class' name
	$cn = Widget::getName();
	Widget::setName($this->class_name);
	
      //Only allow logged in users with permission to execute this action	  
      if(isset($param['opt']) && ($this->class_name == "Login" || (isset($user) && $user->HasPermission($cn . '_' . $param['opt'])))) {
	    Widget::setLastAction($param['opt']);
        $this->action->Action($param['opt'], $param);
	  } else {
	    Widget::setLastAction('default_comment');
		Comments::setCommentOf($cn . '_' . $param['id']);
	    $this->action->Action('default_comment', array('id'=>$param['id']));
	  }
  }
  
  function GetAverageRating($from, $id) {
      global $db;
	  $ret = "";
	  
	  $cResults = $db->Query( "SELECT * FROM comments WHERE comment_of='" . $from . '_' . $id . "'");
	  //Get the average rating of this blog
	  $avg_rating = 0;
	  $num_comments = 0;
	  
	  if($cResults) {
        while(($row = mysqli_fetch_row( $cResults )) != null) {
          $avg_rating += $row[4];
		  $num_comments++;
	    }
	  
	    if($num_comments > 0) {
	      $avg_rating = $avg_rating / $num_comments;
	    }
		
	    if($avg_rating != 0) {
          $ret .= "<br/><span class=\"right\">Rated ";
	  
          //Display the average rating in stars
          while($avg_rating > 1) {
            $ret .= "  <img src=\"images/star.png\" alt=\"star\" />";
            $avg_rating--;
	      }
          //Half star if needed
          if($avg_rating >= 1) {
            $ret .= "  <img src=\"images/star.png\" alt=\"half star\" />";
          } else if($avg_rating >= 0.5) {
            $ret .= "  <img src=\"images/half_star.png\" alt=\"half star\" />";
          }
	  
          $ret .= "</span>";
	    }
	  }

	  return $ret;
  }

  function BuildCommentModLinks(&$user, $id) {
    $cn = Widget::getName();
  	//Build fancy edit/delete links
	$buf = " ";
	$tmp = Widget::HardLinkToAction($cn, 'edit_comment', 'Edit', array('id'=>$id));
	if($tmp != 'Edit') {
	  $buf .= "[ " . $tmp;
	}
	$tmp = Widget::HardLinkToAction($cn, 'del_comment', 'Delete', array('id'=>$id));
	if($tmp != 'Delete') {
	  if($buf == " ") {
	    $buf .= "[ " . $tmp;
	  } else {
	    $buf .= " | " . $tmp;
	  }
	}
	
	if($buf != " ") {
	  $buf .= " ]";
	}
	
	return $buf;
  }
    
  function FormatCommentItem($assoc_array, $db_fields) {
    global $user;
  
    //Make the date/time look nice when we output it
    $pretty_time = date("M j, Y g:i a", strtotime($assoc_array[$db_fields[5]]));
	
	//Note to self: make sure ampersands are properly escaped
    echo "  <div class=\"box\">
    <p>" . $assoc_array[$db_fields[3]] . "</p>
    <h2 style=\"text-align: right;\">
      On $pretty_time by <a href=\"#\">" . $user->GetUsernameFromId($assoc_array[$db_fields[2]]) . "</a>";
	
	$rating = $assoc_array[$db_fields[4]];
	if($rating != 0) {
	  echo "<br/><span style=\"text-align: right;\">";
	  
      //Display the average rating in stars
      while($rating > 1) {
        echo "  <img src=\"images/star.png\" alt=\"star\" />";
		$rating--;
      }

	  //Half star if needed
	  if($rating >= 1) {
        echo "  <img src=\"images/star.png\" alt=\"half star\" />";
      } else if($rating >= 0.5) {
        echo "  <img src=\"images/half_star.png\" alt=\"half star\" />";
      }
	  
      echo "</span>";
    }
		
	if((isset($user) && $user->LoggedIn()) || $user->GetGroupId() == GROUP_ADMIN) {
	  echo $this->BuildCommentModLinks($user, $assoc_array[$db_fields[0]]);
	}
	
    echo "</h2>
  </div>\n\n";
  }
  
  //Action handlers
  function action_default_comment($param = '') {
    global $db;
	global $user;
	
	$cn = Widget::getName();
	
	if(isset($param['id'])) {	  
	  $results = $db->Query( "SELECT * FROM comments WHERE comment_of='" . Comments::getCommentOf() . "'" );
	  //Send the queried results off to the formatting function for output
	  if(isset($results) && $results != '') {
	    while(($row = mysqli_fetch_array($results)) != null) {
	      $this->FormatCommentItem($row, $this->db_fields);
		}
	  }
	
	  echo "  <div class=\"clear\"></div><div class=\"right\">" .
		Widget::HardLinkToAction($cn, 'new_comment', 'Add ' . $this->widget_alias, array('w'=>Widget::getGuid())) . 
		"  </div>\n";
	}
  }
  
  function action_new_comment($param = '') {
    global $user;
    $cn = Widget::getName();
	$guid = Widget::getGuid();
    // User requested to post a new article
    // Build and display the form, validate inputs, etc.
    $this->form->OpenForm( $guid, $cn, $cn . '.php', 'get' );  // <form...
	$this->form->AddHidden( 'comment_of', Comments::getCommentOf() );     //  <input type="text"
	$this->form->AddHidden( 'author_id', $user->GetUserId() );     //  <input type="text"
    $this->form->AddTextarea( 'comment', '12', '40' ); //  <textarea ...
	$this->form->AddInput( 'rating', 'Rating (0-5):'); //  <textarea ...
	$this->form->AddHidden( 'opt', 'save_comment' );
    $this->form->AddSubmit( "Save" );                      //  <input type="submit"
    $this->form->Close();                                  // </form>
  }
  
  function action_edit_comment($param = '') {
    global $db;
	
	$cn = Widget::getName();
	$guid = Widget::getGuid();
	
	$results = $db->Query('SELECT * FROM comments WHERE id=' . $param['id']);
	if($results) {
      list( $id, $comment_of, $author_id, $comment, $rating, $created_date ) = mysqli_fetch_row( $results );
      // Build and display the form, validate inputs, etc.
      $this->form->OpenForm( $guid, $cn, $cn . '.php', 'get' );
      $this->form->AddTextareaWithValue( 'str_comment', '12', '40', $comment );
	  $this->form->AddInputWithValue( 'str_rating', "Rating: ", $rating );
	  $this->form->AddHidden( 'opt', 'update_comment' );
	  $this->form->AddHidden( 'id', $id );
      $this->form->AddSubmit( "Update" );
      $this->form->Close();
	}
  }
  
  function action_update_comment($param = '') {
    global $db;
	
    if( $this->form->SubmittedOK() ) {
      // User is updating an item
	  $up  = 'comment=\'' . mysqli_real_escape_string($db->getHandle(), $param['str_comment']) . '\'';
	  $up .=', rating=\'' . mysqli_real_escape_string($db->getHandle(), $param['str_rating']) . '\'';
	
      if( $db->Update( 'comments', $param['id'], $up ) ) {
	    $db->Close();
        // Redirect back to the default page
        header( 'Location: ' . Widget::getName() . '.php' );
      } else {
        echo "Error updating " . $this->widget_alias . "!\n";
      }
    }
  }
  
  function action_save_comment($param = '') {
    global $db;
	
    if( $this->form->SubmittedOK() ) {
      // Get our postback variables (strip out the routing ones)
	  //$param[0] = 'w'
	  //$param[-2] = 'opt'
	  //$param[-1] = 'submit'
	  $arr = array_slice( $param, 1, -2 );
	
      // and shove the data in the DB
	  if( $db->Insert( 'comments', $this->new_fields, $arr ) ) {
        $db->Close();
        // Redirect back to the default page
        header( 'Location: ' . Widget::getName() . '.php' );
      }
	}
  }
  
  function action_del_comment($param = '') {
    global $db;
	
    // User is deleting an item
    if( $db->Delete( 'comments', 'id=' . $param['id'] ) ) {
	  $db->Close();
      // Redirect back to the default page
      header( 'Location: ' . Widget::getName() . '.php' );
    } else {
      echo "Error deleting " . $this->widget_alias . "!\n";
    }
  }
}

?>

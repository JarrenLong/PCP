<?php

//All widget classes will derive from this base class
class Widget {
  public $class_name = 'Widget';
  public $guid = null; //GUID for passing actions to their rightful owners
  public $form = null; //Form
  public $action = null; //Processor
  public $db_fields = null; //All fields in this database table
  public $new_fields = null; //Fields users can fill out when creating
  public $edit_fields = null; //Fields users can fill out when editing
  public $order_by_field = 'id'; //For sorting default action DB results 
  private $showDefault = 1; //# items to show on default load
  private $last_action = 0; //Last action processed
  
  //Initialize class on creation
  function __construct($class_name) {
    //Give this class a name to use
    $this->class_name = $class_name;
	$this->guid = md5($class_name . uniqid(rand()));
	$this->guid = substr($this->guid, 0, 8); //Should be random enough
	
	global $db;
	//Open a connection to the database
	if(isset($db) && !$db->IsOpen()) {
      $db->Open();
	}

    // Initialize the form class
    $this->form = new Form;
	
    // Initialize the action processor
    $this->action = new Processor();
  }

  //Cleanup and die on close
  function __destruct() {
	global $db;
	//Close the database if it's still open
	if(isset($db) && $db->IsOpen()) {
      $db->Close();
	}
	
    //Be kind, rewind (explicitly call the class destructors)
	$this->action = null;
	$this->form = null;
  }
  
  function RegisterActions($action_array = null) {
  	//Register the default actions
	$this->action->Register('default', array($this, 'action_default'));
	$this->action->Register('show', array($this, 'action_show'));
	$this->action->Register('showall', array($this, 'action_showall'));
	
	//Register all actions
	if(is_array($action_array)) {
	  foreach($action_array as $action) {
	    $this->action->Register($action, array($this, 'action_' . $action));
	  }
	}
  }
  
  function RegisterTableFields($field_array, $order_by_field_index, $num_items) {
    $this->db_fields = $field_array;
	$this->order_by_field = $field_array[$order_by_field_index];
	$this->showDefault = $num_items;
  }
  
  function RegisterNewFields($field_array) {
    $this->new_fields = $field_array;
  }
  
  function RegisterEditFields($field_array) {
    $this->edit_fields = $field_array;
  }
    
  public static function getName() {
    global $session;
    return $session->Get("pcp_class_name");
  }
  
  public static function setName($name) {
    global $session;
    $session->Set("pcp_class_name", $name);
  }
  
  public static function getGuid() {
    global $session;
    return $session->Get("pcp_class_guid");
  }
  
  public static function setGuid($guid) {
    global $session;
	$session->Set("pcp_class_guid", $guid);
  }
  
  public static function getLastAction() {
    global $session;
    return $session->Get("pcp_class_action");
  }
  
  public static function setLastAction($action) {
    global $session;
    $session->Set("pcp_class_action", $action);
  }
  
  //Fire this every time the page loads to process actions
  function Process($param = '') {
    global $user;
	//Forward our widget class' name
	$cn = Widget::getName();
	$guid = Widget::getGuid();
	Widget::setName($this->class_name);
	Widget::setGuid($this->guid);
	
      //Only allow logged in users with permission to execute this action	  
      if(isset($param['opt']) && ($this->class_name == "Login" || (isset($user) && $user->HasPermission($this->class_name . '_' . $param['opt'])))) {
	    Widget::setLastAction($param['opt']);
        $this->action->Action($param['opt'], $param);
	  } else {
	    Widget::setLastAction('default');
	    $this->action->Action('default');
	  }
  }
  
  //Build a link using the specified options and parameters
  function LinkToAction($user, $action, $text, $params = '') {
    //global $user;
  
    $ret = "";
	$cn = Widget::getName();
	$guid = Widget::getGuid();
	
	if($user->HasPermission($cn . '_' . $action)) {
      if(isset($params) && is_array($params)) {
	    $ret = "<a href=\"" . $cn . ".php?w=" . $guid . "&amp;opt=" . $action;
	 
	    foreach($params as $k => $v) {
	      $ret .= "&amp;" . $k . "=" . $v;
	    }
	  
	    $ret .= "\">$text</a>";
	  } else {
	    $ret = "<a href=\"" . $cn . ".php?w=" . $guid . "&amp;opt=" . $action . "\">$text</a>";
	  }
	} else {
	  $ret = $text;//"<a href=\"login.php\">$text</a>";
	}
	
	return $ret;
  }
  
    //Build a link using the specified options and parameters
  public static function HardLinkToAction($class_name, $action, $text, $params = '') {
    global $user;
  
    $ret = "";
	
      if(isset($params) && is_array($params)) {
	    $ret = "<a href=\"" . $class_name . ".php?opt=$action";
	 
	    foreach($params as $k => $v) {
	      $ret .= "&amp;$k=$v";
	    }
	  
	    $ret .= "\">$text</a>";
	  } else {
	    $ret = "<a href=\"$class_name.php?opt=$action\">$text</a>";
	  }
	
	return $ret;
  }
  
  function BuildModLinks(&$user, $id) {
  
  	//Build fancy edit/delete links
	$buf = " ";
	$tmp = $this->LinkToAction($user, 'edit', 'Edit', array('id'=>$id));
	if($tmp != 'Edit') {
	  $buf .= "[ " . $tmp;
	}
	$tmp = $this->LinkToAction($user, 'del', 'Delete', array('id'=>$id));
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
  
  function BuildAddShowLinks(&$user, $opt = '') {
	//Build fancy add/show links
	$cn = Widget::getName();
	
	$buf = "  <div class=\"clear\"></div><div class=\"right\">";
	$title = 'Add ' . ucfirst(depluralize($cn));
	$tmp = $this->LinkToAction($user, 'new', $title);
	if($tmp != $title) {
	  $buf .= $tmp . " | ";
	}
	if($opt=='showall') {
	  $title = '[-] Show Latest ' .
	    ucfirst($this->showDefault > 1 ? $cn : depluralize($cn));
	  $tmp = $this->LinkToAction($user, 'default', $title );
	} else {
	  $title = '[+] Show All '. ucfirst($cn);
	  $tmp = $this->LinkToAction($user, 'showall', $title );
	}
	if($tmp != $title) {
	  $buf .= $tmp;
	}
	$buf .= "  </div>\n";
	
	echo $buf;
  }
  
  //Child classes should override this!
  function FormatItem($assoc_array, $db_fields) {}
  
  //Child classes should NOT override these! They are the same for all widgets!
  //They DO still need to be registered with each widget's action system to be used!
  function action_default($param = '') {
    global $db;
	global $user;
	$cn = Widget::getName();
	
	//Show a specific item
	if($param['opt']=='show') {
	  $results = $db->Query( 'SELECT * FROM ' . $cn . ' WHERE ' .
	    $this->order_by_field . '=' . $param[$this->order_by_field] );
	  
	  //Send the queried results off to the formatting function for output
	  if($results) {
	    $row = mysqli_fetch_array($results);
	    $this->FormatItem($row, $this->db_fields);
	  }
	} else {
	  //Show all items for this table
	  if($param['opt']=='showall') {
	    $results = $db->Query( 'SELECT * FROM ' . $cn .
		  ' ORDER BY ' . $this->order_by_field . ' DESC' );
	  }
	  //Default load behavior
	  else {
        $results = $db->Query( 'SELECT * FROM ' . $cn .
		  ' ORDER BY ' . $this->order_by_field .
		  ' DESC LIMIT ' . $this->showDefault );
	  }
	
	  //Send the queried results off to the formatting function for output
	  if($results) {
        while ( $row = mysqli_fetch_assoc($results)) {
          $this->FormatItem($row, $this->db_fields);
        }
	  }
	}
	
	if($param['opt']!='show') {
	  $this->BuildAddShowLinks($user, $param['opt']);
	}
  }
  //Wrapper around default handler
  function action_show($param = '') {
    $this->action_default($param);
  }
  //Wrapper around default handler
  function action_showall($param = '') {
    $this->action_default($param);
  }
  
  function action_save($param = '') {
    global $db;
    $cn = Widget::getName();
	
    if( $this->form->SubmittedOK() ) {
      // Get our postback variables (strip out the routing ones)
	  //$param[0] = 'w'
	  //$param[-2] = 'opt'
	  //$param[-1] = 'submit'
	  $arr = array_slice( $param, 1, -2 );
	
      // and shove the data in the DB
	  if( $db->Insert( $cn, $this->new_fields, $arr ) ) {
        $db->Close();
        // Redirect back to the default page
        header( 'Location: ' . $cn . '.php' );
      }
	}
  }
  
  function action_del($param = '') {
    global $db;
	$cn = Widget::getName();
	
    // User is deleting an item
    if( $db->Delete( $cn, 'id=' . $param['id'] ) ) {
	  $db->Close();
      // Redirect back to the default page
      header( 'Location: ' . $cn . '.php' );
    } else {
      echo "Error deleting " . depluralize($cn) . "!\n";
    }
  }
}

?>

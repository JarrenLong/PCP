<?php

require_once('header.php');
require_once('footer.php');

/*
 * A Page is just a simple wrapper for a collection of widgets, that also
 * handles inter-page postback routing.
 */
class Page {
  private $title = 'Page';
  private $widgets = null;
  
  function __construct() {
    $this->widgets = array();
	//Turn on output buffering for this page
	ob_start();
  }
  
  function __destruct() {
    //Dump out whatever's left in the output buffer for this widget
    ob_flush();
  }
  
  function SetTitle($name) {
    define( "PCP_PAGE_TITLE", $name);
  }
  
  //Passing by reference here, the object exists at the global scope
  function AddWidget(&$widget_obj) {
	array_push($this->widgets, $widget_obj);
  }

  //Return GUID was registered on this or the last load
  function isRegistered($guid) {
    foreach($this->widgets as $i => $w) {
	  if($this->widgets[$i]->guid == $guid) {
	    return $i;
	  }
    }
	
	return null;
  }
  
  function Render($param = '') {	
    global $session;
	
	$old_widgets = $session->Get('widget_list');
	if(isset($old_widgets)) {
	  $from_arr = explode(',', $old_widgets);
	  
	  foreach($this->widgets as $i => $w) {
		$this->widgets[$i]->guid = $from_arr[$i];
	  }
	}
	
	//Clear this out when not in use? EDIT: No
	//$session->Set('widget_list', 0);
	
    //Draw the page header
	DrawSiteHeader();
	
	//Update all widgets!
	foreach($this->widgets as $i=>$w) {
      $this->widgets[$i]->Process($param);
	}
	  
	//Draw the page footer
	DrawSiteFooter();
	
	// Persist our current widget GUIDs so we can reload them later
	$to_set = '';
	foreach($this->widgets as $i=>$w) {
	    $to_set .= $this->widgets[$i]->guid . ',';
	}
	
	//Save & reset for next page load
    global $session;
	$session->Set('widget_list', substr($to_set, 0, -1));
  }
}

?>

<?php

class StaticPage extends Widget {
  private $page_path = "static.php";
  
  /*
   * A static page doesn't need database access, action handling,
   * or anything else fancy, just text. I'm going to override the
   * default Widget behavior so we can cut out the excess
   * overhead for this widget.
   */
  function __construct($class_name) {
    //Give this class a name to use
    $this->class_name = $class_name;
	$this->guid = md5($class_name . uniqid(rand()));
	$this->guid = substr($this->guid, 0, 8); //Should be random enough
  }
  function __destruct() { }
  
  function SetPage($file_path) {
    $this->page_path = $file_path;
  }
  //Just render the page, no matter what
  function Process($param = '') {
    //Just echo out whatever is in the specified file
    include('./static/' . $this->page_path);
  }
}

?>

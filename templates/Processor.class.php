<?php

class Processor {
  private $action = null;
  
  function __construct() {
    $this->action = array(0);
  }
  
  function __destruct() {
    //Dump any registered actions
    unset($this->action);
	$action = null;
  }
  
  /* Debug function, print list of registered callbacks
  function PrintList() {
    foreach($this->action as $k => $v) {
	  echo $k . ":" . $v . "<br/>\n";
	}
  }
  */
  
  /*
  Register an action callback function by name
  $callback can be:
   - A function name as a string ($callback = 'MyFunction';)
   - An array holding static class and function names
     ($callback = array('MyClass', 'MyFunction');)
   - An array holding an instantiated class object and function name
     ($obj = new MyClass(); $callback = array($obj, 'MyFunction');)
   See: http://php.net/manual/en/language.pseudo-types.php
  */
  function Register($name, $callback = null) {
    $ret = false;
	
    if(isset($name)) { //action $name is required	  
	  if($callback == null) {
	    //No callback? Use the default static one in this class
	    $this->action[$name] = array('Processor', 'DefaultAction');
	  } else {
	    $this->action[$name] = $callback;
	  }
	  
	  $ret = true;
	}
	
	return $ret;
  }
  
  //Unregister an action by name (returns true if unregistered)
  function Unregister($name) {
	if(isset($this->action[$name])) {
	  unset($this->action[$name]);
	  return true;
	}
	
	return false;
  }
  
  //Fire callback if it's registered (returns true if callback fires)
  //$params can (optionally) hold the callback parameters
  function Action($name, $params = null) {
    $found = false;
	
	if(isset($this->action[$name])) {
	  $found = true;
      //Fire the callback
      call_user_func($this->action[$name], $params);
	}
	
	return $found;
  }
  
  //Default callback error handler (unsupported action)
  private static function DefaultAction($name) {
    echo "<span style=\"color: red;\">Unknown action: " . $name . "</span><br/>\n";
  }
}

function fnTestCallbackA() {
  echo "fnTestCallbackA<br/>";
}
  
function fnTestCallbackB($opt) {
  echo "fnTestCallbackB:" . $opt . "<br/>";
}

//Quick test to make sure the Processor class works
class ProcessorTest {
  private $action = null;
  
  function __construct() {
  
    $action = new Processor();
	echo "Registering actions...<br/>\n";
	//Normal function callback tests
    $action->Register('fnTestA', 'fnTestCallbackA');
	$action->Register('fnTestB', 'fnTestCallbackB');
	//Instantiated class method callback tests
	$action->Register('objTestA', array($this, 'objTestCallbackA'));
	$action->Register('objTestB', array($this, 'objTestCallbackB'));
	//Static class method callback tests
	$action->Register('stTestA', array('ProcessorTest', 'stTestCallbackA'));
	$action->Register('stTestB', array('ProcessorTest', 'stTestCallbackB'));
	//Debug function
	//$action->PrintList();
	
	echo "Calling actions...<br/>\n";
	$action->Action('fnTestA');
	$action->Action('fnTestB', 'opt');
	$action->Action('objTestA');
	$action->Action('objTestB', 'opt');
	$action->Action('stTestA');
	$action->Action('stTestB', 'opt');
	$action->Action('unknown');
	$action->Action('unknown', 'opt');
	
	echo "Unregistering actions...<br/>\n";
	$action->Unregister('fnTestA');
	$action->Unregister('fnTestB');
	$action->Unregister('objTestA');
	$action->Unregister('objTestB');
	$action->Unregister('stTestA');
	$action->Unregister('stTestB');
	
	echo "Calling actions...<br/>\n";
	$action->Action('fnTestA');
	$action->Action('fnTestB', 'opt');
	$action->Action('objTestA');
	$action->Action('objTestB', 'opt');
	$action->Action('stTestA');
	$action->Action('stTestB', 'opt');
	$action->Action('unknown');
	$action->Action('unknown', 'opt');
  }  
  
  function objTestCallbackA() {
    echo "objTestCallbackA<br/>";
  }
  
  function objTestCallbackB($opt) {
    echo "objTestCallbackB:" . $opt . "<br/>";
  }
  
  static function stTestCallbackA() {
    echo "stTestCallbackA<br/>";
  }
  
  static function stTestCallbackB($opt) {
    echo "stTestCallbackB:" . $opt . "<br/>";
  }
}

//Uncomment this to run test
//$pt = new ProcessorTest();

?>

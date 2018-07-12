<?php

//A generic interface to make sure session and cookie management is as seamless and interchangeable as possible
interface iPersist {
  public function Get($name); // Get a variable (by name)
  public function Set($name, $val); // Set a variable (by name)
  public function Delete($name); // Delete a variable (by name)
}


//Pretty simple Session class wrapper, supports init/kill session and variable get/set
class Session implements iPersist {
  // Start a new session
  function __construct() {
    session_start(); 
  }
  
  // End current session  
  function Kill() {
    session_destroy();
  }
  
  // Get a session variable (by name)
  function Get($name) {
	return $_SESSION[$name];
  }
  
  // Set a session variable (by name)
  function Set($name, $val) {
    $_SESSION[$name] = $val;
  }
  
  function Delete($name) {
    unset($_SESSION[$name]);
  }
  
  function ToCookie() {
    $c = new Cookie();
	//c.Init();
    foreach($_SESSION as $k => $v) {
	  c.Set($k, $v);
	}
	
	return c;
  }
}

//Pretty simple Session class wrapper, supports init/kill session and variable get/set
class Cookie implements iPersist {  
  // Get a cookie value
  function Get($name) {
	return $_COOKIE[$name];
  }
  
  // Set a cookie value
  function Set($name, $val) {
    setcookie($name, $val);
  }
  
  function SetAll($name, $val, $expires = 0, $path = '/', $host = '/', $secure = 0, $httponly = 0) {
    setcookie($name, $val, $expires, $path, $host, $secure, $httponly);
  }
  
  function Delete($name) {
    setcookie($name, '', time()-3600);
  }
  
  function ToSession() {
    $s = new Session();
	s.Init();
	
    foreach($_COOKIE as $k => $v) {
	  s.Set($k, $v);
	}
	
	return s;
  }
}

?>

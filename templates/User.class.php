<?php


class User {

  // Register new user
  function Register( $user, $pass ) {
    global $db;
	
	$ret = false;
	
    if( isset( $user ) && isset( $pass ) ) {
	  //Build & clean the fields to insert
	  $usr = mysqli_real_escape_string( $db->getHandle(), $user );
	  $pwd = mysqli_real_escape_string( $db->getHandle(), $pass );
	  $salt = substr( md5( uniqid( rand(), true ) ), 0, 8 );
	  $pwd = hash( 'sha256', $pwd . $salt );
	  
	  if( !$this->checkUsernameExists( $usr ) ) {
	    //Insert the new record if the user doesn't already exist
	    if( $db->Insert( 'users', array( 'user', 'pass', 'salt' ), array( $usr, $pwd, $salt ) ) ) {
		  $ret = true;
        }
	  }
	}
	
	//Auto-login after registration
	if($ret) {
	  return $this->Login($user, $pass);
	}
	
    return $ret;
  }
  
  // Allow user to login
  function Login( $user, $pass ) {
    global $db;
	global $session;
	
    if( isset( $user ) && isset( $pass ) ) {
	  $usr = mysqli_real_escape_string( $db->getHandle(), $user );
	  $pwd = mysqli_real_escape_string( $db->getHandle(), $pass );
	
	  $result = $db->Query( "SELECT * FROM users WHERE user='" . $usr . "'" );
	  
	  while ( list( $id, $group_id, $user, $pass, $salt ) = mysqli_fetch_row( $result ) ) {
	    //If the password hashes match, user has the right user/pass combo
	    if( $pass == hash( 'sha256', $pwd . $salt ) ) {
		  //let 'em in
		  $session = new Session();
	      $session->Set( 'user_id', $id );
		  $session->Set( 'group_id', $group_id );
		  $session->Set( 'user_name', $user );
		  $session->Set( 'logged_in', 1 );
	    }
	  }
	}
	
	return $this->LoggedIn();
  }
  
  //Check if user is logged into this session
  function LoggedIn() {
    global $session;
    return $session->Get( 'logged_in' );
  }
  
  //Logout user
  function Logout() {
    global $session;
    $session->Set( 'logged_in', 0);
	$session->Set( 'user_name', 0);
	$session->Set( 'user_id', 0);
	$session->Set( 'group_id', 0);
	$session->Kill();
	//Start a new guest session
	$session = new Session();
  }
  
  //Get user's permissions
  //TODO: Stub
  function GetPermissions() {
    global $db;
	
	//Set up a return value
	$ret = null;
	
	if($this->LoggedIn()) {
	  //Grab the permissions for the group this user belongs to
      $result = $db->Query( "SELECT * FROM groups WHERE id='" . $this->GetGroupId() . "'" );
	  if($result) {
	    list( $id, $name, $permissions ) = mysqli_fetch_row( $result );
	    //Convert CSV string to array
	    $ret = explode(',', $permissions);
	  }
	}
	
	//And return all permissions
    return $ret;
  }
  
  //Does the user have $perm permission?
  function HasPermission($perm) {
    $ret = false;
	
    //Admin have full permissions, don't bother checking
    if($this->GetGroupId() == PCP_GROUP_ADMIN) {
	  $ret = true;
	}
	
	if(!$ret) {
      $perms = $this->GetPermissions();
	
	  if(isset($perms)) {
	    if(is_array($perms)) {
	      foreach($perms as $k=>$v) {
	        if($v == $perm) {
	          $ret = true;
	        }
	      }
	    } else if($perms == $perm) {
	      $ret = true;
	    }
	  }
	}
	
	//Debug code
	/*
	if($ret) {
	  echo "Granted: " . $perm . "<br/>";
	} else {
	  echo "Denied: " . $perm . "<br/>";
	}
	*/
	return $ret;
  }
  
  //Get user's permissions
  //TODO: Stub
  function SetPermissions( $param ) {
    return null;
  }

  //Just return the username from the current session
  function GetUsername() {
    global $session;
    return $session->Get( 'user_name' );
  }
  
  function GetUserId() {
    global $session;
    return $session->Get( 'user_id' );
  }
  
  function GetGroupId() {
    global $session;
    return $session->Get( 'group_id' );
  }
  
  function GetUsernameFromId($id) {
    $ret = $this->GetUserInfo($id);
	if(is_array($ret)) {
	  return $ret[2];
	}
	
    return "Unknown";
  }
  
  // Get the user's information (id, group, & name)
  function GetUserInfo( $user ) {
    global $db;
	
    if( isset( $user ) ) {
	  $usr = mysqli_real_escape_string( $db->getHandle(), $user );
	
	  $result = $db->Query( "SELECT * FROM users WHERE id='" . $usr . "'" );

	  //NEVER return the password or the salt!!!
	  $ret = mysqli_fetch_row( $result );
	  if($ret) {
	    return array_slice( $ret, 0, 3 );
	  }
	}
	
	return false;
  }

  //TODO: Stub
  function GetOnlineUsers() {
    return null;
  }
  
  /* Internal functions */
  //Check if a username is already registered in the system
  private function checkUsernameExists( $user ) {
    global $db;

	//Kinda blunt: Query the DB for a user, if any results are returned, return true
	$result = $db->Query( "SELECT * FROM users WHERE user='" . $user . "'" );

	$arr = mysqli_fetch_row( $result );
	if( is_array( mysqli_fetch_row( $result ) ) ) {
	  return true;
	}
	
	//If not, user must not exist, return false
	return false;
  }
}

?>

<?php

class Login extends Widget {
  function RegisterActions($action_array = null) {
  	//Register the default actions
	$this->action->Register('default', array($this, 'action_default'));
	$this->action->Register('login', array($this, 'action_login'));
	$this->action->Register('logout', array($this, 'action_logout'));
	$this->action->Register('signup', array($this, 'action_signup'));
	$this->action->Register('register', array($this, 'action_register'));
  }
 
  function action_default($param = '') {
    global $user;
	
    if(!$user->LoggedIn()) {
	  $this->form->OpenForm( $this->guid, 'login', 'login.php', 'get' );
      $this->form->AddInput('user', 'Username');
      $this->form->AddPassword('pass', 'Password');
	  $this->form->AddHidden('opt','login');
      $this->form->AddSubmit('Login');
      $this->form->Close();
	  
	  echo "<br/>" . $this->LinkToAction($user, 'signup', "Sign Up!");
	} else {
	  //Already logged in, redirect
	  header( 'Location: index.php' );
	}
  }
  
  function action_login($param = '') {
    global $user;
	
	if( $this->form->SubmittedOK() ) {
    if(!$user->LoggedIn()) {
        if( $user->Login($param['user'], $param['pass']) ) {
          header('Location: index.php');
	    } else {
          echo "<br/><span style=\"color: red;\">Username or password is incorrect, try again</span>";
        }
	} else {
	  header( 'Location: index.php' );
	}
	} else {
	echo "no submit!";
	}
  }
  
  function action_logout($param = '') {
    global $user;
	
    if(isset($user)) {
	  $user->Logout();
	  header( 'Location: index.php' );
	}
  }
  
  function action_signup($param = '') {
    global $user;
	
	if(!$user->LoggedIn()) {
	  $this->form->OpenForm( $this->guid, 'register', "login.php", 'get' );
      $this->form->AddInput('user', 'Username');
      $this->form->AddPassword('pass', 'Password');
	  $this->form->AddPassword('pass2', 'Retype Pass');
	  $this->form->AddHidden('opt','register');
      $this->form->AddSubmit('Sign Up!');
      $this->form->Close();
	} else {
	  header( 'Location: index.php' );
	}
  }
  
  function action_register($param = '') {
    global $db;
	global $user;
	
    if( $this->form->SubmittedOK() ) {
      if($param['pass'] == $param['pass2']) {
        if($user->Register($param['user'], $param['pass'])) {
          header('Location: index.php');
	    } else {
	      echo "Registration error!";
	    }
	  } else {
	    echo "Passwords must match!";
	  }
    }
  }
  
}

?>

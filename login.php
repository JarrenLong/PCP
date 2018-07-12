<?php
/**
 * Name:        Jarren Long
 * Date:        1/15/2012
 * Assignment:  PHP Navigation
 * Class:       CIS 230 PHP
 * Description: Login page for Long Technical, Co.
 */


include('./templates/config.inc.php');
//Include widgets used by this page
include('./templates/login.php');
include('./templates/static.php');

//Set the page's title
$page = new Page();
$page->SetTitle('Login');

$login = new Login('Login');
$login->RegisterActions();

$sp = new StaticPage('Contact Us');
$sp->SetPage('contact.php');

$page->AddWidget($login);
$page->AddWidget($sp);

if(isset($_GET['opt'])) {
  $params = $_GET;
} else if(isset($_POST['opt'])) {
  $params = $_POST;
}
//And render the page
$page->Render($params);
?>

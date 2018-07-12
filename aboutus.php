<?php
/**
 * Name:        Jarren Long
 * Date:        1/15/2012
 * Assignment:  PHP Navigation
 * Class:       CIS 230 PHP
 * Description: About Us page for Long Technical, Co.
 */

include('./templates/config.inc.php');
//Include widgets used by this page
include('./templates/static.php');

//Set the page's title
$page = new Page();
$page->SetTitle('About Us');

//This page just holds some static text
$sp = new StaticPage('About Us');
$sp->SetPage('aboutus.php');

//TODO: Add contact widget here when completed
//$contact = new ContactWidget('Contact Us');

$page->AddWidget($sp);
//$page->AddWidget($contact);

//And render the page
$page->Render($_GET);
?>

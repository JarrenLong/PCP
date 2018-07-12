<?php
/**
 * Name:        Jarren Long
 * Date:        1/15/2012
 * Assignment:  PHP Navigation
 * Class:       CIS 230 PHP
 * Description: Header section for Long Technical, Co.
 */
 function DrawSiteHeader() {
   global $user;

?>
<!DOCTYPE html>
<!-- <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"> -->
<!-- Start of Header -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="content-type" content="text/html;charset=utf-8" />
  <meta name="author" content="Jarren Long" />
  <meta name="description" content="All-hours onsite PC maintainence and repair" />
  <meta name="keywords" content="Long Technical, about, products, newsletter, blog, calendar, articles" />
  <title><?php echo PCP_SITE_NAME; if(PCP_PAGE_TITLE != '') echo ' | ' . PCP_PAGE_TITLE; ?></title>
  <script type="text/javascript" src="js/prototype.js"></script>
  <script type="text/javascript" src="js/scriptaculous.js?load=effects,builder"></script>
  <script type="text/javascript" src="js/lightbox.js"></script>
  <link rel="stylesheet" type="text/css" href="css/style.css" media="screen" />
  <link rel="shortcut icon" href="favicon.ico" />
</head>
<body>

<div id="wrap">
  <div id="top">
    <h2><a href="index.php" title="Back to main page"><?php echo PCP_SITE_NAME; ?></a></h2>
    <div id="menu">
      <ul>
        <li><a href="aboutus.php">About Us</a></li>
        <li><a href="products.php">Products</a></li>
        <li><a href="blogs.php">Blog</a></li>
        <li><a href="calendar.php">Calendar</a></li>
        <li><a href="articles.php">Articles</a></li>
      </ul>
    </div>
  </div>

  <div class="right">
<?php
   if(isset($user) && $user->LoggedIn()) {
     //echo "Hi <a href=\"user.php?name=" . $user->GetUsername() . "\">" . $user->GetUsername() . "!</a> | <a href=\"login.php?opt=logout\">Logout</a>";
     echo "    Hi <a href=\"account.php\">" . $user->GetUsername() . "!</a> | " . Widget::HardLinkToAction('login', 'logout', "Logout") . "\n";
   } else {
     //echo "    <a href=\"login.php\">Login</a> | <a href=\"login.php?opt=signup\">Sign Up</a>\n";
	 echo "    <a href=\"login.php\">Login</a> | " . Widget::HardLinkToAction('login', 'signup', "Sign Up") . "\n";
   }
?>
  </div>
  <!-- End of Header -->

  <!-- Start of Content -->
<div id="content">
<?php
   if(PCP_PAGE_TITLE != '') {
     echo '  <h2>' . PCP_PAGE_TITLE . '</h2>';
   }
}
?>
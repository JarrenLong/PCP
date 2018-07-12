<?php
/**
 * Name:        Jarren Long
 * Date:        1/15/2012
 * Assignment:  PHP Navigation
 * Class:       CIS 230 PHP
 * Description: Site map for Long Technical, Co.
 */


include('./templates/config.inc.php');

define("PCP_PAGE_TITLE", "Sitemap");
DrawSiteHeader();

?>

<div id="left">
  <ul>
	<li><a href="aboutus.php">About Us</a></li>
	<li><a href="products.php">Products</a></li>
	<li><a href="blog.php">Blog</a></li>
	<li><a href="calendar.php">Calendar</a></li>
	<li><a href="articles.php">Articles</a></li>
  </ul>
</div>

<?php DrawSiteFooter(); ?>

<?php
/**
 * Name:        Jarren Long
 * Date:        1/15/2012
 * Assignment:  PHP Navigation
 * Class:       CIS 230 PHP
 * Description: Blog page for Long Technical, Co.
 */

include('./templates/config.inc.php');
//Include widgets used by this page
include('./templates/blogs.php');

//Set the page's title
$page = new Page();
$page->SetTitle('Company Blog');

//Load the Blogs widget
$blog = new Blogs('blogs');
$blog->RegisterActions(array('new','edit','update','save','del','comment','save_comment'));
$blog->RegisterTableFields(array('id','created_date','author','title','content'), 0, 2);
$blog->RegisterNewFields(array('author','title','content'));
$blog->RegisterEditFields(array('author','title','content'));

//Extend Blogs for comment support
$comments = new Comments($blog->class_name, $blog->guid, 'Comment');

$page->AddWidget($blog);
$page->AddWidget($comments);

//And render the page
$page->Render($_GET);
/*
?>

<!--
 <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam rutrum elit at arcu tristique vitae ullamcorper risus accumsan. Nullam non enim sed mi aliquet dapibus sit amet quis urna. Vestibulum pharetra, lacus at pretium commodo, metus felis vulputate arcu, vitae tempor est odio at est. Vestibulum justo nisl, interdum vel ultrices a, ultrices vitae diam. Curabitur nec lacus est, sit amet ultricies ligula. Cras consectetur consequat est, ac imperdiet lacus eleifend quis. Etiam dapibus turpis nec tortor congue eget sodales velit porttitor.</p>
 <p>Vestibulum at ligula sit amet nisi ornare feugiat quis eget est. Proin sed dui turpis, in lobortis tortor. Phasellus ac felis eget leo sagittis pharetra. Cras malesuada tortor sed dui feugiat imperdiet et sit amet sem. In non sapien lacus, ut fermentum nulla. In non elit ut magna suscipit venenatis. Fusce imperdiet tincidunt porttitor. Donec eget laoreet purus. Sed mattis, purus non fringilla fermentum, elit lorem vehicula quam, eget consequat lacus justo nec mi. Praesent vel neque eros. Mauris at odio velit. Donec posuere commodo posuere. </p>

 <h3>Recent Posts</h3>
 <div class="box">
  <h2>SharpTouch - MultiTouch screen driver</h2>
  <p>
   As part of the SCC Computer Club, I joined the "MultiTouch table"
   team. Together, we have built a (roughly) 30"x30" FTIR display using
   instructions found online. The original instructions specify the use
   of a modified PS3 Eye camera for the IR detector but, being the guy I
   am, we have decided to use a cheap, off-the-shelf Logitech camera instead.
   Of course, since the original project uses the PS3 Eye cam drivers, we
   didn't have any way to utilize the data streaming from the Logitech camera.
   Well, now we do, because I wrote them (in C#). This library uses the
   Windows DirectShow to access the camera, performs all of the image processing,
   and spits out the touch points as mouse coordinates that can then later be used
   by the built-in mouse driver (one oint at a time), or by our custom apps,
   which can utilize multiple click spots at once.
  </p>
  <ul><li><a href="#">Download Project</a></li></ul>
 </div>

  <div class="box">
  <h2>SoHAM! - SOHO Account Manager</h2>
  <p>
   Ever wanted to start a business, but didn't want to deal with the piles of paper?
   This app is intended to be an all-in-one business manager, which can handle:
   </p>
   <ul>
    <li>Accounts Payable/Receivable</li>
	<li>Inventory management</li>
	<li>Employee scheduling/payroll</li>
	<li>Event scheduling</li>
	<li>Public company website/eCommerce</li>
   </ul>
   <p>
   While providing a clean, user-friendly interface. The main software is designed
   to run on a HTTP/SQL server, which allows for easy integration with the Android app
   in development along with it.
  </p>
  <ul><li><a href="#">Download Project</a></li></ul>
 </div>

 <div class="box">
  <h2>XAPI - (.NET framework P/Invoke example)</h2>
  <p>
   This project was intended to be an example of how to call C/C++ code from
   C# libraries using the PInvoke framework. The code is a very minimal
   implementation that demonstrates the use of function calls, delegates,
   and passing data to and from the C/C++ library.
  </p>
  <ul><li><a href="#">Download Project</a></li></ul>
 </div>

 <h3>Older Posts</h3>
 <div class="box">
  <h2>RegeditCE - Registry Editor for Windows CE</h2>
  <p>
   I got really tired of having to use the Remote Registry Editor included
   with Visual Studio to view the changes I was making to the Registry, so
   I whipped up this little bad boy. The GUI is ugly (usually is when you
   only have 240x320px of real estate to work with), but it works like a dream!
   Full support for creating, editing, and deleting registry keys and values
   on Windows CE 4.2 (also works on Windows XP and later).
  </p>
  <ul><li><a href="#">Download Project</a></li></ul>
 </div>
 
 <div class="box">
  <h2>Sonic Racing - CIS 256 Final Project</h2>
  <p>
   Our final project for my C# class was to create a horse racing game. I stuck to the project requirements, but changed the theme of it to something a bit more fun...Sonic the Hedgehog and friends! Grade: 4.0
  </p>
  <ul><li><a href="#">Download Project</a></li></ul>
 </div>
 
 <div class="box">
  <h2>Bluetooth Object Exchange (OBEX) Library</h2>
  <p>
   This is a simple implementation of the Bluetooth Object Exchange protocol
   in C#, with basic FTP over Bluetooth support. This was written while I was
   studying the inner workings of Bluetooth, and has been tested on Windows
   XP, 7, and CE 4.2 platforms using internal and external Bluetooth radios.</p>
  <ul><li><a href="#">Download Project</a></li></ul>
 </div>
-->
*/
?>

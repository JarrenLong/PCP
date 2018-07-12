<?php
/**
 * Name:        Jarren Long
 * Date:        1/15/2012
 * Assignment:  PHP Navigation
 * Class:       CIS 230 PHP
 * Description: Products page for Long Technical, Co.
 */

include('./templates/config.inc.php');
//Include widgets used by this page
include('./templates/products.php');

//Set the page's title
$page = new Page();
$page->SetTitle('Products and Services');

//Load the Products widget
$p = new Products('products');
$p->RegisterActions(array('new','edit','update','save','del'));
$p->RegisterTableFields(array('id','name','description','qty','price','cost','image_path'), 0, 4);
$p->RegisterNewFields(array('name','description','qty','price','cost','image_path'));
$p->RegisterEditFields(array('name','description','qty','price','cost','image_path'));

//"Comments" extends the Products widget by adding new actions
//For Reviews (comment) management. We will call these comments 'Review's
$comments = new Comments($p->class_name, $p->guid, 'Review');

$page->AddWidget($p);
$page->AddWidget($comments);

//And render the page
$page->Render($_GET);
?>
<!--
 <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam rutrum elit at arcu tristique vitae ullamcorper risus accumsan. Nullam non enim sed mi aliquet dapibus sit amet quis urna. Vestibulum pharetra, lacus at pretium commodo, metus felis vulputate arcu, vitae tempor est odio at est. Vestibulum justo nisl, interdum vel ultrices a, ultrices vitae diam. Curabitur nec lacus est, sit amet ultricies ligula. Cras consectetur consequat est, ac imperdiet lacus eleifend quis. Etiam dapibus turpis nec tortor congue eget sodales velit porttitor.</p>
 <p>Vestibulum at ligula sit amet nisi ornare feugiat quis eget est. Proin sed dui turpis, in lobortis tortor. Phasellus ac felis eget leo sagittis pharetra. Cras malesuada tortor sed dui feugiat imperdiet et sit amet sem. In non sapien lacus, ut fermentum nulla. In non elit ut magna suscipit venenatis. Fusce imperdiet tincidunt porttitor. Donec eget laoreet purus. Sed mattis, purus non fringilla fermentum, elit lorem vehicula quam, eget consequat lacus justo nec mi. Praesent vel neque eros. Mauris at odio velit. Donec posuere commodo posuere. </p>
 
 <h3>Products Offered</h3>
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

 <h3>Services Offered</h3>
 <div class="box">
  <h2>PC Support</h2>
  <table>
<tbody>
<tr>
<td><strong>Consulting Services</strong></td>
<td><strong>Price ($)</strong></td>
<td></td>
</tr>
<tr>
<td><a title="Diagnostic" href="#diagnostic">PC Consulting Services</a></td>
<td style="text-align: center;">29.99</td>
<td></td>
</tr>
<tr>
<td><a title="Diagnostic" href="#diagnostic">Web Consulting Services</a></td>
<td style="text-align: center;">49.99</td>
<td></td>
</tr>
<tr>
<td><a title="Diagnostic" href="#diagnostic">Software Consulting Services</a></td>
<td style="text-align: center;">64.99</td>
<td></td>
</tr>
<tr>
<td></td>
<td></td>
<td></td>
</tr>
<tr>
<td></td>
<td></td>
<td></td>
</tr>
<tr>
<td><strong>Repair Services</strong></td>
<td><strong>Price ($)</strong></td>
<td><strong><strong>Notes - Prices don't include tax</strong></strong></td>
</tr>
<tr>
<td><a title="Diagnostic" href="#diagnostic">Basic System Diagnostic</a></td>
<td style="text-align: center;">39.99</td>
<td></td>
</tr>
<tr>
<td><a title="Tuneup" href="#tuneup">Basic System Tuneup</a></td>
<td style="text-align: center;">59.99</td>
<td></td>
</tr>
<tr>
<td><a title="Backup" href="#backup">Personal Files Backup</a></td>
<td style="text-align: center;">39.99</td>
<td></td>
</tr>
<tr>
<td><a title="Software Installation" href="#softinstall">Software Installation</a></td>
<td></td>
<td></td>
</tr>
<tr>
<td><a title="Hardware Install" href="#hardinstall">Hardware Installation**</a></td>
<td></td>
<td></td>
</tr>
<tr>
<td style="text-align: right;">1 Device</td>
<td style="text-align: center;">29.99</td>
<td></td>
</tr>
<tr>
<td style="text-align: right;">2 Devices</td>
<td style="text-align: center;">54.99</td>
<td></td>
</tr>
<tr>
<td style="text-align: right;">3 Devices</td>
<td style="text-align: center;">69.99</td>
<td></td>
</tr>
<tr>
<td style="text-align: right;">4 Devices</td>
<td style="text-align: center;">79.99</td>
<td></td>
</tr>
<tr>
<td style="text-align: right;">5+ Devices</td>
<td style="text-align: center;">99.99</td>
<td></td>
</tr>
<tr>
<td><a title="OS Installation" href="#osinstall">OS Installation</a></td>
<td></td>
<td>Does not include OS or Install Media</td>
</tr>
<tr>
<td style="text-align: right;">Desktop</td>
<td style="text-align: center;">99.99</td>
<td></td>
</tr>
<tr>
<td style="text-align: right;">Server</td>
<td style="text-align: center;">199.99</td>
<td></td>
</tr>
<tr>
<td><a title="OS Install Media" href="#osdisk">Linux OS Install Media</a></td>
<td></td>
<td>CD or DVD</td>
</tr>
<tr>
<td style="text-align: right;">First 3 disks (each)</td>
<td style="text-align: center;">3.99</td>
<td></td>
</tr>
<tr>
<td style="text-align: right;">Additional disks</td>
<td style="text-align: center;">1.99</td>
<td></td>
</tr>
<tr>
<td><a title="Disk Wipe/Recondition" href="#wipe">Disk Wipe/Recondition</a></td>
<td style="text-align: center;">74.99</td>
<td></td>
</tr>
<tr>
<td><a title="Disk Wipe/Recondition" href="#wipe">Secure Physical Disk Destruction</a></td>
<td style="text-align: center;">49.99</td>
<td></td>
</tr>
<tr>
<td><a title="Disk Wipe/Recondition" href="#wipe">Data Recovery</a></td>
<td></td>
<td></td>
</tr>
<tr>
<td style="text-align: right;">Hard Disk (IDE &amp; SATA) - Basic</td>
<td style="text-align: center;">99.99</td>
<td>*49.99 if data is unrecoverable</td>
</tr>
<tr>
<td style="text-align: right;">Hard Disk (IDE &amp; SATA) - Intensive</td>
<td style="text-align: center;">499.99</td>
<td>*199.99 if data is unrecoverable</td>
</tr>
<tr>
<td style="text-align: right;">Flash Memory - Basic</td>
<td style="text-align: center;">149.99</td>
<td>*49.99 if data is unrecoverable</td>
</tr>
<tr>
<td style="text-align: right;">Flash Memory - Intensive</td>
<td style="text-align: center;">299.99</td>
<td>*124.99 if data is unrecoverable</td>
</tr>
<tr>
<td><a title="Extended Trip Fee" href="#trip">Extended Trip Fee</a></td>
<td style="text-align: center;">0.49/mi.</td>
<td>On-Site visits greater than 20 mi. from store</td>
</tr>
<tr>
<td><a title="Extended Visit Fee" href="#visit">Extended Visit Fee</a></td>
<td style="text-align: center;">19.99/hr.</td>
<td style="text-align: left;">On-Site visits lasting more than 3 hours</td>
</tr>
</tbody>
</table>

<br/>
<br/>

<p>
  <strong><a id="diagnostic">Basic System Diagnostic</a></strong> - This is our most
    common repair service, and is usually included with every consult.
	A basic system diagnostic is used to document any problems that
	are found on a device (software or hardware) and recommend solutions
	to them.
</p>
<p>
  <strong><a id="tuneup">Basic System Tuneup</a></strong> - A basic system tuneup
    takes the information gathered from the customer and/or system
	diagnostic and uses it to implement solutions to the problems found.
</p>
<p>
  <strong><a id="backup">Personal Files Backup</a></strong> - Personal file backups
    are performed prior to any work that may compromise the integrity
	of data stored on a system. This is always recommended prior to the
	installation/upgrade of Operating Systems and major service
	packs/bug fixes. We also recommend performing regularly scheduled
	monthly backups, in case of system failure. First three CD/DVDs
	are included, additional disks are $1.99/ea.
</p>
<p>
  <strong><a id="softinstall">Software Installation</a></strong> - We can install and
    configure any legally licensed software title on your system. Price
	does not include the title itself, though we are more than happy to
	use our resources to find you the best deal and acquire the title
	for you (at your expense).
</p>
<p>
  <strong><a id="hardinstall">Hardware Installation</a></strong> - While most companies
    charge different rates based on the type of hardware installed, we
	feel like that's dirty pool. Prices shown are applicable to any
	hardware device (internal or external) that is compatible with
	your system, and are rated on a sliding scale based on the number
	of parts being installed. Full PC system construction will never
	cost more than the cost of 5+ devices, as shown above.
</p>
<p>
  <strong><a id="osinstall">OS Installation</a></strong> - Like our software installation
    service, we can install and configure operating systems on your device,
	and are more than happy to use our resources to find you the best
	deals on Windows operating systems.
</p>
<p>
  <strong><a id="osdisk">Linux OS Install Media</a></strong> - If you would like to
    use a variation of Linux as your operating system, we offer installation
	disks (CD or DVD) for next-to-nothing, for those people who do not want
	to hassle with downloading and burning the disks themselves (almost all
	Linux variations are FOSS software).
</p>
<p>
  <strong><a id="wipe">Disk Wipe/Recondition</a></strong> - A Disk wipe/recondition
    is used to securely remove data from a used hard drive in a manner that
	it will be suitable for resale or disposal. This recommended if you are
	selling/disposing of any computer containing sensitive data.
</p>
<p>
  <strong><a id="trip">Extended Trip Fee</a></strong> - This is a rare charge that
    is only incurred if the work site is more than twenty (20) miles from
	our main office.
</p>
<p>
  <strong><a id="visit">Extended Visit Fee</a></strong> - Like our Extended Trip fee,
    the Extended Visit fee is only charged if we have been on the work site
	for more than 3 hours at a time.
</p>
<p>**All unpaid accounts will accrue a 5%/day surcharge after 30 days**</p>
  <ul><li><a href="#">Download Project</a></li></ul>
 </div>
-->


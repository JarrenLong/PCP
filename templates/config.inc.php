<?php

/*
 * PCP Site Configuration - DO NOT EDIT MANUALLY!!! These values can (will) be
 *    modified on the Admin Site Portal page (/admin.php)!
 */
// Database host address
define( "PCP_DB_HOST", "cis230.cis3scc.com" );
// DB username for SQL access
define( "PCP_DB_USER", "scc_jlong" );
// DB password for SQL access
define( "PCP_DB_PASS", "820236732" );
// SQL database to use
define( "PCP_DB_NAME", "jlong_cis3scc_com" );


// Name of this website
define( "PCP_SITE_NAME", "Long Technical Co.");
// URL of this website (leave off the protocol)
define( "PCP_SITE_URL", "long-technical.com");

//TODO: Unimplemented
// Send validation email to confirm user email address before registration?
//define( "PCP_EMAIL_VALIDATE", true);
// Send welcome email when new user registers?
//define( "PCP_EMAIL_WELCOME", true);

//Group ID=>Name mappings
define( "PCP_GROUP_MEMBER", 0);
define( "PCP_GROUP_MODERATOR", 1);
define( "PCP_GROUP_ADMIN", 2);


//PCP Core Requirements
require_once('Util.php');
require_once('Database.class.php');
require_once('Session.class.php');
require_once('User.class.php');
require_once('Processor.class.php');
require_once('Form.class.php');
require_once('Widget.class.php');
require_once('Page.class.php');

//Global variables go here
$db = new Database( PCP_DB_HOST, PCP_DB_USER, PCP_DB_PASS, PCP_DB_NAME );
//Initiate a new guest user for first load
$session = new Session();
$user = new User();
?>

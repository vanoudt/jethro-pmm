<?php
// Database details - you need to replace at least USERNAME, PASSWORD and DB_NAME for both of these.
// The Private DSN is used for the main system that users log in to.
// For enhanced security you can use a different database user for the Public DSN
// and only grant reduced access privileges to that user (eg only SELECT priveleges, only certain tables)
// Ref: http://en.wikipedia.org/wiki/Database_Source_Name
define('PRIVATE_DSN', "mysql://root:n03ntryh3r3@localhost/cometbay_chms");
define('PUBLIC_DSN',  PRIVATE_DSN);
define('MEMBERS_DSN', PRIVATE_DSN);
?>

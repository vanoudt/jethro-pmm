<?php
/* Settings Type:
 * 1 = text
 * 2 = option
 * 3 = multichoice
 * 4 = number
 */
// Set up the DB
if (!defined('DSN')) define('DSN', constant('PRIVATE_DSN'));

if (!@include_once('MDB2.php')) {
	trigger_error('MDB2 Library not found on the server.  See the readme file for how to work around this');
	exit();
}
$GLOBALS['db'] =& MDB2::factory(DSN);
if (MDB2::isError($GLOBALS['db']) || MDB2::isError($GLOBALS['db']->getConnection())) {
	trigger_error('Could not connect to database - please check for mistakes in your DSN in conf.php, and check in MySQL that the database exists and the specified user has been granted access.', E_USER_ERROR);
	exit();
}

$GLOBALS['db']->setOption('portability', $GLOBALS['db']->getOption('portability') & !MDB2_PORTABILITY_EMPTY_TO_NULL);
$GLOBALS['db']->setFetchmode(MDB2_FETCHMODE_ASSOC);

// define all database settings
$db = $GLOBALS['db'];
$sql = "SELECT * FROM settings;";
$res = $db->queryAll($sql, null, null, true, true);
foreach ($res as $settingid => $setting) {
  if (!$setting['value']) {
    define($setting['setting'], '');
  } else {
    define($setting['setting'], $setting['value']);
  }
}


if (defined('TIMEZONE') && constant('TIMEZONE')) {
	date_default_timezone_set(constant('TIMEZONE'));
	$GLOBALS['db']->query('SET time_zone = "'.date('P').'"');
}

function definetodb($name, $value) {
  if (defined($name)) {    
    save_setting($name, $value);
  } else {
    echo "EROR: NOT DEFINED IN DB $name\n";
    insert_setting(0,$name, $value, '');
    define($name,$value);
  }
}

function insert_setting($category,$name,$value,$description) {
  $db = $GLOBALS['db'];
  $statement = $db->prepare('INSERT INTO `settings` (category,setting,value,title,description,sort_order,type) VALUES (?,?,?,?,?,0,1)', array('integer','text','text','text','text'), MDB2_PREPARE_MANIP);
  $data = Array($category, $name, $value, $name, $description);
  $resultcount = $statement->execute($data);   
  
}

function save_setting($name, $value) {
  $db = $GLOBALS['db'];
  $statement = $db->prepare('UPDATE `settings` SET category=?,value=? WHERE setting=?', array('integer','text','text'), MDB2_PREPARE_MANIP);
  $data = Array(0, $value,$name);
  $resultcount = $statement->execute($data); 
}


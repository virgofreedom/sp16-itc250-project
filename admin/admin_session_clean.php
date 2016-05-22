<?php 
/**
 * admin_session_clean.php triggers garbage collection phase of PHP sessions to clear old session data
 *
 * This is an admin only page that can be linked within your administrative area.  Clicking on the 
 * link to this page clears old session data, and provides feedback on the number of records thus 
 * effected (deleted).
 *
 * @package nmSession
 * @author Bill Newman (via Larry Ullman) <williamnewman@gmail.com>
 * @version 1.21 2010/07/26
 * @link http://www.newmanix.com/ 
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @see session_db_inc.php 
 * @todo none
 */

require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials  
 
$PageTitle = smartTitle(); #Fills <title> tag. If left empty will default to $PageTitle in config_inc.php 
$meta_robots = 'no index, no follow';#never index admin pages
$collection_duration = 24; #Length of time in hours a session is allowed to be

//END CONFIG AREA ----------------------------------------------------------
$collection_seconds = $collection_duration * 3600; #multiply hours x 3600 seconds/hour

$access = "developer"; #admin or higher level can view this page
include_once INCLUDE_PATH . 'admin_only_inc.php'; #session protected page - level is defined in $access var 

get_header(); #defaults to header_inc.php
?>
<div align="center"><h3>Clean Session Data</h3></div>
<p>This page will attempt to clear any session data that is more than <b><?=$collection_duration;?></b> hours old.</p>
<p>The number of sessions successfully cleared out will be identified below.</p>
<p>If there is no session data older than <b><?=$collection_duration;?></b> hours, a message will be generated that zero sessions were cleared.</p>
<p>This page will only work if sessions are being stored currently in your database instead of the file system.</p>  
<?php 
startSession(); #DB requires session started
$cleaned = session_clean($collection_seconds); #forces clearing of old sessions
echo '<p align="center"><b>' . $cleaned . '</b> sessions cleaned! (eliminated from database)</p>';
echo '<div align="center"><a href="' . $config->adminDashboard . '">Exit To Admin</a></div>';
get_footer(); #defaults to footer_inc.php
?>
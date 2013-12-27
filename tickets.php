<?php
define("IN_MYBB", 1);
define("THIS_SCRIPT", "tickets.php");

require "global.php";

$is_master = is_member($mybb->settings['tickets_usergroups']);

echo "TODO!<br />";
if($is_master)
    echo "Master";
else
	echo "Slave";
?>
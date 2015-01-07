<?php
define("IN_MYBB", 1);
define("THIS_SCRIPT", "tickets.php");

require "global.php";

if(!function_exists("tickets_info"))
{
	$lang->load("tickets");
	error($lang->tickets_deactivated);
}

if($mybb->user['uid'] == 0)
	error_no_permission();

$is_master = is_member($mybb->settings['tickets_usergroups']);

add_breadcrumb($lang->toplinks_tickets, "tickets.php");

$m = new JB_Modules();
$m->loadModule();
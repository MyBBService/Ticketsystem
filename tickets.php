<?php
define("IN_MYBB", 1);
define("THIS_SCRIPT", "tickets.php");

require "global.php";

if(!function_exists("tickets_info"))
    die("Ticketsystem deaktiviert");

if($mybb->user['uid'] == 0)
    error_no_permission();

$is_master = is_member($mybb->settings['tickets_usergroups']);

add_breadcrumb($lang->toplinks_tickets, "tickets.php");

if($mybb->input['action'] == "do_add" && $mybb->request_method == "post") {
	verify_post_check($mybb->input['my_post_key']);
	
	if(empty($mybb->input['subject']))
	    $errors[] = "Keinen Titel angegeben";
	if(empty($mybb->input['ticket']))
		$errors[] = "Kein Ticket angegeben";

	if(!isset($errors)) {
		$ticket = array(
			"subject"	=> $db->escape_string($mybb->input['subject']),
			"ticket"	=> $db->escape_string($mybb->input['ticket']),
			"creator"	=> $mybb->user['uid'],
			"date"		=> TIME_NOW,
		);
		$db->insert_query('tickets', $ticket);
		
		redirect("tickets.php", "Ticket erfolgreich erstellt");
	} else {
		$mybb->input['action'] = "add";
	}
}
if($mybb->input['action'] == "add") {
	add_breadcrumb("Neues Ticket", "tickets.php?action=add");
	$value = $ticket = "";
	
	if(isset($errors))
	{
		$errors = inline_error($errors);
		$subject = htmlspecialchars_uni($mybb->input['subject']);
		$ticket = htmlspecialchars_uni($mybb->input['ticket']);
	}

	eval("\$ticketsystem = \"".$templates->get("tickets_add")."\";");
	output_page($ticketsystem);	
}
if(!$mybb->input['action']) {
	$colspan = 3;
	$masterlink = "";
	if($is_master) {
		$colspan = 2;
		eval("\$masterlink = \"".$templates->get("tickets_masterlink")."\";");
	}
	
	$query = "SELECT t.subject, t.date, COUNT(a.id) as answers
			 FROM ".TABLE_PREFIX."tickets t
			 LEFT JOIN ".TABLE_PREFIX."tickets_answers a ON (a.ticket=t.id)
			 WHERE closed='0' AND creator='".$mybb->user['uid']."'
			 GROUP BY t.id
 			 ORDER BY t.date DESC";
	$query = $db->query($query);
	
	if($db->num_rows($query) != 0) {
		while($ticket = $db->fetch_array($query)) {
			$ticket['date'] = my_date($mybb->settings['dateformat'], $ticket['date'])." ".my_date($mybb->settings['timeformat'], $ticket['date']);
			eval("\$tickets .= \"".$templates->get("tickets_table")."\";");
		}
	} else {
		eval("\$tickets = \"".$templates->get("tickets_table_nothing")."\";");		
	}

	eval("\$ticketsystem = \"".$templates->get("tickets")."\";");
	output_page($ticketsystem);
}
?>
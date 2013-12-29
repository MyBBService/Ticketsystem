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

if($mybb->input['action'] == "do_answer" && $mybb->request_method == "post") {
	if(empty($mybb->input['id']))
	    error("Keine ID");

	$id = (int)$mybb->input['id'];
	$query = $db->simple_select("tickets", "*", "id={$id}");
	if($db->num_rows($query) != 1)
	    error("Fehlerhafte ID");

	if(!$is_master && $ticket['creator'] != $mybb->user['uid'])
		error_no_permission();

	if($mybb->input['submit'] == "Schliessen") {
		$db->update_query("tickets", array("closed" => 1), "id={$id}");
		redirect("tickets.php?action=view&view={$id}", "Ticket geschloßen");
	}

	if(empty($mybb->input['answer']))
		redirect("tickets.php?action=view&view={$id}");

	$insert = array(
		"uid" => $mybb->user['uid'],
		"ticket" => $id,
		"answer" => $mybb->input['answer'],
		"date" => TIME_NOW
	);
	$db->insert_query("tickets_answers", $insert);
	redirect("tickets.php?action=view&view={$id}", "Antwort erfolgreich gespeichert");
}
if($mybb->input['action'] == "view") {
	if(empty($mybb->input['view']))
	    error("Keine ID");
	    
	$id = (int)$mybb->input['view'];
	$query = $db->simple_select("tickets", "*", "id={$id}");
	if($db->num_rows($query) != 1)
	    error("Fehlerhafte ID");
	$ticket = $db->fetch_array($query);
	$user = get_user($ticket['creator']);
	$ticket['uid'] = $ticket['creator'];
	$ticket['creator'] = build_profile_link($user['username'], $ticket['creator']);
	$ticket['date'] = my_date($mybb->settings['dateformat'], $ticket['date'])." ".my_date($mybb->settings['timeformat'], $ticket['date']);
	
	if($is_master && $ticket['uid'] != $mybb->user['uid'])
		add_breadcrumb("Admin Bereich", "tickets.php?action=master");
	elseif($ticket['uid'] != $mybb->user['uid'])
	    error_no_permission();
    add_breadcrumb("Ticket: {$ticket['subject']}", "tickets.php?action=view&view={$id}");
    
	$query = "SELECT a.uid, a.date, a.answer, u.username
			 FROM ".TABLE_PREFIX."tickets_answers a
			 LEFT JOIN ".TABLE_PREFIX."users u ON (u.uid=a.uid)
			 WHERE a.ticket={$id}
 			 ORDER BY a.date DESC";
    $query = $db->query($query);
    while($answer = $db->fetch_array($query)) {
		$answer['creator'] = build_profile_link($answer['username'], $answer['creator']);
		$answer['date'] = my_date($mybb->settings['dateformat'], $answer['date'])." ".my_date($mybb->settings['timeformat'], $answer['date']);
	   	eval("\$answers .= \"".$templates->get("tickets_view_answers")."\";");
	}
    
   	eval("\$ticketsystem = \"".$templates->get("tickets_view")."\";");
	output_page($ticketsystem);
}
if($mybb->input['action'] == "master") {
	if(!$is_master) {
		error_no_permission();
	}
	
	add_breadcrumb("Admin Bereich", "tickets.php?action=master");
	
	$where = "closed='0'";
	if($mybb->input['closed'] == "1")
	    $where .= " OR closed='1'";

	$query = "SELECT t.id, t.subject, t.creator, t.date, u.username, COUNT(a.id) as answers
			 FROM ".TABLE_PREFIX."tickets t
			 LEFT JOIN ".TABLE_PREFIX."tickets_answers a ON (a.ticket=t.id)
			 LEFT JOIN ".TABLE_PREFIX."users u ON (u.uid=t.creator)
			 WHERE {$where}
			 GROUP BY t.id
 			 ORDER BY t.date DESC";
	$query = $db->query($query);

	if($db->num_rows($query) != 0) {
		while($ticket = $db->fetch_array($query)) {
			$ticket['creator'] = build_profile_link($ticket['username'], $ticket['creator']);
			$ticket['date'] = my_date($mybb->settings['dateformat'], $ticket['date'])." ".my_date($mybb->settings['timeformat'], $ticket['date']);
			eval("\$tickets .= \"".$templates->get("tickets_master_table")."\";");
		}
	} else {
		eval("\$tickets = \"".$templates->get("tickets_master_table_nothing")."\";");
	}

	eval("\$ticketsystem = \"".$templates->get("tickets_master")."\";");
	output_page($ticketsystem);
}
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
	$colspan = 2;
	$masterlink = "";
	if($is_master) {
		$colspan = 1;
		eval("\$masterlink = \"".$templates->get("tickets_masterlink")."\";");
	}
	
	$where = "(closed='0'";
	if($mybb->input['closed'] == 1)
	    $where .= " OR closed='1'";
	$where .= ")";
	
	$query = "SELECT t.id, t.subject, t.date, COUNT(a.id) as answers
			 FROM ".TABLE_PREFIX."tickets t
			 LEFT JOIN ".TABLE_PREFIX."tickets_answers a ON (a.ticket=t.id)
			 WHERE {$where} AND creator='".$mybb->user['uid']."'
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
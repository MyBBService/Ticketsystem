<?php

class Module_Answer extends JB_Module_Base
{
	function post()
	{
		global $mybb, $lang, $errors, $is_master;

		if(empty($mybb->input['id']))
			error($lang->no_id);

		$ticket = MyBBS_Tickets_Ticket::getById($mybb->input['id']);
		if($ticket === false)
			error($lang->wrong_id);
		
		if(!$is_master && $ticket->uid != $mybb->user['uid'])
			error_no_permission();
		
		if($mybb->input['submit'] == $lang->ticket_close)
		{
			$ticket->closed = 1;
			$ticket->save();
			redirect("tickets.php", $lang->ticket_closed);
		}
		
		$answer = $ticket->createAnswer($mybb->input['answer']);
		if(!$answer->validate())
			redirect("tickets.php?action=view&view={$ticket->id}");

		$answer->save();

		// Trigger the Alert for the new answer
		$extra = array(
			"link" => "tickets.php?action=view&view={$ticket->id}",
			"lang_data" => $ticket->subject
		);
		JB_Alerts::trigger("tickets", "new_answer", $ticket->getRecipients(), $extra);

		redirect("tickets.php?action=view&view={$ticket->id}", $lang->ticket_answered);
	}

	function get() {}
}

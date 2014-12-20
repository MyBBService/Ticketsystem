<?php

class Module_View extends JB_Module_Base
{
	public $post = false;

	function get()
	{
		global $lang, $templates, $theme, $articles, $plugins, $mybb, $is_master, $headerinclude, $header, $footer;

		if(empty($mybb->input['view']))
			error($lang->no_id);
	
		$ticket = MyBBS_Tickets_Ticket::getById($mybb->input['view']);
		if($ticket === false)
			error($lang->wrong_id);

		$ticket->creator = JB_Helpers::formatUser($ticket->uid);
		$ticket->date = JB_Helpers::formatDate($ticket->dateline);
		$ticket->subject = e($ticket->subject);
		$ticket->ticket = e($ticket->ticket);
		$lockimg = $do_answer = "";
		if($ticket->closed == 1)
			$lockimg = "<img src=\"images/lock.gif\" alt=\"[Lock]\" /> ";
		else
			eval("\$do_answer = \"".$templates->get("tickets_answer_form")."\";");
	
		if($is_master && $ticket->uid != $mybb->user['uid'])
			add_breadcrumb($lang->tickets_admin, "tickets.php?action=master");
		elseif($ticket->uid != $mybb->user['uid'])
			error_no_permission();
		add_breadcrumb($lang->ticket.": {$ticket->subject}", "tickets.php?action=view&view={$ticket->id}");
	
		$answerss = $ticket->getAnswers();
		foreach($answerss as $answer)
		{
			$answer->creator = JB_Helpers::formatUser($answer->uid);
			$answer->date = JB_Helpers::formatDate($answer->dateline);
			$answer->answer = e($answer->answer);
			eval("\$answers .= \"".$templates->get("tickets_view_answers")."\";");
		}
	
		eval("\$ticketsystem = \"".$templates->get("tickets_view")."\";");
		output_page($ticketsystem);
	}
}
<?php

class Module_Master extends JB_Module_Base
{
	public $post = false;

	function get()
	{
		global $lang, $templates, $theme, $articles, $plugins, $mybb, $is_master, $headerinclude, $header, $footer;

		if(!$is_master)
			error_no_permission();

		add_breadcrumb($lang->tickets_admin, "tickets.php?action=master");
	
		if($mybb->input['closed'] == 1)
			$ticketss = MyBBS_Tickets_Ticket::getAllWithClosed();
		else
			$ticketss = MyBBS_Tickets_Ticket::getAll();
	
		if(count($ticketss) > 0)
		{
			foreach($ticketss as $ticket)
			{
				$lockimg = "";
				if($ticket->closed == 1)
					$lockimg = "<img src=\"images/lock.gif\" alt=\"[Lock]\" /> ";
				$ticket->creator = JB_Helpers::formatUser($ticket->uid);
				$ticket->date = JB_Helpers::formatDate($ticket->dateline);
				$ticket->subject = e($ticket->subject);
				$ticket->answers = $ticket->numberAnswers();
				eval("\$tickets .= \"".$templates->get("tickets_master_table")."\";");
			}
		}
		else
		{
			eval("\$tickets = \"".$templates->get("tickets_master_table_nothing")."\";");
		}

		eval("\$ticketsystem = \"".$templates->get("tickets_master")."\";");
		output_page($ticketsystem);
	}
}
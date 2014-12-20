<?php

class Module_Index extends JB_Module_Base
{
	public $post = false;

	function get()
	{
		global $lang, $templates, $theme, $articles, $plugins, $mybb, $is_master, $colspan, $masterlink;

		$colspan = 2;
		$masterlink = "";
		if($is_master)
		{
			$colspan = 1;
			eval("\$masterlink = \"".$templates->get("tickets_masterlink")."\";");
		}
	
		if($mybb->input['closed'] == 1)
			$ticketss = MyBBS_Tickets_Ticket::getAllWithClosed("uid='{$mybb->user['uid']}'");
		else
			$ticketss = MyBBS_Tickets_Ticket::getAll("uid='{$mybb->user['uid']}'");

		if(count($ticketss) > 0)
		{
			foreach($ticketss as $ticket)
			{
				$lockimg = "";
				if($ticket->closed == 1)
					$lockimg = "<img src=\"images/lock.gif\" alt=\"[Lock]\" /> ";
				$ticket->date = JB_Helpers::formatDate($ticket->dateline);
				$ticket->subject = e($ticket->subject);
				$ticket->answers = $ticket->numberAnswers();
				eval("\$tickets .= \"".$templates->get("tickets_table")."\";");
			}
		}
		else
		{
			eval("\$tickets = \"".$templates->get("tickets_table_nothing")."\";");
		}

		return $tickets;
	}
}
<?php

class Module_Add extends JB_Module_Base
{
	function post()
	{
		global $mybb, $lang, $errors;

		$array = array(
			"subject" => $mybb->input['subject'],
			"ticket" => $mybb->input['ticket']
		);

		$ticket = MyBBS_Tickets_Ticket::create($array);
	
		if($ticket->validate())
		{
			$ticket->save();
	
			redirect("tickets.php", $lang->ticket_created);
		}
		else
		{
			$errors = $ticket->getInlineErrors();
			$this->get();
		}

	}

	function get()
	{
		global $lang, $templates, $theme, $articles, $plugins, $mybb, $errors, $headerinclude, $header, $footer;

		add_breadcrumb($lang->ticket_new, "tickets.php?action=add");
		$value = $ticket = "";
	
		if(isset($errors))
		{
			$subject = e($mybb->input['subject']);
			$ticket = e($mybb->input['ticket']);
		}
	
		eval("\$ticketsystem = \"".$templates->get("tickets_add")."\";");
		output_page($ticketsystem);
	}
}
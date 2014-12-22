<?php

class MyBBS_Tickets_Answer extends JB_Classes_StorableObject
{
	static protected $table = "tickets_answers";
	static protected $cache = array();
	static protected $timestamps = true;
	static protected $user = true;

	public function validate($hard=true)
	{
		global $lang;
		
		if(!isset($this->data['answer']) || !trim($this->data['answer']))
			return false;

		return true;
	}

	public static function getByTicket($id)
	{
		return static::getAll("ticket='{$id}'");
	}

	public function getTicket()
	{
		return MyBBS_Tickets_Ticket::getByID($this->data['ticket']);
	}

}
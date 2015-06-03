<?php

class MyBBS_Tickets_Ticket extends JB_Classes_StorableObject
{
	static protected $table = "tickets";
	static protected $cache = array();
	static protected $timestamps = true;
	static protected $user = true;
	private $answer_cache = array();
	private $new_answer_cache = array();

	public function validate($hard=true)
	{
		global $lang;

		if(!isset($this->data['subject']) || !trim($this->data['subject']))
			$this->errors[] = $lang->ticket_no_subject;
		if(!isset($this->data['ticket']) || !trim($this->data['ticket']))
			$this->errors[] = $lang->ticket_no_ticket;

    	if(!empty($this->errors))
			return false;

		return true;
	}

	public static function getAll($where='', array $options=array())
	{
		if(!empty($where))
		    $where .= " AND ";
		$where .= "closed=0";
		return parent::getAll($where, $options);
	}

	public static function getAllWithClosed($where='', array $options=array())
	{
		return parent::getAll($where, $options);
	}

	public function getRecipients()
	{
		$recips = array($this->data['uid']);
		foreach($this->getAnswers() as $answer)
		{
			$recips[] = $answer->uid;
		}
		return array_unique($recips);
	}

	// Functions to interact with our answers
	public function hasAnswers()
	{
		return ($this->numberAnswers() > 0);
	}

	public function numberAnswers()
	{
		if(empty($this->answer_cache))
			$this->getAnswers();

		return count($this->answer_cache);
	}

	public function getAnswers()
	{
		if(empty($this->answer_cache))
			$this->answer_cache = MyBBS_Tickets_Answer::getByTicket($this->data['id']);

		return $this->answer_cache;
	}

	public function createAnswer($data)
	{
		if(!is_array($data))
			$data = array("answer" => $data);

		$data['ticket'] = $this->data['id'];
		$answer = MyBBS_Tickets_Answer::create($data);
		$this->new_answer_cache[] = $answer;
		return $answer;
	}
}

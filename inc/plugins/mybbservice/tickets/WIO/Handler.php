<?php

class MyBBS_Tickets_WIO_Handler extends JB_WIO_Base
{
	protected static $handle = array(
		"tickets"	=> array(
			"add"		=> "wio",
			"answer"	=> "wio",
			"index"		=> "wio",
			"master"	=> "wio",
			"view"		=> "wio",
		)
	);

	public static function init()
	{
		global $lang;
		$lang->load("tickets");
	}
}
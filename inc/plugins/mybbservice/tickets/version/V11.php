<?php

class MyBBS_Tickets_Version_V11 extends JB_Version_Base
{
	static function execute()
	{
		global $cache, $db;

		// Update setting types (use the 1.8 ones)
		$db->update_query("settings", array("optionscode" => "groupselect"), "name='tickets_usergroups'");

		// Rename columns
		$db->rename_column("tickets", "creator", "uid", "int(11) NOT NULL");
		$db->rename_column("tickets", "date", "dateline", "bigint(30) NOT NULL");
		$db->rename_column("tickets_answers", "date", "dateline", "bigint(30) NOT NULL");
	}
}
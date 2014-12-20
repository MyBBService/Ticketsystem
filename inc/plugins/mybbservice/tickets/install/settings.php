<?php

$settingsgroup = array(
	"title"			=> $lang->setting_group_tickets,
	"description"	=> $lang->setting_group_tickets_desc,
);

$settings[] = array(
	"name"			=> "tickets_usergroups",
	"title"			=> $lang->setting_tickets_usergroups,
	"description"	=> $lang->setting_tickets_usergroups_desc,
	"optionscode"	=> "groupselect",
	"value"			=> '3,4',
);
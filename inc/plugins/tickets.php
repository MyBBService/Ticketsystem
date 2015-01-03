<?php 
// Disallow direct access to this file for security reasons
if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

// Test whether core is installed and if so get it up
defined("JB_CORE_INSTALLED") or require_once MYBB_ROOT."inc/plugins/jones/core/include.php";

if(JB_CORE_INSTALLED === true)
{
	JB_Packages::i()->register("MyBBS", "mybbservice", "tickets");
}

$plugins->add_hook("global_start", "tickets_lang");

function tickets_info()
{
	$info = array(
		"name"			=> "Ticketsystem",
		"description"	=> "Fügt deinem Forum ein simples Ticketsystem hinzu",
		"website"		=> "http://mybbservice.de",
		"author"		=> "MyBBService",
		"authorsite"	=> "http://mybbservice.de",
		"version"		=> "1.1",
		"codename"		=> "tickets",
		"compatibility" => "*",
		"dlcid"			=> "35"
	);

	if(JB_CORE_INSTALLED === true)
		return JB_CORE::i()->getInfo($info, false);

	return $info;
}

function tickets_install()
{
	global $lang;
	$lang->load("tickets");

	jb_install_plugin("tickets", array("prefix" => "MyBBS", "vendor" => "mybbservice"));
}

function tickets_is_installed()
{
	global $db;
	return $db->table_exists("tickets");
}

function tickets_uninstall()
{
	JB_Core::i()->uninstall("tickets");
}

function tickets_activate()
{
	JB_Core::i()->activate("tickets");
}

function tickets_deactivate()
{
	JB_Core::i()->deactivate("tickets");
}

function tickets_lang()
{
	global $lang;
	$lang->load("tickets");
}
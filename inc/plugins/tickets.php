<?php 
// Disallow direct access to this file for security reasons
if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

$plugins->add_hook("global_start", "tickets_lang");

function tickets_info()
{
	return array(
		"name"			=> "Ticketsystem",
		"description"	=> "F&uuml;gt deinem Forum ein simples Ticketsystem hinzu",
		"website"		=> "http://mybbservice.de",
		"author"		=> "MyBBService",
		"authorsite"	=> "http://mybbservice.de",
		"version"		=> "1.0",
		"guid" 			=> "",
		"compatibility" => "*",
//		"dlcid"			=> "35"
	);
}

function tickets_install()
{
	global $db, $lang;
	$lang->load("tickets");

	$col = $db->build_create_table_collation();
	$db->query("CREATE TABLE `".TABLE_PREFIX."tickets` (
	                        `id` int(11) NOT NULL AUTO_INCREMENT,
	PRIMARY KEY (`id`) ) ENGINE=MyISAM {$col}");

    $db->query("CREATE TABLE `".TABLE_PREFIX."tickets_answers` (
                            `id` int(11) NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`) ) ENGINE=MyISAM {$col}");

	//Einstellungs Gruppe
	$settings_group = array(
        "title"          => $lang->setting_group_tickets,
        "name"           => "tickets",
        "description"    => $lang->setting_group_tickets_desc,
        "disporder"      => "40",
        "isdefault"      => "0",
    );
    $gid = $db->insert_query("settinggroups", $settings_group);


	//Einstellungen
	$setting = array(
        "name"           => "tickets_usergroups",
        "title"          => $lang->setting_tickets_usergroups,
        "description"    => $lang->setting_tickets_usergroups_desc,
        "optionscode"    => "text",
        "value"          => '3,4',
        "disporder"      => '1',
        "gid"            => (int)$gid,
    );
	$db->insert_query("settings", $setting);

	rebuild_settings();
}

function tickets_is_installed()
{
	global $db;

	return $db->table_exists("tickets");
}

function tickets_uninstall()
{
	global $db;

    $db->drop_table("tickets");
    $db->drop_table("tickets_answers");

	$query = $db->simple_select("settinggroups", "gid", "name='tickets'");
    $gid = $db->fetch_field($query, "gid");
	$db->delete_query("settinggroups", "gid='{$gid}'");
	$db->delete_query("settings", "gid='{$gid}'");
	rebuild_settings();
}

function tickets_activate()
{
	require MYBB_ROOT."inc/adminfunctions_templates.php";
	find_replace_templatesets("header",
		"#".preg_quote('<li><a href="{$mybb->settings[\'bburl\']}/search.php"><img src="{$theme[\'imgdir\']}/toplinks/search.gif" alt="" title="" />{$lang->toplinks_search}</a></li>')."#i",
		'<li><a href="{$mybb->settings[\'bburl\']}/search.php"><img src="{$theme[\'imgdir\']}/toplinks/search.gif" alt="" title="" />{$lang->toplinks_search}</a></li>
		 <li><a href="{$mybb->settings[\'bburl\']}/tickets.php"><img src="images/toplinks/tickets.gif" alt="" title="" />{$lang->toplinks_tickets}</a></li>');
}

function tickets_deactivate()
{
	require MYBB_ROOT."inc/adminfunctions_templates.php";
	find_replace_templatesets("header",
		"#".preg_quote('<li><a href="{$mybb->settings[\'bburl\']}/tickets.php"><img src="images/toplinks/tickets.gif" alt="" title="" />{$lang->toplinks_tickets}</a></li>')."#i",
		"", 0);
}

function tickets_lang()
{
	global $lang;
	$lang->load("tickets");
}

if(!function_exists("is_member")) {
	function is_member($groups, $user = false)
	{
	        global $mybb;
	
	        if($user == false)
	        {
	                $user = $mybb->user;
	        }
	        else if(!is_array($user))
	        {
	                // Assume it's a UID
	                $user = get_user($user);
	        }
	
	        $memberships = array_map('intval', explode(',', $user['additionalgroups']));
	        $memberships[] = $user['usergroup'];
	
	        if(!is_array($groups))
	        {
	                if(is_string($groups))
	                {
	                        $groups = explode(',', $groups);
	                }
	                else
	                {
	                        $groups = (array)$groups;
	                }
	        }
	
	        $groups = array_filter(array_map('intval', $groups));
	
	        return array_intersect($groups, $memberships);
	}
}
?>
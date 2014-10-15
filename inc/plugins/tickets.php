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
		"description"	=> "Fügt deinem Forum ein simples Ticketsystem hinzu",
		"website"		=> "http://mybbservice.de",
		"author"		=> "MyBBService",
		"authorsite"	=> "http://mybbservice.de",
		"version"		=> "1.0.2",
		"codename"		=> "tickets",
		"compatibility" => "*",
		"dlcid"			=> "35"
	);
}

function tickets_install()
{
	global $db, $lang;
	$lang->load("tickets");

	$col = $db->build_create_table_collation();
	$db->query("CREATE TABLE `".TABLE_PREFIX."tickets` (
		`id`		int(11)		NOT NULL AUTO_INCREMENT,
		`subject`	varchar(30)	NOT NULL,
		`ticket`	text		NOT NULL,
		`creator`	int(11)		NOT NULL,
		`date`		bigint(30)	NOT NULL,
		`closed`	bigint(30)	NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`) ) ENGINE=MyISAM {$col}");

	$db->query("CREATE TABLE `".TABLE_PREFIX."tickets_answers` (
		`id`		int(11)		NOT NULL AUTO_INCREMENT,
		`uid`		int(11)		NOT NULL,
		`ticket`	int(11)		NOT NULL,
		`answer`	text		NOT NULL,
	`date`		bigint(30)	NOT NULL,
	PRIMARY KEY (`id`) ) ENGINE=MyISAM {$col}");

	$templateset = array(
		"prefix" => "tickets",
		"title" => "Ticketsystem",
	);
	$db->insert_query("templategroups", $templateset);


	//Templates
	$templatearray = array(
		"title" => "tickets",
		"template" => "<html>
<head>
<title>{\$mybb->settings[\'bbname\']}</title>
{\$headerinclude}
</head>
<body>
{\$header}

<table border=\"0\" cellspacing=\"{\$theme[\'borderwidth\']}\" cellpadding=\"{\$theme[\'tablespace\']}\" class=\"tborder\">
	<tr>
		<td class=\"thead\" colspan={\$colspan}>{\$lang->tickets}</td>
		<td class=\"thead\" style=\"text-align: right;\"><a href=\"tickets.php?closed=1\">{\$lang->tickets_show_closed}</a></td>
		{\$masterlink}
	</tr>
	<tr>
		<td class=\"tcat\" width=\"40%\">{\$lang->ticket_title}</td>
		<td class=\"tcat\" width=\"40%\">{\$lang->ticket_created_at}</td>
		<td class=\"tcat\" width=\"20%\">{\$lang->ticket_answers}</td>
	</tr>
	{\$tickets}
</table>

<br />
<div style=\"text-align:center;\">
	<a href=\"tickets.php?action=add\"><input type=\"button\" value=\"{\$lang->ticket_new}\" ></a>
</div>

{\$footer}
</body>
</html>",
		"sid" => -2
	);
	$db->insert_query("templates", $templatearray);

	$templatearray = array(
		"title" => "tickets_add",
		"template" => "<html>
<head>
	<title>{\$mybb->settings[\'bbname\']}</title>
	{\$headerinclude}
</head>
<body>
	{\$header}
	<table width=\"100%\" border=\"0\" align=\"center\">
		<tr>
			<td valign=\"top\">
				{\$errors}
				<form action=\"tickets.php\" method=\"post\">
					<input type=\"hidden\" name=\"action\" value=\"do_add\" />
					<input type=\"hidden\" name=\"my_post_key\" value=\"{\$mybb->post_code}\" />

					<table border=\"0\" cellspacing=\"{\$theme[\'borderwidth\']}\" cellpadding=\"{\$theme[\'tablespace\']}\" class=\"tborder\">
						<tr>
							<td class=\"thead\" align=\"center\" colspan=\"2\">
								<strong>{\$lang->ticket_new}</strong>
							</td>
						</tr>

						<tr>
							<td class=\"trow1\">{\$lang->ticket_title}:</td>
							<td class=\"trow1\"><input type=\"text\" name=\"subject\" value=\"{\$subject}\" ></td>
						</tr>
						<tr>
							<td class=\"trow2\">{\$lang->ticket}:</td>
							<td class=\"trow2\"><textarea cols=\"50\" rows=\"10\" name=\"ticket\">{\$ticket}</textarea></td>
						</tr>

						<tr>
							<td class=\"trow1\"></td>
							<td class=\"trow1\"><input type=\"submit\" value=\"{\$lang->ticket_create}\" /></td>
						</tr>
					</table>
				</form>
			</td>
		</tr>
	</table>
	{\$footer}
</body>
</html>",
		"sid" => -2
	);
	$db->insert_query("templates", $templatearray);

	$templatearray = array(
		"title" => "tickets_answer_form",
		"template" => "<br />
<form action=\"tickets.php\" method=\"post\">
<input type=\"hidden\" name=\"action\" value=\"do_answer\" />
<input type=\"hidden\" name=\"my_post_key\" value=\"{\$mybb->post_code}\" />
<input type=\"hidden\" name=\"id\" value=\"{\$id}\" />
<table border=\"0\" cellspacing=\"{\$theme[\'borderwidth\']}\" cellpadding=\"{\$theme[\'tablespace\']}\" class=\"tborder\">
<tr>
<td class=\"thead\">{\$lang->ticket_answers}</td>
</tr>
<tr>
<td class=\"trow1\"><textarea name=\"answer\" cols=175 rows=10></textarea></td>
</tr>
<tr>
<td class=\"trow1\" style=\"text-align: center;\"><input type=\"submit\" name=\"submit\" value=\"{\$lang->ticket_answers}\" /><input type=\"submit\" name=\"submit\" value=\"{\$lang->ticket_close}\" /></td>
</table>
</form>",
		"sid" => -2
	);
	$db->insert_query("templates", $templatearray);

	$templatearray = array(
		"title" => "tickets_master",
		"template" => "<html>
<head>
<title>{\$mybb->settings[\'bbname\']}</title>
{\$headerinclude}
</head>
<body>
{\$header}

<table border=\"0\" cellspacing=\"{\$theme[\'borderwidth\']}\" cellpadding=\"{\$theme[\'tablespace\']}\" class=\"tborder\">
	<tr>
		<td class=\"thead\" colspan=2>{\$lang->tickets}</td>
		<td class=\"thead\" colspan=2 style=\"text-align: right;\"><a href=\"tickets.php?action=master&closed=1\">{\$lang->tickets_show_closed}</a></td>
	</tr>
	<tr>
		<td class=\"tcat\" width=\"40%\">{\$lang->ticket_title}</td>
		<td class=\"tcat\" width=\"20%\">{\$lang->ticket_created_at}</td>
		<td class=\"tcat\" width=\"30%\">{\$lang->by}</td>
		<td class=\"tcat\" width=\"10%\">{\$lang->ticket_answers}</td>
	</tr>
	{\$tickets}
</table>

{\$footer}
</body>
</html>",
		"sid" => -2
	);
	$db->insert_query("templates", $templatearray);

	$templatearray = array(
		"title" => "tickets_master_table",
		"template" => "<tr>
	<td class=\"trow1\"><a href=\"tickets.php?action=view&view={\$ticket[\'id\']}\">{\$lockimg}{\$ticket[\'subject\']}</a></td>
	<td class=\"trow1\">{\$ticket[\'date\']}</td>
	<td class=\"trow1\">{\$ticket[\'creator\']}</td>
	<td class=\"trow1\" style=\"text-align:center;\">{\$ticket[\'answers\']}</td>
</tr>",
		"sid" => -2
	);
	$db->insert_query("templates", $templatearray);

	$templatearray = array(
		"title" => "tickets_master_table_nothing",
		"template" => "<tr>
	<td class=\"trow1\" colspan=4 style=\"text-align:center;\">{\$lang->tickets_nothing}</td>
</tr>",
		"sid" => -2
	);
	$db->insert_query("templates", $templatearray);

	$templatearray = array(
		"title" => "tickets_masterlink",
		"template" => "<td class=\"thead\" style=\"text-align:right;\"><a href=\"tickets.php?action=master\">{\$lang->tickets_answer}</a></td>",
		"sid" => -2
	);
	$db->insert_query("templates", $templatearray);

	$templatearray = array(
		"title" => "tickets_table",
		"template" => "<tr>
	<td class=\"trow1\"><a href=\"tickets.php?action=view&view={\$ticket[\'id\']}\">{\$lockimg}{\$ticket[\'subject\']}</a></td>
	<td class=\"trow1\">{\$ticket[\'date\']}</td>
	<td class=\"trow1\" style=\"text-align:center;\">{\$ticket[\'answers\']}</td>
</tr>",
		"sid" => -2
	);
	$db->insert_query("templates", $templatearray);

	$templatearray = array(
		"title" => "tickets_table_nothing",
		"template" => "<tr>
	<td class=\"trow1\" colspan=3 style=\"text-align:center;\">{\$lang->tickets_nothing}</td>
</tr>",
		"sid" => -2
	);
	$db->insert_query("templates", $templatearray);

	$templatearray = array(
		"title" => "tickets_view",
		"template" => "<html>
<head>
<title>{\$mybb->settings[\'bbname\']}</title>
{\$headerinclude}
</head>
<body>
{\$header}

<table border=\"0\" cellspacing=\"{\$theme[\'borderwidth\']}\" cellpadding=\"{\$theme[\'tablespace\']}\" class=\"tborder\">
	<tr>
		<td class=\"thead\" colspan=2>{\$lockimg}{\$lang->ticket}: {\$ticket[\'subject\']}</td>
	</tr>
	<tr>
		<td class=\"tcat\">{\$ticket[\'creator\']}</td>
		<td class=\"tcat\">{\$ticket[\'date\']}</td>
	</tr>
<tr>
<td class=\"trow1\" colspan=2>{\$ticket[\'ticket\']}</td>
</tr>
	{\$answers}
</table>

{\$do_answer}

{\$footer}
</body>
</html>",
		"sid" => -2
	);
	$db->insert_query("templates", $templatearray);

	$templatearray = array(
		"title" => "tickets_view_answers",
		"template" => "<tr>
		<td class=\"tcat\">{\$answer[\'creator\']}</td>
		<td class=\"tcat\">{\$answer[\'date\']}</td>
	</tr>
<tr>
	<td class=\"trow1\" colspan=2>{\$answer[\'answer\']}</td>
</tr>",
		"sid" => -2
	);
	$db->insert_query("templates", $templatearray);

	//Einstellungs Gruppe
	$settings_group = array(
		"title"			=> $lang->setting_group_tickets,
		"name"			=> "tickets",
		"description"	=> $lang->setting_group_tickets_desc,
		"disporder"		=> "40",
		"isdefault"		=> "0",
	);
	$gid = $db->insert_query("settinggroups", $settings_group);


	//Einstellungen
	$setting = array(
		"name"			=> "tickets_usergroups",
		"title"			=> $lang->setting_tickets_usergroups,
		"description"	=> $lang->setting_tickets_usergroups_desc,
		"optionscode"	=> "text",
		"value"			=> '3,4',
		"disporder"		=> '1',
		"gid"			=> (int)$gid,
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

	$templatearray = array(
		"tickets",
		"tickets_add",
		"tickets_master",
		"tickets_master_table",
		"tickets_master_table_nothing",
		"tickets_masterlink",
		"tickets_table",
		"tickets_table_nothing",
		"tickets_view",
		"tickets_view_answers"
	);
	$deltemplates = implode("','", $templatearray);
	$db->delete_query("templates", "title in ('{$deltemplates}')");
}

function tickets_activate()
{
	require MYBB_ROOT."inc/adminfunctions_templates.php";
	find_replace_templatesets("header",
		"#".preg_quote('{$menu_portal}')."#i",
		'<li><a href="{$mybb->settings[\'bburl\']}/tickets.php" style="background-position: 0 20px;"><img src="images/toplinks/tickets.gif" alt="" title="" />{$lang->toplinks_tickets}</a></li>
		{$menu_portal}');
}

function tickets_deactivate()
{
	require MYBB_ROOT."inc/adminfunctions_templates.php";
	find_replace_templatesets("header",
		"#".preg_quote('<li><a href="{$mybb->settings[\'bburl\']}/tickets.php" style="background-position: 0 20px;"><img src="images/toplinks/tickets.gif" alt="" title="" />{$lang->toplinks_tickets}</a></li>')."#i",
		"", 0);
}

function tickets_lang()
{
	global $lang;
	$lang->load("tickets");
}
?>
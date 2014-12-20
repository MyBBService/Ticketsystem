<?php

$templateset = "Ticketsystem";

$templates[] = array(
	"title"		=> "tickets",
	"template"	=> '<html>
<head>
<title>{$mybb->settings[\'bbname\']}</title>
{$headerinclude}
</head>
<body>
{$header}

<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
	<tr>
		<td class="thead" colspan={$colspan}>{$lang->tickets}</td>
		<td class="thead" style="text-align: right;"><a href="tickets.php?closed=1">{$lang->tickets_show_closed}</a></td>
		{$masterlink}
	</tr>
	<tr>
		<td class="tcat" width="40%">{$lang->ticket_title}</td>
		<td class="tcat" width="40%">{$lang->ticket_created_at}</td>
		<td class="tcat" width="20%">{$lang->ticket_answers}</td>
	</tr>
	{$content}
</table>

<br />
<div style="text-align:center;">
	<a href="tickets.php?action=add"><input type="button" class="button" value="{$lang->ticket_new}" ></a>
</div>

{$footer}
</body>
</html>'
);

$templates[] = array(
	"title"		=> "tickets_add",
	"template"	=> '<html>
<head>
	<title>{$mybb->settings[\'bbname\']}</title>
	{$headerinclude}
</head>
<body>
	{$header}
	<table width="100%" border="0" align="center">
		<tr>
			<td valign="top">
				{$errors}
				<form action="tickets.php" method="post">
					<input type="hidden" name="action" value="add" />
					<input type="hidden" name="my_post_key" value="{$mybb->post_code}" />

					<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
						<tr>
							<td class="thead" align="center" colspan="2">
								<strong>{$lang->ticket_new}</strong>
							</td>
						</tr>

						<tr>
							<td class="trow1">{$lang->ticket_title}:</td>
							<td class="trow1"><input type="text" class="textbox" name="subject" value="{$subject}" ></td>
						</tr>
						<tr>
							<td class="trow2">{$lang->ticket}:</td>
							<td class="trow2"><textarea cols="50" rows="10" name="ticket">{$ticket}</textarea></td>
						</tr>

						<tr>
							<td class="trow1"></td>
							<td class="trow1"><input type="submit" class="button" value="{$lang->ticket_create}" /></td>
						</tr>
					</table>
				</form>
			</td>
		</tr>
	</table>
	{$footer}
</body>
</html>'
);

$templates[] = array(
	"title"		=> "tickets_answer_form",
	"template"	=> '<br />
<form action="tickets.php" method="post">
<input type="hidden" name="action" value="answer" />
<input type="hidden" name="my_post_key" value="{$mybb->post_code}" />
<input type="hidden" name="id" value="{$ticket->id}" />
<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
<tr>
<td class="thead">{$lang->ticket_answers}</td>
</tr>
<tr>
<td class="trow1"><textarea name="answer" cols=175 rows=10></textarea></td>
</tr>
<tr>
<td class="trow1" style="text-align: center;"><input type="submit" class="button" name="submit" value="{$lang->ticket_answers}" /><input type="submit" class="button" name="submit" value="{$lang->ticket_close}" /></td>
</table>
</form>'
);

$templates[] = array(
	"title"		=> "tickets_master",
	"template"	=> '<html>
<head>
<title>{$mybb->settings[\'bbname\']}</title>
{$headerinclude}
</head>
<body>
{$header}

<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
	<tr>
		<td class="thead" colspan=2>{$lang->tickets}</td>
		<td class="thead" colspan=2 style="text-align: right;"><a href="tickets.php?action=master&closed=1">{$lang->tickets_show_closed}</a></td>
	</tr>
	<tr>
		<td class="tcat" width="40%">{$lang->ticket_title}</td>
		<td class="tcat" width="20%">{$lang->ticket_created_at}</td>
		<td class="tcat" width="30%">{$lang->by}</td>
		<td class="tcat" width="10%">{$lang->ticket_answers}</td>
	</tr>
	{$tickets}
</table>

{$footer}
</body>
</html>'
);

$templates[] = array(
	"title"		=> "tickets_master_table",
	"template"	=> '<tr>
	<td class="trow1"><a href="tickets.php?action=view&view={$ticket->id}">{$lockimg}{$ticket->subject}</a></td>
	<td class="trow1">{$ticket->date}</td>
	<td class="trow1">{$ticket->creator}</td>
	<td class="trow1" style="text-align:center;">{$ticket->answers}</td>
</tr>'
);

$templates[] = array(
	"title"		=> "tickets_master_table_nothing",
	"template"	=> '<tr>
	<td class="trow1" colspan=4 style="text-align:center;">{$lang->tickets_nothing}</td>
</tr>'
);

$templates[] = array(
	"title"		=> "tickets_masterlink",
	"template"	=> '<td class="thead" style="text-align:right;"><a href="tickets.php?action=master">{$lang->tickets_answer}</a></td>'
);

$templates[] = array(
	"title"		=> "tickets_table",
	"template"	=> '<tr>
	<td class="trow1"><a href="tickets.php?action=view&view={$ticket->id}">{$lockimg}{$ticket->subject}</a></td>
	<td class="trow1">{$ticket->date}</td>
	<td class="trow1" style="text-align:center;">{$ticket->answers}</td>
</tr>'
);

$templates[] = array(
	"title"		=> "tickets_table_nothing",
	"template"	=> '<tr>
	<td class="trow1" colspan=3 style="text-align:center;">{$lang->tickets_nothing}</td>
</tr>'
);

$templates[] = array(
	"title"		=> "tickets_view",
	"template"	=> '<html>
<head>
<title>{$mybb->settings[\'bbname\']}</title>
{$headerinclude}
</head>
<body>
{$header}

<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
	<tr>
		<td class="thead" colspan=2>{$lockimg}{$lang->ticket}: {$ticket->subject}</td>
	</tr>
	<tr>
		<td class="tcat">{$ticket->creator}</td>
		<td class="tcat">{$ticket->date}</td>
	</tr>
<tr>
<td class="trow1" colspan=2>{$ticket->ticket}</td>
</tr>
	{$answers}
</table>

{$do_answer}

{$footer}
</body>
</html>'
);

$templates[] = array(
	"title"		=> "tickets_view_answers",
	"template"	=> '<tr>
	<td class="tcat">{$answer->creator}</td>
	<td class="tcat">{$answer->date}</td>
</tr>
<tr>
	<td class="trow1" colspan=2>{$answer->answer}</td>
</tr>'
);
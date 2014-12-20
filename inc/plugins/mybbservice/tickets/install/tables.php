<?php

$tables[] = "CREATE TABLE mybb_tickets (
	id			int(11)		NOT NULL AUTO_INCREMENT,
	subject		varchar(30)	NOT NULL,
	ticket		text		NOT NULL,
	uid			int(11)		NOT NULL,
	dateline	bigint(30)	NOT NULL,
	closed		bigint(30)	NOT NULL DEFAULT '0',
PRIMARY KEY (id)) ENGINE=MyISAM;";

$tables[] = "CREATE TABLE mybb_tickets_answers (
	id			int(11)		NOT NULL AUTO_INCREMENT,
	uid			int(11)		NOT NULL,
	ticket		int(11)		NOT NULL,
	answer		text		NOT NULL,
	dateline	bigint(30)	NOT NULL,
PRIMARY KEY (id)) ENGINE=MyISAM;";
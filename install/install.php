<?php

class Installer {
	public static function install($db)
	{
		$db->query('create table if not exists ' . DB_PREFIX . 'user_all (id int primary key auto_increment, firstname varchar(100) not null, lastname varchar(100), email varchar(255) not null, country varchar(100), salt varchar(12) not null, password char(32) not null, enabled bool null default true, unique index (email, enabled))');
		$db->query('create view if not exists ' . DB_PREFIX . 'user as select id, firstname, lastname, email, country, salt, password from ' . DB_PREFIX . 'user_all where enabled is not null');
		$db->query('create table if not exists ' . DB_PREFIX . 'article (id int primary key auto_increment, title varchar(255) not null, date datetime not null default now(), author_id int not null references ' . DB_PREFIX . 'user on update cascade on delete cascade, text text not null)');
	}
}
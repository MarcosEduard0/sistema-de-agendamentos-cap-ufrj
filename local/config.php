<?php
defined('BASEPATH') or exit('No direct script access allowed');

return array(
	'config' => array(
		'base_url' => 'http://192.168.1.64/sistema-de-agendamentos-cap-ufrj',
		'log_threshold' => 1,
		'index_page' => 'index.php',
		'uri_protocol' => 'REQUEST_URI',
	),

	'database' => array(
		'dsn' => 'mysql:host=localhost;dbname=agendamento',
		'hostname' => 'localhost',
		'username' => 'root',
		'password' => '',
		'database' => 'agendamento',
		'dbdriver' => 'pdo',
	),

);

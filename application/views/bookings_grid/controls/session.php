<?php

$attrs = [
	'method' => 'post',
	'id' => 'bookings_controls_session',
];

$hidden = [
	'params' => http_build_query($query_params)
];

echo form_open($form_action, $attrs, $hidden);

echo "<label for='session_id'>Ano letivo: </label>";

echo form_dropdown([
	'name' => 'session_id',
	'id' => 'session_id',
	'options' => $session_options,
	'selected' => $selected_session_id,
	'class' => 'form-control',
	'style' => 'display: initial;',

]);

echo form_close();

echo "<br />";

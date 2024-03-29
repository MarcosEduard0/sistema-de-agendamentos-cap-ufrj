<?php
$classes = [
	'bookings-grid-header-cell',
	'bookings-grid-header-cell-day',
];

if ($date->date == $today->format('Y-m-d')) {
	$classes[] = 'bookings-grid-header-cell-is-today';
}

?>

<th class="<?= implode(' ', $classes) ?>">
	<strong>
		<?php
		echo isset($day_names[$date->weekday])
			? $day_names[$date->weekday]
			: '';
		?>
	</strong>
	<?php
	$this->formatter = new IntlDateFormatter(
		'pt_BR',
		IntlDateFormatter::FULL,
		IntlDateFormatter::NONE,
		'America/Sao_Paulo',
		IntlDateFormatter::GREGORIAN
	);

	$date_fmt = $this->formatter->setPattern(setting('date_format_weekday'));
	// $date_fmt = setting('date_format_weekday');
	if (strlen($date_fmt)) {
		$dt = datetime_from_string($date->date);
		$format = $this->formatter->format($dt);
		echo "<br>";
		echo "<span style='font-size: 90%'>{$format}</span>";
	}
	?>
</th>
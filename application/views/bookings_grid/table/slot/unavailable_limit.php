<td class="<?= $class ?>">
	<a href="#" class="bookings-grid-button" up-layer="new popup" up-align="top" up-size="medium" up-class="booking-choices-cancel" up-content="<p><?= html_escape($slot->label) ?></p>
	<br><button up-dismiss type='button' class='btn btn-outline-danger btn-sm'>Ok, entendi.</button>">
		<?php
		$icon = 'error.png';
		switch ($extended) {
			case 'quota':
				$icon = 'stop.png';
				break;
			case 'past':
				$icon = 'school_manage_weeks.png';
				break;
			case 'future':
				$icon = 'date_error.png';
				break;
		}
		echo img([
			'role' => 'button',
			'src' => 'assets/images/ui/' . $icon,
			'alt' => 'Limit',
		]);
		?>
	</a>
</td>
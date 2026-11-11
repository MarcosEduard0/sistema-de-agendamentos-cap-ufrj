<?php

namespace app\components\bookings\grid;

defined('BASEPATH') or exit('No direct script access allowed');

use app\components\bookings\Context;
use \IntlDateFormatter;


class Header
{


	// CI instance
	private $CI;


	// Context instance
	private $context;


	public function __construct(Context $context)
	{
		$this->CI = &get_instance();

		$this->CI->load->helper('week');

		$this->context = $context;
		$this->formatter = new IntlDateFormatter(
			'pt_BR',
			IntlDateFormatter::FULL,
			IntlDateFormatter::NONE,
			'America/Sao_Paulo',
			IntlDateFormatter::GREGORIAN
		);
		$this->formatter->setPattern(setting('date_format_long'));
	}


	/**
	 * Render the Date or Room selectors.
	 *
	 */
	public function render()
	{
		if (!$this->context->datetime) {
			return '';
		}

		$data = $this->get_data();

		if (empty($data)) {
			return '';
		}

		return $this->CI->load->view('bookings_grid/header', $data, TRUE);
	}


	private function get_data()
	{
		$data = [
			'prev' => FALSE,
			'next' => FALSE,
			'title' => '',
			'week' => $this->context->timetable_week,
		];

		switch ($this->context->display_type) {

			case 'day':

				$prev_label = '&larr; Voltar';
				$next_label = 'Próximo &rarr;';

				$long_date = $this->context->datetime->format(setting('date_format_long'));

				// $long_date = $this->formatter->format($this->context->datetime);

				$data['title'] = $this->context->timetable_week
					? $long_date . ' - ' . html_escape($this->context->timetable_week->name)
					: $long_date;

				break;

			case 'room':

				$prev_label = '&lsaquo; Anterior';
				$next_label = 'Próxima &rsaquo;';

                $dates = array_keys($this->context->dates);
                sort($dates);
                $start = $dates ? new \DateTime(reset($dates)) : clone $this->context->week_start;
                $end = $dates ? new \DateTime(end($dates)) : clone $start;
                $range_formatter = new IntlDateFormatter('pt_BR', IntlDateFormatter::NONE, IntlDateFormatter::NONE, 'America/Sao_Paulo');
                $range_formatter->setPattern("d 'de' MMMM 'de' yyyy");
                if ($start->format('Y-m-d') === $end->format('Y-m-d')) {
                    $week_text = $range_formatter->format($start);
                } elseif ($start->format('Y-m') === $end->format('Y-m')) {
                    $week_text = $start->format('j') . ' a ' . $range_formatter->format($end);
                } else {
                    $week_text = $range_formatter->format($start) . ' a ' . $range_formatter->format($end);
                }
                $data['title'] = '<span class="booking-nav-date">' . html_escape($week_text) . '</span>';
                if ($this->context->timetable_week) {
                    $data['title'] .= '<span class="booking-nav-week">' . html_escape($this->context->timetable_week->name) . '</span>';
                }

				break;

			default:

				return $data;
		}

		// Links
		//

		$params = $this->context->get_query_params();

		if ($this->context->prev_date) {

			$params['date'] = $this->context->prev_date->format('Y-m-d');
			$params['dir'] = 'prev';
			$query = http_build_query($params);

			$data['prev']['label'] = $prev_label;
			$data['prev']['url'] = site_url($this->context->base_uri) . '?' . $query;
		}

		if ($this->context->next_date) {

			$params['date'] = $this->context->next_date->format('Y-m-d');
			$params['dir'] = 'next';
			$query = http_build_query($params);

			$data['next']['label'] = $next_label;
			$data['next']['url'] = site_url($this->context->base_uri) . '?' . $query;
		}

		return $data;
	}
}

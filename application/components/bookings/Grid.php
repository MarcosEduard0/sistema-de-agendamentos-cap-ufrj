<?php

namespace app\components\bookings;

defined('BASEPATH') or exit('No direct script access allowed');


use \DateTime;
use \DateInterval;
use \DatePeriod;

use app\components\Calendar;
use app\components\bookings\grid\Controls;
use app\components\bookings\grid\Header;
use app\components\bookings\grid\Table;
use app\components\bookings\exceptions\DateException;


class Grid
{


	// CI instance
	private $CI;


	// Context instance
	private $context;



	public function __construct(Context $context)
	{
		$this->CI = &get_instance();

		$this->CI->load->model([
			'sessions_model',
			'dates_model',
			'users_model',
			'rooms_model',
			'access_control_model',
			'periods_model',
			'weeks_model',
		]);

		$this->context = $context;

		$this->controls = new Controls($context);
		$this->header = new Header($context);
		$this->table = new Table($context);
	}


	public function render()
	{
		$controls = $this->controls->render();
		$header = $this->header->render();

		$body = $this->render_body();
		$footer = $this->render_footer();
		$legend = $this->render_legend();

		$form_attrs = [
			'up-layer' => 'new modal',
			'up-size' => 'large',
			'up-target' => '.bookings-create',
			'up-dismissable' => 'button key',
		];
		$form_hidden = ['step' => 'selection'];
		$form_open = form_open($this->context->base_uri . '/create/multi', $form_attrs, $form_hidden);
		$form_close = form_close();

        $out = "{$controls}\n{$header}\n{$legend}\n{$form_open}\n<div class='booking-table-scroll' tabindex='0' role='region' aria-label='Grade de agendamentos'>{$body}</div>\n{$footer}\n{$form_close}\n";

		return "<div id='bookings_grid' up-hungry>{$out}</div>";
	}


	/**
	 * Render the main content area.
	 *
	 * If an exception is present in the context, the error is displayed instead of the table.
	 *
	 */
	public function render_body()
	{
		// Check for any errors and render it instead of the table.
		if ($this->context->exception) {
			return msgbox('error', $this->context->exception->getMessage());
		}

		return $this->table->render();
	}


	/**
	 * Render the footer. This includes the legend/key, and recurring bookings controls.
	 *
	 */
	public function render_footer()
	{
		// if ( ! $this->CI->userauth->is_level(ADMINISTRATOR)) {
		// 	return '';
		// }

		if ($this->context->exception) {
			return '';
		}

		$input = form_checkbox([
			'name' => 'multi_select',
			'id' => 'multi_select',
			'value' => '1',
			'checked' => false,
			'up-switch' => '.multi-select-content',
		]);
        $input_label = form_label($input . ' Seleção múltipla', 'multi_select', ['class' => 'booking-multi-label']);
        return "<div class='booking-grid-actions'>
            <div>{$input_label}<p class='booking-selection-hint'>Selecione vários horários para agendar de uma vez.</p></div>
            <div class='multi-select-content'><button type='submit' class='btn btn-primary btn-sm'>Criar agendamentos...</button></div>
        </div>";
	}


	/**
	 * Render table legend.
	 *
	 */
	public function render_legend()
	{
        $items = [
            ['class' => 'available', 'label' => lang('bookings_legend_free')],
            ['class' => 'recurring', 'label' => lang('bookings_legend_static')],
            ['class' => 'single', 'label' => lang('bookings_legend_staff')],
        ];
        $out = '<div class="booking-legend" aria-label="Legenda dos agendamentos"><strong>' . html_escape(lang('bookings_legend_legend')) . ':</strong><ul>';
        foreach ($items as $item) {
            $out .= '<li><span class="booking-legend-dot booking-legend-dot--' . $item['class'] . '" aria-hidden="true"></span>' . html_escape($item['label']) . '</li>';
        }
        return $out . '</ul></div>';
	}
}

<?php defined('BASEPATH') or exit('No direct script access allowed');

class Menu_model extends CI_Model
{


	/**
	 * For header/footer in page.
	 *
	 */
	public function global()
	{
		$is_admin = $this->userauth->is_level(ADMINISTRATOR);

		$items = [];

		if (!$this->userauth->logged_in()) {
			return $items;
		}

		$items[] = [
			'label' => 'Agendamentos',
			'url' => site_url('bookings'),
			'icon' => 'school_manage_bookings.png',
		];

		if ($is_admin) {
			$items[] = [
				'label' => 'Setup',
				'url' => site_url('setup'),
				'icon' => 'school_manage_settings.png',
			];
		}

		$items[] = [
			'label' => 'Conta',
			'url' => site_url('profile/edit'),
			'icon' => ($is_admin) ? 'user_administrator.png' : 'user_teacher.png',
		];

		$items[] = [
			'label' => 'Sair',
			'url' => site_url('logout'),
			'icon' => 'logout.png',
		];

		return $items;
	}


	public function setup_school()
	{
		$items = [];

		if (!$this->userauth->is_level(ADMINISTRATOR)) {
			return $items;
		}

		$items[] = [
			'label' => 'Detalhes da Escola',
			'icon' => 'school_manage_times.png',
			'url' => site_url('school'),
			'img' => 'school.png',
		];

		$items[] = [
			'label' => 'Horário das aulas',
			'icon' => 'school_manage_times.png',
			'url' => site_url('periods'),
			'img' => 'periods.png',
		];

		$items[] = [
			'label' => 'Calendário Escolar',
			'icon' => 'school_manage_weeks.png',
			'url' => site_url('weeks'),
			'img' => 'schedule.png',
		];

		$items[] = [
			'label' => 'Anos letivos',
			'icon' => 'school_manage_times.png',
			'url' => site_url('sessions'),
			'img' => 'holiday.png',
		];

		$items[] = [
			'label' => 'Salas',
			'icon' => 'school_manage_rooms.png',
			'url' => site_url('rooms'),
			'img' => 'rooms.png',
		];

		$items[] = [
			'label' => 'Departamentos',
			'icon' => 'school_manage_departments.png',
			'url' => site_url('departments'),
			'img' => 'departments.png',
		];

		return $items;
	}


	public function setup_manage()
	{
		$items = [];

		if (!$this->userauth->is_level(ADMINISTRATOR)) {
			return $items;
		}

		$items[] = [
			'label' => 'Usuários',
			'icon' => 'school_manage_users.png',
			'url' => site_url('users'),
			'img' => 'users.png',
		];

		$items[] = [
			'label' => 'Configurações',
			'icon' => 'school_manage_settings.png',
			'url' => site_url('settings/general'),
			'img' => 'settings.png',
		];

		// $items[] = [
		// 	'label' => 'Autentificação',
		// 	'icon' => 'lock.png',
		// 	'url' => site_url('settings/authentication/ldap'),
		// 	'img' => 'reports.png',
		// ];

		return $items;
	}
}

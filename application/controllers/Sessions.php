<?php
defined('BASEPATH') or exit('No direct script access allowed');

use app\components\Calendar;


class Sessions extends MY_Controller
{


	public function __construct()
	{
		parent::__construct();

		$this->require_auth_level(ADMINISTRATOR);

		$this->load->model('sessions_model');
		$this->load->model('weeks_model');
		$this->load->model('dates_model');
		$this->load->helper('date');

		$this->data['showtitle'] = 'Sessões';
	}


	private function get_icons($session = NULL)
	{
		$items = [
			['sessions', 'Sessões', 'calendar_view_month.png'],
		];

		if ($session) {
			$items[] = ['sessions/view/' . $session->session_id, $session->name, 'calendar_view_day.png'];
			$items[] = ['holidays/session/' . $session->session_id, 'Feriados', 'school_manage_holidays.png'];
		}

		return $items;
	}


	/**
	 * View list of sessions.
	 *
	 */
	public function index()
	{
		$this->data['active'] = $this->sessions_model->get_all_active();
		$this->data['past'] = $this->sessions_model->get_all_past();

		$this->data['title'] = 'Sessões';

		$body = $this->load->view('sessions/index', $this->data, TRUE);

		$icons = iconbar($this->get_icons(), 'sessions');

		$this->data['body'] = $icons . $body;

		return $this->render();
	}


	/**
	 * View info on single session.
	 *
	 * Calendar UI for selecting dates.
	 * Link for Holidays in session.
	 *
	 */
	public function view($session_id)
	{
		$session = $this->find_session($session_id);

		if ($this->input->post()) {
			$this->save_dates($session->session_id);
		}

		$weeks = $this->weeks_model->get_all();

		$calendar = new Calendar([
			'session' => $session,
			'weeks' => $weeks,
			'dates' => $this->dates_model->get_by_session($session->session_id),
			'mode' => Calendar::MODE_CONFIG,
			'month_class' => 'session-calendar',
		]);

		$this->data['weeks'] = $weeks;
		$this->data['calendar'] = $calendar;
		$this->data['session'] = $session;
		$this->data['title'] = $this->data['showtitle'] = 'Sessão: ' . $session->name;

		$icons = iconbar($this->get_icons($session), 'sessions/view/' . $session->session_id);

		$body = $this->load->view('sessions/view', $this->data, TRUE);

		if (empty($weeks)) {
			$body = msgbox('error', 'Por favor, adicione pelo menos uma Semana.');
		}

		$this->data['body'] = $icons . $body;

		return $this->render();
	}


	private function save_dates($session_id)
	{
		$dates = $this->input->post('dates');

		if (empty($dates)) {
			return FALSE;
		}

		$updated = $this->dates_model->set_weeks($session_id, $dates);
		if ($updated) {
			$flashmsg = msgbox('info', "As semanas da sessão foram atualizadas.");
		} else {
			$flashmsg = msgbox('error', "Ocorreu um erro ao atualizar as semanas da sessão.");
		}

		// echo "done";
		$this->session->set_flashdata('saved', $flashmsg);
		redirect(current_url());
	}


	public function apply_week()
	{
		$session_id = $this->input->post('session_id');

		if (empty($session_id)) {
			redirect('sessions');
		}

		$week_id = $this->input->post('week_id');
		$week = $this->weeks_model->get($week_id);

		if (empty($week)) {
			$flashmsg = msgbox('error', 'Nenhuma semana selecionada.');
			$this->session->set_flashdata('saved', $flashmsg);
			redirect("sessions/view/{$session_id}");
		}

		$this->dates_model->apply_week($session_id, $week_id);

		$flashmsg = msgbox('info', sprintf("%s foi aplicado a todas as semanas na sessão.", html_escape($week->name)));
		$this->session->set_flashdata('saved', $flashmsg);

		redirect("sessions/view/{$session_id}");
	}


	/**
	 * Add a new session.
	 *
	 */
	public function add()
	{
		$this->data['title'] = 'Adicionar Sessão';

		if ($this->input->post()) {
			$this->save_session();
		}

		$add = $this->load->view('sessions/add', $this->data, TRUE);
		$side = $this->load->view('sessions/add_side', $this->data, TRUE);

		$columns = [
			'c1' => ['content' => $add, 'width' => '70%'],
			'c2' => ['content' => $side, 'width' => '30%'],
		];

		$body = $this->load->view('columns', $columns, TRUE);

		$icons = iconbar($this->get_icons(), 'sessions');
		$this->data['body'] = $icons . $body;

		return $this->render();
	}


	public function edit($session_id)
	{
		$this->data['title'] = $this->data['showtitle'] = 'Editar Sessão';

		$this->data['session'] = $this->find_session($session_id);

		if ($this->input->post()) {
			$this->save_session($session_id);
		}

		$edit = $this->load->view('sessions/add', $this->data, TRUE);
		$side = $this->load->view('sessions/add_side', $this->data, TRUE);

		$columns = [
			'c1' => ['content' => $edit, 'width' => '70%'],
			'c2' => ['content' => $side, 'width' => '30%'],
		];

		$body = $this->load->view('columns', $columns, TRUE);

		$icons = iconbar($this->get_icons(), 'sessions');
		$this->data['body'] = $icons . $body;

		return $this->render();
	}


	/**
	 * Add or edit a session
	 *
	 */
	private function save_session($session_id = NULL)
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('name', 'Name', 'required|max_length[50]');
		$this->form_validation->set_rules('is_selectable', 'User-selectable', 'required|in_list[0,1]');

		$callbackRule = strlen($session_id)
			? sprintf('callback__date_check[%d]', $session_id)
			: 'callback__date_check';

		$this->form_validation->set_rules('date_start', 'Start date', "required|valid_date|{$callbackRule}");
		$this->form_validation->set_rules('date_end', 'End date', "required|valid_date|{$callbackRule}");

		$data = array(
			'name' => $this->input->post('name'),
			'is_selectable' => $this->input->post('is_selectable'),
			'date_start' => $this->input->post('date_start'),
			'date_end' => $this->input->post('date_end'),
		);

		if ($this->form_validation->run() == FALSE) {
			return FALSE;
		}

		$uri = "sessions";

		if ($session_id) {
			if ($this->sessions_model->update($session_id, $data)) {
				$line = sprintf($this->lang->line('crbs_action_saved'), $data['name']);
				$flashmsg = msgbox('info', $line);
			} else {
				$line = sprintf($this->lang->line('crbs_action_dberror'), 'editing');
				$flashmsg = msgbox('error', $line);
			}
		} else {
			if ($session_id = $this->sessions_model->insert($data)) {
				$uri = "sessions/view/{$session_id}";
				$line = sprintf($this->lang->line('crbs_action_added'), 'Session');
				$flashmsg = msgbox('info', $line);
			} else {
				$line = sprintf($this->lang->line('crbs_action_dberror'), 'adding');
				$flashmsg = msgbox('error', $line);
			}
		}

		$this->session->set_flashdata('saved', $flashmsg);
		redirect($uri);
	}




	/**
	 * Delete a session
	 *
	 */
	function delete($id)
	{
		$session = $this->find_session($id);

		if ($this->input->post('id')) {
			$this->sessions_model->delete($this->input->post('id'));
			$flashmsg = msgbox('info', $this->lang->line('crbs_action_deleted'));
			$this->session->set_flashdata('saved', $flashmsg);
			redirect('sessions');
		}

		$this->data['action'] = current_url();
		$this->data['id'] = $id;
		$this->data['cancel'] = 'sessions';
		$this->data['text'] = 'Se você excluir esta sessão, <strong>todas os agendamentos</strong> e feriados durante esta sessão também serão <strong>excluídos permanentemente</strong>.';

		$this->data['title'] = sprintf('Deletar Sessão (%s)', html_escape($session->name));

		$title = "<h2>{$this->data['title']}</h2>";
		$body = $this->load->view('partials/deleteconfirm', $this->data, TRUE);
		$icons = iconbar($this->get_icons(), 'sessions');

		$this->data['body'] = $icons . $title . $body;

		return $this->render();
	}



	/**
	 * Validation: Ensure the date isn't part of another session. They can't overlap.
	 *
	 */
	public function _date_check($value, $session_id = NULL)
	{
		$session = $this->sessions_model->get_by_date($value, $session_id);

		if ($session) {
			$dt = datetime_from_string($value);
			$dtFormat = $dt->format('d/m/Y');
			$sessionName = $session->name;
			$msg = sprintf("O {field} (%s) já faz parte de uma sessão existente (%s).", $dtFormat, $sessionName);
			$this->form_validation->set_message('_date_check', $msg);
			return FALSE;
		}

		return TRUE;
	}


	/**
	 * Get and return a session by ID or show error page.
	 *
	 */
	private function find_session($session_id)
	{
		$session = $this->sessions_model->get($session_id);

		if (empty($session)) {
			show_404();
		}

		return $session;
	}
}

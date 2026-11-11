<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Holidays extends MY_Controller
{


	public function __construct()
	{
		parent::__construct();

		$this->require_logged_in();
		$this->require_auth_level(ADMINISTRATOR);

		$this->load->model('sessions_model');
		$this->load->model('crud_model');
		$this->load->model('weeks_model');
		$this->load->model('holidays_model');

		$this->data['showtitle'] = 'Feriados';
	}


    public function import_year($session_id)
    {
        $target = $this->find_session($session_id);
        $this->load->library('holiday_import');
        $sources = array_merge($this->sessions_model->get_all_active() ?: [], $this->sessions_model->get_all_past() ?: []);
        $sources = array_filter($sources, function ($year) use ($target) { return $year->session_id != $target->session_id; });
        $source_id = $this->input->get_post('source_id');
        $source = null;
        foreach ($sources as $year) {
            if ((string) $year->session_id === (string) $source_id) { $source = $year; break; }
        }
        $holidays = $source ? ($this->holidays_model->get_by_session($source->session_id) ?: []) : [];
        $error = '';
        if (!$this->session->userdata('holiday_import_token')) {
            $this->session->set_userdata('holiday_import_token', bin2hex(random_bytes(32)));
        }
        if ($this->input->method(TRUE) === 'POST') {
            try {
                $token = $this->input->post('import_token');
                if (!is_string($token) || !hash_equals($this->session->userdata('holiday_import_token'), $token)) {
                    throw new InvalidArgumentException('Solicitação expirada. Recarregue a página.');
                }
                if (!$source || !$this->input->post('reviewed')) {
                    throw new InvalidArgumentException('Escolha o ano de origem e confirme a revisão das datas.');
                }
                $rows = $this->holiday_import->validate($holidays, $this->input->post('selected'), $this->input->post('starts'), $this->input->post('ends'), $target);
                $result = $this->holidays_model->import_reviewed($target->session_id, $rows);
                $this->session->set_flashdata('saved', msgbox('info', sprintf('%d feriado(s) importado(s); %d duplicado(s) ignorado(s).', $result['inserted'], $result['skipped'])));
                redirect('holidays/session/' . $target->session_id);
                return;
            } catch (InvalidArgumentException $e) {
                $error = $e->getMessage();
            } catch (Throwable $e) {
                log_message('error', 'Holiday import failed: ' . $e->getMessage());
                $error = 'Não foi possível concluir a importação. Nenhuma alteração foi confirmada.';
            }
        }
        $this->data['title'] = $this->data['showtitle'] = 'Importar feriados para ' . $target->name;
        $data = ['target' => $target, 'sources' => $sources, 'source' => $source, 'holidays' => $holidays,
            'error' => $error, 'import_token' => $this->session->userdata('holiday_import_token')];
        $this->data['body'] = iconbar($this->get_icons($target)) . $this->load->view('holidays/import_year', $data, TRUE);
        return $this->render();
    }


	private function get_icons($session = NULL)
	{
		$items = [
			['sessions', 'Anos letivos', 'calendar_view_month.png'],
		];

		if ($session) {
			$items[] = ['sessions/view/' . $session->session_id, $session->name, 'calendar_view_day.png'];
			$items[] = ['holidays/session/' . $session->session_id, 'Feriados', 'school_manage_holidays.png'];
		}

		return $items;
	}




	public function session($session_id)
	{
		$session = $this->find_session($session_id);

		$this->data['session'] = $session;
		$this->data['holidays'] = $this->holidays_model->get_by_session($session->session_id);
		$this->data['title'] = $this->data['showtitle'] = 'Ano letivo: ' . $session->name . ': Feriado';

		$icons = iconbar($this->get_icons($session), 'holidays/session/' . $session->session_id);

		$body = $this->load->view('holidays/index', $this->data, TRUE);

		$this->data['body'] = $icons . $body;

		return $this->render();
	}




	/**
	 * Adicionar um novo feriado
	 *
	 */
	function add()
	{
		$session_id = $this->input->get_post('session_id');
		$session = $this->find_session($session_id);

		$this->data['session'] = $session;
		$this->data['title'] = $this->data['showtitle'] = 'Ano letivo: ' . $session->name . ': Adicionar Feriado';

		if ($this->input->post()) {
			$this->save_holiday();
		}

		$add = $this->load->view('holidays/add', $this->data, TRUE);
		$side = $this->load->view('holidays/add_side', $this->data, TRUE);

		$columns = [
			'c1' => ['content' => $add, 'width' => '70%'],
			'c2' => ['content' => $side, 'width' => '30%'],
		];
		$body = $this->load->view('columns', $columns, TRUE);
		$icons = iconbar($this->get_icons($session), 'holidays/session/' . $session->session_id);
		$this->data['body'] = $icons . $body;

		return $this->render();
	}




	/**
	 * Editar Feriado
	 *
	 */
	function edit($id)
	{
		$this->data['holiday'] = $this->find_holiday($id);

		$session = $this->sessions_model->get($this->data['holiday']->session_id);

		$this->data['session'] = $session;
		$this->data['title'] = $this->data['showtitle'] = 'Ano letivo: ' . $session->name . ': Editar Feriado';

		if ($this->input->post()) {
			$this->save_holiday($this->data['holiday']->holiday_id);
		}

		$add = $this->load->view('holidays/add', $this->data, TRUE);
		$side = $this->load->view('holidays/add_side', $this->data, TRUE);

		$columns = [
			'c1' => ['content' => $add, 'width' => '70%'],
			'c2' => ['content' => $side, 'width' => '30%'],
		];
		$body = $this->load->view('columns', $columns, TRUE);
		$icons = iconbar($this->get_icons($session), 'holidays/session/' . $session->session_id);
		$this->data['body'] = $icons . $body;

		return $this->render();
	}



	/**
	 * Adicionar ou Editar feriado
	 *
	 */
	private function save_holiday($holiday_id = NULL)
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('name', 'Name', 'required|max_length[50]');

		$callbackRule = sprintf('callback__date_check[%d]', $this->data['session']->session_id);
		$this->form_validation->set_rules('date_start', 'Start date', "required|{$callbackRule}");
		$this->form_validation->set_rules('date_end', 'End date', "required|{$callbackRule}");

		$data = array(
			'session_id' => $this->data['session']->session_id,
			'name' => $this->input->post('name'),
			'date_start' => $this->input->post('date_start'),
			'date_end' => $this->input->post('date_end'),
		);

		if ($this->form_validation->run() == FALSE) {
			return FALSE;
		}

		$uri = 'holidays/session/' . $this->data['session']->session_id;

		if ($holiday_id) {
			if ($this->holidays_model->update($holiday_id, $data)) {
				$line = sprintf($this->lang->line('crbs_action_saved'), $data['name']);
				$flashmsg = msgbox('info', $line);
			} else {
				$line = sprintf($this->lang->line('crbs_action_dberror'), 'editing');
				$flashmsg = msgbox('error', $line);
			}
		} else {
			if ($holiday_id = $this->holidays_model->insert($data)) {
				$line = sprintf($this->lang->line('crbs_action_added'),  $data['name']);
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
	 * Delete a holiday
	 *
	 */
	function delete($id)
	{
		$holiday = $this->find_holiday($id);

		$session = $this->sessions_model->get($holiday->session_id);

		$this->data['session'] = $session;
		$this->data['title'] = $this->data['showtitle'] = 'Ano letivo: ' . $session->name . ': Deletar Feriado';

		if ($this->input->post('id')) {
			$this->holidays_model->delete($this->input->post('id'));
			$flashmsg = msgbox('info', $this->lang->line('crbs_action_deleted'));
			$this->session->set_flashdata('saved', $flashmsg);
			redirect('holidays/session/' . $session->session_id);
		}

		$this->data['action'] = current_url();
		$this->data['id'] = $id;
		$this->data['cancel'] = 'holidays/session/' . $session->session_id;
		// $this->data['text'] = 'If you delete this holiday, <strong>all bookings</strong> and holidays during this session will be <strong>permanently deleted</strong> as well.';

		$this->data['title'] = sprintf('Deletar Feriado (%s)', html_escape($holiday->name));

		$title = "<h2>{$this->data['title']}</h2>";
		$body = $this->load->view('partials/deleteconfirm', $this->data, TRUE);
		$icons = iconbar($this->get_icons($session), 'holidays/session/' . $session->session_id);

		$this->data['body'] = $icons . $title . $body;

		return $this->render();
	}



	/**
	 * Validation: Ensure the date is valid and in range of the session
	 *
	 */
	public function _date_check($value, $session_id = NULL)
	{
		$rule = '_date_check';
		$session = $this->sessions_model->get($session_id);

		if (!$session) {
			$this->form_validation->set_message($rule, 'O ano letivo não pôde ser carregado.');
			return FALSE;
		}

		$dt = datetime_from_string($value);
		if (!$dt) {
			$msg = sprintf("O valor de {field} '%s' não parece uma data válida.", $value);
			$this->form_validation->set_message($rule, $msg);
			return FALSE;
		}

		if ($dt < $session->date_start || $dt > $session->date_end) {
			$start_fmt = $session->date_start->format('d/m/Y');
			$end_fmt = $session->date_end->format('d/m/Y');
			$msg = sprintf("O {field} deve estar entre %s e %s.", $start_fmt, $end_fmt);
			$this->form_validation->set_message($rule, $msg);
			return FALSE;
		}

		return TRUE;
	}



	/**
	 * Get and return a holiday by ID or show error page.
	 *
	 */
	private function find_holiday($holiday_id)
	{
		$holiday = $this->holidays_model->get($holiday_id);

		if (empty($holiday)) {
			show_404();
		}

		return $holiday;
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

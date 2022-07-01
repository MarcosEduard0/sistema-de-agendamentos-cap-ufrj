<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Holidays extends MY_Controller
{


	public function __construct()
	{
		parent::__construct();

		$this->require_logged_in();
		$this->require_auth_level(ADMINISTRADOR);

		$this->load->model('crud_model');
		$this->load->model('weeks_model');
		$this->load->model('holidays_model');
	}




	function index()
	{
		$this->data['holidays'] = $this->holidays_model->Get();

		$this->data['title'] = 'Feriados Escolares';
		$this->data['showtitle'] = $this->data['title'];
		$this->data['body'] = $this->load->view('holidays/holidays_index', $this->data, TRUE);
		return $this->render();
	}




	/**
	 * Lidar com a página Adicionar
	 *
	 */
	function add()
	{
		$this->data['title'] = 'Novo Feriado';
		$this->data['showtitle'] = $this->data['title'];

		$columns = array(
			'c1' => array(
				'content' => $this->load->view('holidays/holidays_add', NULL, TRUE),
				'width' => '70%',
			),
			'c2' => array(
				'content' => $this->load->view('holidays/holidays_add_side', NULL, TRUE),
				'width' => '30%',
			),
		);

		$this->data['body'] = $this->load->view('columns', $columns, TRUE);

		return $this->render();
	}




	/**
	 * Função do controlador para lidar com a página de edição
	 *
	 */
	function edit($id = NULL)
	{
		$this->data['holiday'] = $this->holidays_model->Get($id);

		if (empty($this->data['holiday'])) {
			show_404();
		}

		// Carregar view
		$this->data['title'] = 'Editar Feriado';
		$this->data['showtitle'] = $this->data['title'];

		$columns = array(
			'c1' => array(
				'content' => $this->load->view('holidays/holidays_add', $this->data, TRUE),
				'width' => '70%',
			),
			'c2' => array(
				'content' => $this->load->view('holidays/holidays_add_side', $this->data, TRUE),
				'width' => '30%',
			),
		);

		$this->data['body'] = $this->load->view('columns', $columns, TRUE);

		return $this->render();
	}




	function save()
	{
		// Obter ID do formulário
		$holiday_id = $this->input->post('holiday_id');

		$this->load->library('form_validation');

		$this->form_validation->set_rules('holiday_id', 'ID', 'integer');
		$this->form_validation->set_rules('name', 'Name', 'required|min_length[1]|max_length[30]');
		$this->form_validation->set_rules('date_start', 'Start date', 'required|min_length[8]|max_length[10]');
		$this->form_validation->set_rules('date_end', 'End date', 'required|min_length[8]|max_length[10]');;

		if ($this->form_validation->run() == FALSE) {
			return (empty($holiday_id) ? $this->add() : $this->edit($holiday_id));
		}

		$date_format = "Y-m-d";

		$start_date = explode('-', $this->input->post('date_start'));
		$end_date = explode('-', $this->input->post('date_end'));

		$holiday_data = array(
			'name' => $this->input->post('name'),
			'date_start' =>	sprintf("%s-%s-%s", $start_date[0], $start_date[1], $start_date[2]),
			'date_end' => sprintf("%s-%s-%s", $end_date[0], $end_date[1], $end_date[2]),
		);

		if (empty($holiday_id)) {

			$holiday_id = $this->holidays_model->Add($holiday_data);

			if ($holiday_id) {
				$line = sprintf($this->lang->line('crbs_action_added'), $holiday_data['name']);
				$flashmsg = msgbox('info', $line);
			} else {
				$line = sprintf($this->lang->line('crbs_action_dberror'), 'adding');
				$flashmsg = msgbox('error', $line);
			}
		} else {

			if ($this->holidays_model->Edit($holiday_id, $holiday_data)) {
				$line = sprintf($this->lang->line('crbs_action_saved'), $holiday_data['name']);
				$flashmsg = msgbox('info', $line);
			} else {
				$line = sprintf($this->lang->line('crbs_action_dberror'), 'editing');
				$flashmsg = msgbox('error', $line);
			}
		}

		$this->session->set_flashdata('saved', $flashmsg);
		redirect('holidays');
	}




	/**
	 *Excluir um feriado
	 *
	 */
	function delete($id = NULL)
	{
		// Verifique se um formulário foi enviado; se não - mostre para pedir confirmação ao usuário
		if ($this->input->post('id')) {
			$this->holidays_model->Delete($this->input->post('id'));
			$flashmsg = msgbox('info', $this->lang->line('crbs_action_deleted'));
			$this->session->set_flashdata('saved', $flashmsg);
			redirect('holidays');
		}

		// Página inicial
		$this->data['action'] = 'holidays/delete';
		$this->data['id'] = $id;
		$this->data['cancel'] = 'holidays';

		$row = $this->holidays_model->Get($id);
		$this->data['title'] = 'Deletar Feriado (' . html_escape($row->name) . ')';
		$this->data['showtitle'] = $this->data['title'];
		$this->data['body'] = $this->load->view('partials/deleteconfirm', $this->data, TRUE);
		return $this->render();
	}




	/**
	 *Atualizar todos os feriados para o ano atual
	 *
	 */
	function update($id = NULL)
	{

		$holidays = $this->holidays_model->Get();

		foreach ($holidays as $holiday) {

			$start_date = explode('-', $holiday->date_start);
			$end_date = explode('-', $holiday->date_end);
			$year_data = array(
				'date_start' => sprintf("%s-%s-%s", date('Y'), $start_date[1], $start_date[2]),
				'date_end' => sprintf("%s-%s-%s", date('Y'), $end_date[1], $end_date[2]),
			);
			$this->holidays_model->Edit($holiday->holiday_id, $year_data);
		}

		$flashmsg = msgbox('info', $this->lang->line('crbs_holiday_update'));
		$this->session->set_flashdata('saved', $flashmsg);
		redirect('holidays');
	}
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setup extends MY_Controller
{


	public function __construct()
	{
		parent::__construct();
		$this->require_auth_level(ADMINISTRATOR);
	}


	public function index()
	{
		$this->data['styles'][] = 'assets/css/setup.css';
		$data = [
			'school_menu' => $this->menu_model->setup_school(),
			'manage_menu' => $this->menu_model->setup_manage(),
		];

		$this->data['title'] = 'Início';
		$this->data['body'] = '';
		$this->data['body'] .= $this->session->flashdata('auth');
		$this->data['body'] .= $this->load->view('setup/index', $data, TRUE);

		return $this->render();
	}
}

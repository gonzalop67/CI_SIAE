<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Codigofacilito extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->helper('form');
		$this->load->model('curso_model');
	}

	function index()
	{
		$this->load->library('menu',array('Inicio','Contacto','Cursos'));
		$data['mi_menu'] = $this->menu->construirMenu();
		$this->load->view('codigofacilito/headers');
		$this->load->view('codigofacilito/bienvenido',$data);
	}

	function holamundo()
	{
		$this->load->view('codigofacilito/headers');
		$this->load->view('codigofacilito/bienvenido');
	}

	function nuevo(){
		$this->load->view('codigofacilito/headers');
		$this->load->view('codigofacilito/formulario');
	}

	function recibirDatos(){
		$data = array(
			'nombre' => $this->input->post('nombre'),
			'videos' => $this->input->post('videos')
		);
		$this->curso_model->crearCurso($data);
		$this->load->view('codigofacilito/headers');
		$this->load->view('codigofacilito/bienvenido');
	}
}
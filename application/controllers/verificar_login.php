<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Verificar_login extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('producto_model');
        $this->load->model('institucion_model');
        $this->load->model('verificar_login_model');
    }

    public function index() {
        $login = $this->input->post('login');
        $clave = $this->input->post('passwd');
        $perfil = $this->input->post('id_perfil');
        if($this->verificar_login_model->existeUsuario($login, $clave, $perfil)){
            $this->load->view('admin_view');
        } else {
            redirect(site_url('login/existeError'));
            //$this->load->view('login_error_view', $data);
        }
    }

}

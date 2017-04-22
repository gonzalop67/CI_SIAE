<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('perfil_model');
        $this->load->model('usuario_model');
        $this->load->model('producto_model');
        $this->load->model('institucion_model');
        $this->load->model('periodo_lectivo_model');
    }

    public function iniciar_sesion() {
        $id_institucion = $this->input->post('cboInstitucion');
        $data['nom_producto'] = $this->producto_model->obtenerProducto()->pr_nombre;
        $data['desc_producto'] = $this->producto_model->obtenerProducto()->pr_descripcion;
        $data['nom_institucion'] = $this->institucion_model->obtenerInstitucion($id_institucion)->in_nombre;
        $data['perfiles'] = $this->perfil_model->obtenerPerfiles();
        $data['periodos_lectivos'] = $this->periodo_lectivo_model->obtenerPeriodosLectivos();
        $this->session->set_userdata('id_institucion', $id_institucion);
        $this->load->view('login_view', $data);
    }
    
    public function iniciar_sesion_error() {
        $id_institucion = $this->session->userdata('id_institucion');
        $data['nom_institucion'] = $this->institucion_model->obtenerInstitucion($id_institucion)->in_nombre;
        $data['nom_producto'] = $this->producto_model->obtenerProducto()->pr_nombre;
        $data['desc_producto'] = $this->producto_model->obtenerProducto()->pr_descripcion;
        $data['error'] = $this->session->flashdata('error');
        $data['perfiles'] = $this->perfil_model->obtenerPerfiles();
        $data['periodos_lectivos'] = $this->periodo_lectivo_model->obtenerPeriodosLectivos();
        $this->load->view('login_view', $data);
    }
    
    public function iniciar_sesion_post() {
        if ($this->input->post()) {
            $login = $this->input->post('uname');
            $clave = $this->input->post('passwd');
            $perfil = $this->input->post('cboPerfil');
            $periodo = $this->input->post('cboPeriodo');
            
            $usuario = $this->usuario_model->existeUsuario($login, $clave, $perfil);
            
            if ($usuario) {
                $usuario_data = array(
                    'id_usuario' => $usuario->id_usuario,
                    'id_perfil' => $perfil,
                    'id_periodo_lectivo' => $periodo,
                    'logueado' => 1
                );
                $this->session->set_userdata($usuario_data);
                redirect(site_url('login/logueado'));
            } else {
                $this->session->set_flashdata('error', 'Usuario o Contraseña o Perfil incorrectos.');
                redirect(site_url('login/iniciar_sesion_error'));
            }
        } else {
            $this->iniciar_sesion();
        }
    }

    public function logueado() {
        if ($this->session->userdata('logueado')) {
            $id_usuario = $this->session->userdata('id_usuario');
            $id_perfil = $this->session->userdata('id_perfil');
            $id_periodo_lectivo = $this->session->userdata('id_periodo_lectivo');
            $data['logueado'] = $this->session->userdata('logueado');
            $id_institucion = $this->session->userdata('id_institucion');
            $data['nom_institucion'] = $this->institucion_model->obtenerInstitucion($id_institucion)->in_nombre;
            $data['nom_producto'] = $this->producto_model->obtenerProducto()->pr_nombre;
            $data['desc_producto'] = $this->producto_model->obtenerProducto()->pr_descripcion;
            $data['nom_usuario'] = $this->usuario_model->obtenerUsuario($id_usuario)->us_fullname;
            $data['nom_perfil'] = $this->perfil_model->obtenerPerfil($id_perfil)->pe_nombre;
            $data['nom_periodoLectivo'] = $this->periodo_lectivo_model->obtenerNombrePeriodoLectivo($id_periodo_lectivo);
            $data['listarMenusNivel1'] = $this->Menu_model->listarMenusNivel1($id_perfil);
            $this->load->view('admin_view.php', $data);
        } else {
            redirect(site_url('usuarios/iniciar_sesion'));
        }
    }

    public function cerrar_sesion() {
        $usuario_data = array(
           'logueado' => 0
        );
        $this->session->set_userdata($usuario_data);
        redirect(site_url('home'));
   }
}

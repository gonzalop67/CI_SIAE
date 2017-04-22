<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Perfil extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('perfil_model');
        $this->load->model('usuario_model');
        $this->load->model('producto_model');
        $this->load->model('institucion_model');
        $this->load->model('periodo_lectivo_model');
    }
    
    public function index() {
        $data['logueado'] = $this->session->userdata('logueado');
        $id_institucion = $this->session->userdata('id_institucion');
        $data['nom_institucion'] = $this->institucion_model->obtenerInstitucion($id_institucion)->in_nombre;
        $data['nom_producto'] = $this->producto_model->obtenerProducto()->pr_nombre;
        $data['desc_producto'] = $this->producto_model->obtenerProducto()->pr_descripcion;
        $id_usuario = $this->session->userdata('id_usuario');
        $data['nom_usuario'] = $this->usuario_model->obtenerUsuario($id_usuario)->us_fullname;
        $id_perfil = $this->session->userdata('id_perfil');
        $data['nom_perfil'] = $this->perfil_model->obtenerPerfil($id_perfil)->pe_nombre;
        $id_periodo_lectivo = $this->session->userdata('id_periodo_lectivo');
        $data['nom_periodoLectivo'] = $this->periodo_lectivo_model->obtenerNombrePeriodoLectivo($id_periodo_lectivo);
        $data['listarMenusNivel1'] = $this->Menu_model->listarMenusNivel1($id_perfil);
        $data['perfiles'] = $this->perfil_model->obtenerPerfiles();
        $this->load->view('perfil/index_perfil_view', $data);
    }
    
    public function getPerfiles() {
        $resultado = $this->perfil_model->obtenerPerfiles()->result();
        echo json_encode($resultado);
    }

    public function nuevo() {
        $data['logueado'] = $this->session->userdata('logueado');
        $id_institucion = $this->session->userdata('id_institucion');
        $data['nom_institucion'] = $this->institucion_model->obtenerInstitucion($id_institucion)->in_nombre;
        $data['nom_producto'] = $this->producto_model->obtenerProducto()->pr_nombre;
        $data['desc_producto'] = $this->producto_model->obtenerProducto()->pr_descripcion;
        $id_usuario = $this->session->userdata('id_usuario');
        $data['nom_usuario'] = $this->usuario_model->obtenerUsuario($id_usuario)->us_fullname;
        $id_perfil = $this->session->userdata('id_perfil');
        $data['nom_perfil'] = $this->perfil_model->obtenerPerfil($id_perfil)->pe_nombre;
        $id_periodo_lectivo = $this->session->userdata('id_periodo_lectivo');
        $data['nom_periodoLectivo'] = $this->periodo_lectivo_model->obtenerNombrePeriodoLectivo($id_periodo_lectivo);
        $data['listarMenusNivel1'] = $this->Menu_model->listarMenusNivel1($id_perfil);
        $this->load->view('perfil/new_perfil_view', $data);
    }
    
    public function recibirdatos() {
        $data = array(
            'nombre' => $this->input->post('nombre')
        );
        $existePerfil = $this->perfil_model->existeNombrePerfil($data['nombre']);
        if ($existePerfil) {
            echo json_encode(array("mensaje"=>"Ya existe el perfil digitado...","color"=>"red"));
        } else {
            $this->perfil_model->crearPerfil($data);
            echo json_encode(array("mensaje"=>"Datos ingresados exitosamente...","color"=>"blue"));
        }
    }
    
    function editar(){
        $data['logueado'] = $this->session->userdata('logueado');
        $id_institucion = $this->session->userdata('id_institucion');
        $id_usuario = $this->session->userdata('id_usuario');
        $id_perfil = $this->session->userdata('id_perfil');
        $id_periodo_lectivo = $this->session->userdata('id_periodo_lectivo');
        
        $data['nom_institucion'] = $this->institucion_model->obtenerInstitucion($id_institucion)->in_nombre;
        $data['nom_producto'] = $this->producto_model->obtenerProducto()->pr_nombre;
        $data['desc_producto'] = $this->producto_model->obtenerProducto()->pr_descripcion;
        
        $data['nom_usuario'] = $this->usuario_model->obtenerUsuario($id_usuario)->us_fullname;
        
        $data['nom_perfil'] = $this->perfil_model->obtenerPerfil($id_perfil)->pe_nombre;
        
        $data['nom_periodoLectivo'] = $this->periodo_lectivo_model->obtenerNombrePeriodoLectivo($id_periodo_lectivo);
        $data['listarMenusNivel1'] = $this->Menu_model->listarMenusNivel1($id_perfil);
        
        $data['id'] = $this->uri->segment(3);
        $data['perfil'] = $this->perfil_model->obtenerPerfil($data['id']);
        $this->load->view('perfil/editar_perfil_view',$data);
    }
    
    function actualizar(){
        $data = array(
            'nombre' => $this->input->post('nombre')
        );
        $this->perfil_model->actualizarPerfil($this->uri->segment(3), $data);
        echo json_encode(array("mensaje"=>"Perfil actualizado exitosamente...","color"=>"blue"));
    }
    
    function eliminar(){
        $id = $this->input->post('id_perfil');
        if ($this->perfil_model->existeUsuariosPerfil($id) > 0){
            echo json_encode(array("mensaje"=>"El perfil no se puede eliminar porque tiene usuarios relacionados..."));
        } else {
            $this->perfil_model->eliminarPerfil($id);
            echo json_encode(array("mensaje"=>"Perfil eliminado exitosamente..."));
        }        
    }
}

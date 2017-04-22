<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Institucion extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('perfil_model');
        $this->load->model('usuario_model');
        $this->load->model('producto_model');
        $this->load->model('institucion_model');
        $this->load->model('periodo_lectivo_model');
    }
    
    function index() {
        $id_usuario = $this->session->userdata('id_usuario');
        $id_perfil = $this->session->userdata('id_perfil');
        $id_periodo_lectivo = $this->session->userdata('id_periodo_lectivo');
        $data['logueado'] = $this->session->userdata('logueado');
        $data['nom_producto'] = $this->producto_model->obtenerProducto()->pr_nombre;
        $data['desc_producto'] = $this->producto_model->obtenerProducto()->pr_descripcion;
        $data['nom_usuario'] = $this->usuario_model->obtenerUsuario($id_usuario)->us_fullname;
        $data['nom_perfil'] = $this->perfil_model->obtenerPerfil($id_perfil)->pe_nombre;
        $data['nom_periodoLectivo'] = $this->periodo_lectivo_model->obtenerNombrePeriodoLectivo($id_periodo_lectivo);
        $data['listarMenusNivel1'] = $this->Menu_model->listarMenusNivel1($id_perfil);
        $data['instituciones'] = $this->institucion_model->obtenerInstituciones();
        $this->load->view('institucion/index_institucion_view.php', $data);
    }
    
    public function nuevo() {
        $data['logueado'] = $this->session->userdata('logueado');
        $data['nom_producto'] = $this->producto_model->obtenerProducto()->pr_nombre;
        $data['desc_producto'] = $this->producto_model->obtenerProducto()->pr_descripcion;
        $id_usuario = $this->session->userdata('id_usuario');
        $data['nom_usuario'] = $this->usuario_model->obtenerUsuario($id_usuario)->us_fullname;
        $id_perfil = $this->session->userdata('id_perfil');
        $data['nom_perfil'] = $this->perfil_model->obtenerPerfil($id_perfil)->pe_nombre;
        $id_periodo_lectivo = $this->session->userdata('id_periodo_lectivo');
        $data['nom_periodoLectivo'] = $this->periodo_lectivo_model->obtenerNombrePeriodoLectivo($id_periodo_lectivo);
        $data['listarMenusNivel1'] = $this->Menu_model->listarMenusNivel1($id_perfil);
        $this->load->view('institucion/new_institucion_view', $data);
    }
    
    public function recibirdatos() {
        $data = array(
            'nombre' => $this->input->post('nombre'),
            'direccion' => $this->input->post('direccion'),
            'telefono' => $this->input->post('telefono'),
            'rector' => $this->input->post('rector'),
            'secretario' => $this->input->post('secretario'),
        );
        $existeInstitucion = $this->institucion_model->existeInstitucion($data['nombre']);
        if ($existeInstitucion) {
            echo json_encode(array("mensaje"=>"Ya existe la institución en la base de datos...","color"=>"red"));
        } else {
            $this->institucion_model->crearInstitucion($data);
            echo json_encode(array("mensaje"=>"Datos ingresados exitosamente...","color"=>"blue"));
        }
    }
    
    public function editar(){
        $data['logueado'] = $this->session->userdata('logueado');
        $id_usuario = $this->session->userdata('id_usuario');
        $id_perfil = $this->session->userdata('id_perfil');
        $id_periodo_lectivo = $this->session->userdata('id_periodo_lectivo');
        
        $data['nom_producto'] = $this->producto_model->obtenerProducto()->pr_nombre;
        $data['desc_producto'] = $this->producto_model->obtenerProducto()->pr_descripcion;
        
        $data['nom_usuario'] = $this->usuario_model->obtenerUsuario($id_usuario)->us_fullname;
        
        $data['nom_perfil'] = $this->perfil_model->obtenerPerfil($id_perfil)->pe_nombre;
        
        $data['nom_periodoLectivo'] = $this->periodo_lectivo_model->obtenerNombrePeriodoLectivo($id_periodo_lectivo);
        $data['listarMenusNivel1'] = $this->Menu_model->listarMenusNivel1($id_perfil);
        
        $data['id'] = $this->uri->segment(3);
        $data['institucion'] = $this->institucion_model->obtenerInstitucion($data['id']);
        $this->load->view('institucion/editar_institucion_view',$data);
    }
    
    public function actualizar(){
        $data = array(
            'in_nombre' => $this->input->post('nombre'),
            'in_direccion' => $this->input->post('direccion'),
            'in_telefono' => $this->input->post('telefono'),
            'in_nom_rector' => $this->input->post('rector'),
            'in_nom_secretario' => $this->input->post('secretario')
        );
        $this->institucion_model->actualizarInstitucion($this->uri->segment(3), $data);
        echo json_encode(array("mensaje"=>"Institución actualizada exitosamente...","color"=>"blue"));
    }

    public function eliminar(){
        $id = $this->input->post('id_institucion');
        if ($this->institucion_model->existeUsuariosInstitucion($id) > 0){
            echo json_encode(array("mensaje"=>"La institución no se puede eliminar porque tiene usuarios relacionados..."));
        } else {
            $this->institucion_model->eliminarInstitucion($id);
            echo json_encode(array("mensaje"=>"Institución eliminada exitosamente..."));
        }        
    }

}

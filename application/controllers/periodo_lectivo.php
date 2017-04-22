<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Periodo_lectivo extends CI_Controller {

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
        $data['periodos_lectivos'] = $this->periodo_lectivo_model->obtenerPeriodosLectivos();
        $this->load->view('periodo_lectivo/index_periodo_lectivo_view.php', $data);
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
        $this->load->view('periodo_lectivo/new_periodo_lectivo_view', $data);
    }
    
    public function recibirdatos() {
        $data = array(
            'pe_anio_inicio' => $this->input->post('anio_inicio'),
            'pe_anio_fin' => $this->input->post('anio_fin'),
            'pe_estado' => 'A',
            'id_institucion' => $this->session->userdata('id_institucion')
        );
        $existePeriodoLectivo = $this->periodo_lectivo_model->existePeriodoLectivo($data['pe_anio_inicio']);
        if ($existePeriodoLectivo) {
            $mensaje = "Ya existe el periodo lectivo en la base de datos...";
            $color = "red";
        } else {
            $this->periodo_lectivo_model->crearPeriodoLectivo($data);
            $mensaje = "Periodo Lectivo ingresado exitosamente...";
            $color = "blue";
        }
        echo json_encode(array("mensaje"=>$mensaje,"color"=>$color));
    }
    
    public function ver(){
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
        $data['periodo_lectivo'] = $this->periodo_lectivo_model->obtenerPeriodoLectivo($data['id']);
        $this->load->view('periodo_lectivo/ver_periodo_lectivo_view',$data);
    }
    
    public function eliminar(){
        $id = $this->input->post('id_periodo_lectivo');
        if ($this->periodo_lectivo_model->existeTiposEducacionPeriodoLectivo($id) > 0){
            echo json_encode(array("mensaje"=>"El periodo lectivo no se puede eliminar porque tiene tipos de educación relacionados..."));
        } else {
            $this->periodo_lectivo_model->eliminarPeriodoLectivo($id);
            echo json_encode(array("mensaje"=>"Periodo Lectivo eliminado exitosamente..."));
        }        
    }

}

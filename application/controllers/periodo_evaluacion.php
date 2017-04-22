<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Periodo_evaluacion extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('perfil_model');
        $this->load->model('usuario_model');
        $this->load->model('producto_model');
        $this->load->model('institucion_model');
        $this->load->model('periodo_lectivo_model');
        $this->load->model('periodo_evaluacion_model');
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
        $data['periodos_evaluacion'] = $this->periodo_evaluacion_model->obtenerPeriodosEvaluacion($id_periodo_lectivo, $id_institucion);
        $this->load->view('periodo_evaluacion/index_periodo_evaluacion_view', $data);
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
        $this->load->view('periodo_evaluacion/new_periodo_evaluacion_view', $data);
    }
    
    public function recibirdatos() {
        $data = array(
            'pe_nombre' => $this->input->post('nombre'),
            'pe_abreviatura' => $this->input->post('abreviatura'),
            'pe_principal' => $this->input->post('tipo'),
            'id_periodo_lectivo' => $this->session->userdata('id_periodo_lectivo'),
            'id_institucion' => $this->session->userdata('id_institucion')
        );
        $existePeriodoEvaluacion = $this->periodo_evaluacion_model->existeNombrePeriodoEvaluacion($data['pe_nombre'],$data['id_periodo_lectivo'],$data['id_institucion']);
        if ($existePeriodoEvaluacion) {
            echo json_encode(array("mensaje"=>"Ya existe el periodo de evaluación digitado...","color"=>"red"));
        } else {
            $this->periodo_evaluacion_model->crearPeriodoEvaluacion($data);
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
        $data['periodo_evaluacion'] = $this->periodo_evaluacion_model->obtenerPeriodoEvaluacion($data['id']);
        $this->load->view('periodo_evaluacion/editar_periodo_evaluacion_view',$data);
    }
    
    function actualizar(){
        $data = array(
            'pe_nombre' => $this->input->post('nombre'),
            'pe_abreviatura' => $this->input->post('abreviatura')
        );
        $this->periodo_evaluacion_model->actualizarPeriodoEvaluacion($this->uri->segment(3), $data);
        echo json_encode(array("mensaje"=>"Periodo de Evaluación actualizado exitosamente...","color"=>"blue"));
    }
    
    function eliminar(){
        $id = $this->input->post('id_periodo_evaluacion');
        if ($this->periodo_evaluacion_model->existeAportesEvaluacion($id) > 0){
            echo json_encode(array("mensaje"=>"El periodo de evaluación no se puede eliminar porque tiene aportes de evaluación relacionados..."));
        } else {
            $this->periodo_evaluacion_model->eliminarPeriodoEvaluacion($id);
            echo json_encode(array("mensaje"=>"Periodo de Evaluación eliminado exitosamente..."));
        }        
    }
}

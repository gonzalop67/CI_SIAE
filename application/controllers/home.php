<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->model('producto_model');
        $this->load->model('institucion_model');
    }

    public function index() {
        $data['nom_producto'] = $this->producto_model->obtenerProducto()->pr_nombre;
        $data['desc_producto'] = $this->producto_model->obtenerProducto()->pr_descripcion;
        $data['instituciones'] = $this->institucion_model->obtenerInstituciones();
        $this->load->view('home_view',$data);
    }

}

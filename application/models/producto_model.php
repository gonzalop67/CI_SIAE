<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Producto_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function obtenerProducto() {
        $query = $this->db->get('sw_producto');
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

}


<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Perfil_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function obtenerPerfiles() {
        $this->db->order_by('pe_nombre', 'asc');
        $query = $this->db->get('sw_perfil');
        if ($query->num_rows() > 0) {
            return $query;
        } else {
            return false;
        }
    }

    function obtenerPerfil($id) {
        $this->db->where('id_perfil', $id);
        $query = $this->db->get('sw_perfil');
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    function existeNombrePerfil($nombrePerfil) {
        $this->db->select('id_perfil');
        $this->db->from('sw_perfil');
        $this->db->where('pe_nombre', $nombrePerfil);
        $consulta = $this->db->get();
        $resultado = $consulta->row();
        return $resultado;
    }
    
    function crearPerfil($data){
	$this->db->insert('sw_perfil',array('pe_nombre'=>$data['nombre']));
    }
    
    function actualizarPerfil($id, $data){
        $datos = array(
            'pe_nombre'=>$data['nombre']
        );
        $this->db->where('id_perfil',$id);
        $this->db->update('sw_perfil', $datos);
    }
    
    function existeUsuariosPerfil($id){
        $query = $this->db->query("SELECT * FROM sw_usuario WHERE id_perfil = $id");
        return $query->num_rows();
    }
    
    function eliminarPerfil($id){
        $query = "DELETE FROM sw_perfil WHERE id_perfil = $id";
        $this->db->query($query);
    }
}


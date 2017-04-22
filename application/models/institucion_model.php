<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Institucion_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function obtenerInstituciones() {
        $query = $this->db->get('sw_institucion');
        if ($query->num_rows() > 0)
            return $query;
        else
            return false;
    }

    function obtenerInstitucion($id) {
        $this->db->where('id_institucion', $id);
        $query = $this->db->get('sw_institucion');
        if ($query->num_rows() > 0)
            return $query->row();
        else
            return false;
    }
    
    function existeInstitucion($nombre) {
        $this->db->select('id_institucion');
        $this->db->from('sw_institucion');
        $this->db->where('in_nombre', $nombre);
        $consulta = $this->db->get();
        $resultado = $consulta->row();
        return $resultado;
    }
    
    function crearInstitucion($data){
        $this->db->insert('sw_institucion',
                    array('in_nombre'=>$data['nombre'],
                            'in_direccion'=>$data['direccion'],
                            'in_telefono'=>$data['telefono'],
                            'in_nom_rector'=>$data['rector'],
                            'in_nom_secretario'=>$data['secretario']
                            ));
    }

    function actualizarInstitucion($id, $data){
        $datos = array(
            'in_nombre' => $data['in_nombre'],
            'in_direccion' => $data['in_direccion'],
            'in_telefono' => $data['in_telefono'],
            'in_nom_rector' => $data['in_nom_rector'],
            'in_nom_secretario' => $data['in_nom_secretario']
        );
        $this->db->where('id_institucion',$id);
        $this->db->update('sw_institucion', $datos);
    }

    function existeUsuariosInstitucion($id){
        $query = $this->db->query("SELECT * FROM sw_usuario WHERE id_institucion = $id");
        return $query->num_rows();
    }

    function eliminarInstitucion($id){
        $query = "DELETE FROM sw_institucion WHERE id_institucion = $id";
        $this->db->query($query);
    }
    
}
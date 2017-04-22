<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Usuario_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('encrypter');
    }
    
    function crearUsuario($data){
        $this->db->insert('sw_usuario',
                    array('us_titulo'=>$data['titulo'],
                            'us_login'=>$data['login'],
                            'us_password'=>$data['clave'],
                            'us_fullname'=>$data['nombre_completo'],
                            'id_perfil'=>$data['perfil'],
                            'id_institucion'=>$data['id_institucion']
                            ));
    }
    
    function actualizarUsuario($id, $data){
        $datos = array(
            'us_titulo' => $data['us_titulo'],
            'us_login' => $data['us_login'],
            'us_password' => $this->encrypter->encrypt($data['us_password']),
            'us_fullname' => $data['us_fullname']
        );
        $this->db->where('id_usuario',$id);
        $this->db->update('sw_usuario', $datos);
    }

    function eliminarUsuario($id){
        $query = "DELETE FROM sw_usuario WHERE id_usuario = $id";
        $this->db->query($query);
    }
    
    function existeUsuario($login, $clave, $id_perfil) {
        $this->db->select('id_usuario');
        $this->db->from('sw_usuario');
        $this->db->where('us_login', $login);
        $this->db->where('us_password', $this->encrypter->encrypt($clave));
        $this->db->where('id_perfil', $id_perfil);
        $consulta = $this->db->get();
        $resultado = $consulta->row();
        return $resultado;
    }
    
    function actualizarClave($id_usuario, $clave_nueva) {
        $this->load->library('encrypter');
        $clave_cifrada = $this->encrypter->encrypt($clave_nueva);
        $this->db->set('us_password', $clave_cifrada);
        $this->db->where('id_usuario', $id_usuario);
	$query = $this->db->update('sw_usuario');
    }

    function obtenerUsuarios($id_institucion) {
        $this->db->select('id_usuario');
        $this->db->select('us_login');
        $this->db->select('us_fullname');
        $this->db->select('pe_nombre');    
        $this->db->from('sw_usuario u');
        $this->db->join('sw_perfil p', 'u.id_perfil = p.id_perfil');
        $this->db->where('id_institucion',$id_institucion);
        $this->db->order_by('pe_nombre','asc');
        $this->db->order_by('us_login','asc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query;
        } else {
            return false;
        }
    }
    
    function obtenerUsuario($id) {
        $this->db->where('id_usuario', $id);
        $query = $this->db->get('sw_usuario');
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

}


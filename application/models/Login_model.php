<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Login_model extends CI_Model {

    var $table = 'md_login';
    var $primary_key = "ID_LOGIN";
 
    public function __construct()
    {
        parent::__construct();
    }
    
    public function login_diperbolehkan() {
    	$this->db->where(array(
            'IP_LOGIN' => $this->input->ip_address(),
            'CREATED_LOGIN > ' => date('Y-m-d H:i:s', strtotime('-'.$this->pengaturan->getJedaPercobaanLogin().' minutes')),
            'STATUS_LOGIN' => 0
        ));
        $result = $this->db->get($this->table)->result();
        
        if(count($result) > $this->pengaturan->getBanyakPercobaanLogin()) 
            return FALSE;
        else 
            return TRUE;
    }
    
    public function login_salah($data) {
        $data = array(
            'IP_LOGIN' => $this->input->ip_address(),
            'DATA_LOGIN' => json_encode($data)
        );
        $this->db->insert($this->table, $data);
        
        return $this->db->affected_rows();
    }
    
    public function login_benar($data) {
        $data = array(
            'IP_LOGIN' => $this->input->ip_address(),
            'DATA_LOGIN' => json_encode($data),
            'STATUS_LOGIN' => 1
        );
        $this->db->insert($this->table, $data);
        
        return $this->db->affected_rows();
    }
}
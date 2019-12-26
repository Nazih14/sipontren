<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Hakakses_model
 *
 * @author Rohmad Eko Wahyudi
 */
class Hakakses_user_model extends CI_Model{
    //put your code here
    
    var $table = 'md_hakakses_user';
 
    public function __construct()
    {
        parent::__construct();
    }

    private function _get_table() 
    {
        $this->db->from($this->table);
        $this->db->join('md_hakakses', $this->table.'.HAKAKSES_HU = md_hakakses.ID_HAKAKSES');
        $this->db->join('md_user', $this->table.'.USER_HU = md_user.ID_USER');
    }
    
    public function get_all($select = false) {
        if($select) $this->db->select('ID_HAKAKSES, COLOR_HAKAKSES, NAME_HAKAKSES');
        $this->_get_table();
        $this->db->where('USER_HU', $this->session->userdata('ID_USER'));
        
        return $this->db->get()->result();
    }
 
    public function count_all()
    {
        $this->_get_table();
        $this->db->where('USER_HU', $this->session->userdata('ID_USER'));
        
        return $this->db->count_all_results();
    }

    public function get_by_id($id = NULL) {
        $this->db->select('md_hakakses.*');
        $this->_get_table();
        $this->db->where('USER_HU', $this->session->userdata('ID_USER'));
        if($id != NULL) $this->db->where('HAKAKSES_HU', $id);
        $query = $this->db->get();

        return $query->row();
    }

    public function cek_hakakses_user($HAKAKSES_HU, $USER_HU) {
        $this->db->from($this->table);
        $this->db->where(array(
            'HAKAKSES_HU' => $HAKAKSES_HU,
            'USER_HU' => $USER_HU,
        ));
        
        if($this->db->count_all_results() > 0) 
            return TRUE;
        else
            return FALSE;
    }

    public function save($data) {
        $this->db->insert($this->table, $data);

        return $this->db->insert_id();
    }

    public function delete_by_id($id) {
        $where = array('USER_HU' => $id);
        $this->db->delete($this->table, $where);
        
        return $this->db->affected_rows();
    }

    public function delete_by_where($where) {
        $this->db->delete($this->table, $where);
        
        return $this->db->affected_rows();
    }
}

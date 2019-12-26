<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Tagihan_model extends CI_Model
{
    public $table = 'keu_tagihan';
    public $primaryKey = 'ID_TAGIHAN';
    public $user = 'USER_TAGIHAN';

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_table($select = true)
    {
        if ($select) {
            $this->db->select('*, CONCAT("Rp ", FORMAT(NOMINAL_TAGIHAN, 2)) AS NOMIMAL_TAGIHAN_SHOW, CONCAT(NAMA_KELAS, " - ", NAMA_KEGIATAN) AS KELAS_KEGIATAN');
        }
        $this->db->from($this->table);
        $this->db->join('md_tahun_ajaran', 'ID_TA=TA_TAGIHAN');
        $this->db->join('md_kelas', 'ID_KELAS=KELAS_TAGIHAN');
        $this->db->join('md_kegiatan', 'ID_KEGIATAN=KEGIATAN_KELAS');
        $this->db->where('TA_TAGIHAN', $this->session->userdata('ID_TA'));
        $this->db->order_by('NAMA_TAGIHAN', 'ASC');
    }

    public function get_datatable()
    {
        $this->_get_table();
        $data = $this->db->get()->result();
        
        $result = array(
            "data" => $data
        );

        return $result;
    }
    
    public function get_by_id($id)
    {
        $this->_get_table();
        $this->db->where($this->primaryKey, $id);
        $result = $this->db->get()->row_array();
            
        return $result;
    }
        
    public function get_form_data($id)
    {
        $this->db->from($this->table);
        $this->db->where($this->primaryKey, $id);
        $result = $this->db->get()->row_array();
                    
        return $result;
    }
    
    public function get_all()
    {
        $this->db->select('ID_TAGIHAN as id, CONCAT(NAMA_TAGIHAN, " | ", NAMA_KELAS, " - ", NAMA_KEGIATAN) as title');
        $this->_get_table(FALSE);
        $result = $this->db->get();
        
        return $result->result();
    }
    
    public function save($data)
    {
        $data[$this->user] = $this->session->userdata('ID_USER');
        $data['TA_TAGIHAN'] = $this->session->userdata('ID_TA');
        if (isset($data[$this->primaryKey])) {
            $where = array($this->primaryKey => $data[$this->primaryKey]);
            unset($data[$this->primaryKey]);
            $result = $this->update($data, $where);
        } else {
            unset($data[$this->primaryKey]);
            $result = $this->insert($data);
        }
        
        return $result;
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);

        return $this->db->insert_id();
    }

    public function update($data, $where)
    {
        $this->db->update($this->table, $data, $where);
        
        return $this->db->affected_rows();
    }

    public function delete($id)
    {
        $where = array($this->primaryKey => $id);
        $this->db->delete($this->table, $where);
        
        return $this->db->affected_rows();
    }
}

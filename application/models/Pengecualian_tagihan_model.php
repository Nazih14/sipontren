<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Pengecualian_tagihan_model extends CI_Model {

    var $table = 'akad_santri';
    var $primaryKey = 'ID_AS';

    public function __construct() {
        parent::__construct();
    }

    private function _get_table() {
        $this->db->from($this->table);
        $this->db->join('md_santri', 'ID_SANTRI=SANTRI_AS');
        $this->db->join('(SELECT *, CONCAT(NAMA_KAMAR, " - ", NAMA_GEDUNG) AS KAMAR_GEDUNG FROM md_kamar INNER JOIN md_gedung ON GEDUNG_KAMAR=ID_GEDUNG) md_kamar', 'KAMAR_SANTRI=ID_KAMAR');
        $this->db->join('md_jenis_kelamin', 'ID_JK=JK_SANTRI');
        $this->db->where('TA_AS', $this->session->userdata('ID_TA'));
    }

    public function get_datatable($post) {
        $this->_get_table();
        $this->db->join('keu_pengecualian', 'TA_KPC=TA_AS AND SANTRI_AS=SANTRI_KPC', 'LEFT');
        $this->db->select('*, IF(TAGIHAN_KPC = '.$post['TAGIHAN_KPC'].', TAGIHAN_KPC, NULL) AS TAGIHAN_KPC_SHOW');
        $this->db->where(array(
            'AKTIF_SANTRI' => 1,
            'ALUMNI_SANTRI' => 0,
            'STATUS_MUTASI_SANTRI' => NULL,
            'ROMBEL_AS' => $post['ROMBEL_AS'],
        ));
//        $this->db->group_start();
//        $this->db->where('TAGIHAN_KPC', $post['TAGIHAN_KPC']);
//        $this->db->or_where('TAGIHAN_KPC', NULL);
//        $this->db->group_end();
        $this->db->order_by('TAGIHAN_KPC_SHOW', 'ASC');
        $data = $this->db->get()->result();
        
        $result = array();
        foreach ($data as $key => $detail) {
            $result[$detail->ID_SANTRI] = $detail;
        }
        
        $result = array(
            "data" => array_values($result)
        );

        return $result;
    }

    public function get_by_id($id) {
        $this->_get_table();
        $this->db->where($this->primaryKey, $id);
        $result = $this->db->get()->row_array();

        return $result;
    }

    public function get_tagihan($rombel) {
        $this->db->select('ID_TAGIHAN as id, NAMA_TAGIHAN as title');
        $this->db->from('keu_tagihan');
        $this->db->join('md_rombel', 'KELAS_ROMBEL=KELAS_TAGIHAN');
        $this->db->where('ID_ROMBEL', $rombel);
        $result = $this->db->get();

        return $result->result_array();
    }

    public function save($data) {
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

    public function prosesTagihan($data) {
        $data['TA_KPC'] = $this->session->userdata('ID_TA');
        if($data['STATUS_TAGIHAN']) { 
            unset($data['STATUS_TAGIHAN']);
            $data['USER_KPC'] = $this->session->userdata('ID_USER');
            $result = $this->db->insert('keu_pengecualian', $data);
        } else {// DIBUANG TAGIHANNYA
            unset($data['STATUS_TAGIHAN']);
            $result = $this->db->delete('keu_pengecualian', $data);
        }

        return $result;
    }

    public function insert($data) {
        $this->db->insert($this->table, $data);

        return $this->db->insert_id();
    }

    public function update($data, $where) {
        $this->db->update($this->table, $data, $where);

        return $this->db->affected_rows();
    }

    public function delete($id) {
        $where = array($this->primaryKey => $id);
        $this->db->delete($this->table, $where);

        return $this->db->affected_rows();
    }

}

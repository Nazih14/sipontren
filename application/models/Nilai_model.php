<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Nilai_model extends CI_Model {

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
        if (is_array($post)) {
            $this->db->where(array(
                'AKTIF_SANTRI' => 1,
                'ALUMNI_SANTRI' => 0,
                'STATUS_MUTASI_SANTRI' => NULL,
                'ROMBEL_AS' => $post['ROMBEL_AS'],
            ));
            $JADWAL_NILAI = ' AND JADWAL_NILAI=' . $post['JADWAL_NILAI'];
        } else {
            $this->db->where($post);
            $JADWAL_NILAI = '';
        }
        
        $this->_get_table();
        $this->db->join('(SELECT * FROM akad_nilai INNER JOIN akad_jadwal ON ID_AJ=JADWAL_NILAI INNER JOIN md_mapel ON MAPEL_AJ=ID_MAPEL INNER JOIN md_ustadz ON ID_UST=USTADZ_AJ WHERE TA_NILAI = ' . $this->session->userdata('ID_TA') . ' AND CAWU_NILAI = ' . $this->session->userdata('ID_CAWU') . $JADWAL_NILAI . ') akad_nilai', 'SANTRI_NILAI=ID_AS', 'LEFT');
        
        $data = $this->db->get();
        
        $result = array(
            "data" => $data->result()
        );

        return $result;
    }

    public function get_by_id($id) {
        $this->_get_table();
        $this->db->where($this->primaryKey, $id);
        $result = $this->db->get()->row_array();

        return $result;
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

    public function insert($data) {
        $this->db->insert($this->table, $data);

        return $this->db->insert_id();
    }

    public function proses_nilai($data) {
        $data['TA_NILAI'] = $this->session->userdata('ID_TA');
        $data['CAWU_NILAI'] = $this->session->userdata('ID_CAWU');

        $where = $data;
        unset($where['NILAI_NILAI']);
        $this->db->delete('akad_nilai', $where);

        $data['USER_NILAI'] = $this->session->userdata('ID_USER');
        $this->db->insert('akad_nilai', $data);

        return $this->db->affected_rows();
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

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Presensi_model extends CI_Model {

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
        $this->db->join('(SELECT * FROM akad_absensi WHERE TANGGAL_ABSENSI = "'.$this->datetime_handler->date_to_store($post['TANGGAL_ABSENSI']).'") akad_absensi', 'SANTRI_ABSENSI=ID_AS AND ROMBEL_AS=ROMBEL_ABSENSI', 'LEFT');
        $this->db->where(array(
            'AKTIF_SANTRI' => 1,
            'ALUMNI_SANTRI' => 0,
            'STATUS_MUTASI_SANTRI' => NULL,
            'ROMBEL_AS' => $post['ROMBEL_ABSENSI'],
        ));
        $this->db->group_start();
        $this->db->where('TANGGAL_ABSENSI', $this->datetime_handler->date_to_store($post['TANGGAL_ABSENSI']));
        $this->db->or_where('TANGGAL_ABSENSI', NULL);
        $this->db->group_end();
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

    public function prosesPresensi($data) {
        $data['TANGGAL_ABSENSI'] = $this->datetime_handler->date_to_store($data['TANGGAL_ABSENSI']);

        $where = array(
            'SANTRI_ABSENSI' => $data['SANTRI_ABSENSI'],
            'ROMBEL_ABSENSI' => $data['ROMBEL_ABSENSI'],
            'TANGGAL_ABSENSI' => $data['TANGGAL_ABSENSI'],
        );
        $this->db->delete('akad_absensi', $where);
        
        if ($data['ALASAN_ABSENSI'] != 'HADIR') {
            $data['TA_ABSENSI'] = $this->session->userdata('ID_TA');
            $data['CAWU_ABSENSI'] = $this->session->userdata('ID_CAWU');
            $data['USER_ABSENSI'] = $this->session->userdata('ID_USER');
            $this->db->insert('akad_absensi', $data);
        }

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

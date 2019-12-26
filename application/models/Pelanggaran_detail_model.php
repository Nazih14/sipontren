<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Pelanggaran_detail_model extends CI_Model {

    var $table = 'pelanggaran_santri';
    var $primaryKey = 'ID_PSN';
    var $user = 'USER_PSN';

    public function __construct() {
        parent::__construct();
    }

    private function _get_table() {
        $this->db->from($this->table);
        $this->db->join('pelanggaran_jenis', 'JENIS_PSN=ID_PJS AND TA_PJS=TA_PSN');
        $this->db->join('md_santri', 'ID_SANTRI=SANTRI_PSN');
        $this->db->join('(SELECT *, CONCAT(NAMA_KAMAR, " - ", NAMA_GEDUNG) AS KAMAR_GEDUNG FROM md_kamar INNER JOIN md_gedung ON GEDUNG_KAMAR=ID_GEDUNG) md_kamar', 'KAMAR_SANTRI=ID_KAMAR');
        $this->db->where('TA_PSN', $this->session->userdata('ID_TA'));
        $this->db->order_by('TANGGAL_PSN', 'DESC');
    }

    public function get_datatable($post) {
        $this->db->select($this->database_handler->set_select($post));
        $this->_get_table();
        $data = $this->db->get()->result();

        $result = array(
            "data" => $data
        );

        return $result;
    }

    public function get_form_data($id) {
        $this->db->from($this->table);
        $this->db->where($this->primaryKey, $id);
        $result = $this->db->get()->row_array();

        return $result;
    }

    public function get_by_id($id) {
        $this->_get_table();
        $this->db->where($this->primaryKey, $id);
        $result = $this->db->get()->row_array();

        return $result;
    }

    public function get_all() {
        $this->db->select('ID_PSN as id, CONCAT("KODE: ",KODE_BUKU, " | ", "NAMA: ", NAMA_BUKU, " | ", "PENGARANG: ",PENGARANG_BUKU) as title');
        $this->_get_table();
        $result = $this->db->get();

        return $result->result();
    }

    public function save($data) {
        $data['TA_PSN'] = $this->session->userdata('ID_TA');
        $data['TANGGAL_PSN'] = $this->datetime_handler->date_to_store($data['TANGGAL_PSN']);
        if ($this->user != '')
            $data[$this->user] = $this->session->userdata('ID_USER');
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

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Laporan_pengembalian_model extends CI_Model {

    var $table = 'perpus_santri';
    var $primaryKey = 'ID_PINJAM';
    var $user = '';

    public function __construct() {
        parent::__construct();
    }

    private function _get_table() {
        $this->db->from($this->table);
        $this->db->join('perpus_buku', 'BUKU_PINJAM=ID_BUKU');
        $this->db->join('perpus_jenis_buku', 'JENIS_BUKU=ID_PJB');
        $this->db->join('md_santri', 'SANTRI_PINJAM=ID_SANTRI');
        $this->db->join('md_kamar', 'ID_KAMAR=KAMAR_SANTRI', 'LEFT');
        $this->db->join('md_gedung', 'GEDUNG_KAMAR=ID_GEDUNG', 'LEFT');
        $this->db->order_by('NAMA_SANTRI', 'ASC');
        $this->db->where('KEMBALI_PINJAM <> ', NULL);
    }

    public function get_datatable($post) {
        $exl = array(
            'KAMAR_SANTRI',
        );
        $this->db->select($this->database_handler->set_select($post, $exl) . ', IF(ID_KAMAR IS NULL, "-", CONCAT(NAMA_KAMAR, " - ", NAMA_GEDUNG)) AS KAMAR_SANTRI');
        $this->_get_table();
        $data = $this->db->get()->result();

        $result = array(
            "data" => $data
        );

        return $result;
    }

}

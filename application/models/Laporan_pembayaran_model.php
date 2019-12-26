<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Laporan_pembayaran_model extends CI_Model {

    var $table = 'keu_pembayaran';
    var $primaryKey = 'ID_BAYAR';

    public function __construct() {
        parent::__construct();
    }

    private function _get_table() {
        $this->db->from($this->table);
        $this->db->join('keu_tagihan', 'TAGIHAN_BAYAR=ID_TAGIHAN');
        $this->db->join('md_santri', 'SANTRI_BAYAR=ID_SANTRI');
        $this->db->join('akad_santri', 'SANTRI_AS=ID_SANTRI AND TA_AS=TA_BAYAR AND KELAS_TAGIHAN=KELAS_AS');
        $this->db->join('md_rombel', 'ID_ROMBEL=ROMBEL_AS', 'LEFT');
        $this->db->join('md_kelas', 'KELAS_ROMBEL=ID_KELAS', 'LEFT');
        $this->db->join('md_kegiatan', 'ID_KEGIATAN=KEGIATAN_KELAS', 'LEFT');
        $this->db->join('md_jenis_kelamin', 'ID_JK=JK_SANTRI');
        $this->db->join('md_kamar', 'ID_KAMAR=KAMAR_SANTRI', 'LEFT');
        $this->db->join('md_gedung', 'GEDUNG_KAMAR=ID_GEDUNG', 'LEFT');
        $this->db->join('md_user', 'USER_BAYAR=ID_USER');
        $this->db->join('md_ustadz', 'USTADZ_USER=ID_UST');
        $this->db->where(array(
            'DIKEMBALIKAN_BAYAR' => 0,
            'ALUMNI_SANTRI' => 0,
            'AKTIF_SANTRI' => 1,
            'STATUS_MUTASI_SANTRI' => NULL,
            'TA_AS' => $this->session->userdata("ID_TA"),
        ));
    }

    public function get_datatable_persantri($post) {
        $exl = array(
            'ROMBEL_SANTRI',
            'NOMIMAL_TAGIHAN_SHOW',
        );
        $this->db->select($this->database_handler->set_select($post, $exl).', IF(ID_KAMAR IS NULL, "-", CONCAT(NAMA_KAMAR, " - ", NAMA_GEDUNG)) AS KAMAR_SANTRI, CONCAT(TEMPAT_LAHIR_SANTRI, ", ", DATE_FORMAT(TANGGAL_LAHIR_SANTRI, "%d-%m-%Y")) AS TTL_SANTRI, IF(ROMBEL_AS IS NULL, "-", CONCAT(NAMA_ROMBEL, " - ", NAMA_KEGIATAN)) AS ROMBEL_SANTRI, CONCAT("Rp ", FORMAT(NOMINAL_TAGIHAN, 2)) AS NOMIMAL_TAGIHAN_SHOW');
        $this->_get_table();
        $data = $this->db->get()->result();
        
        $result = array(
            "data" => $data
        );

        return $result;
    }

    public function delete($id) {
        $where = array($this->primaryKey => $id);
        $this->db->delete($this->table, $where);
        
        return $this->db->affected_rows();
    }
}

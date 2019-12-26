<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Laporan_tunggakan_model extends CI_Model {

    var $table = 'md_santri';
    var $primaryKey = 'ID_BAYAR';

    public function __construct() {
        parent::__construct();
    }

    private function _get_table() {
        $this->db->from($this->table);
        $this->db->join('akad_santri', 'SANTRI_AS=ID_SANTRI');
        $this->db->join('keu_tagihan', 'TA_TAGIHAN=TA_AS AND KELAS_TAGIHAN=KELAS_AS');
        $this->db->join('md_rombel', 'ID_ROMBEL=ROMBEL_AS', 'LEFT');
        $this->db->join('md_kelas', 'KELAS_ROMBEL=ID_KELAS', 'LEFT');
        $this->db->join('md_kegiatan', 'ID_KEGIATAN=KEGIATAN_KELAS', 'LEFT');
        $this->db->join('md_jenis_kelamin', 'ID_JK=JK_SANTRI');
        $this->db->join('md_kamar', 'ID_KAMAR=KAMAR_SANTRI', 'LEFT');
        $this->db->join('md_gedung', 'GEDUNG_KAMAR=ID_GEDUNG', 'LEFT');
        $this->db->join('keu_pengecualian', 'TA_KPC=TA_AS AND TAGIHAN_KPC=ID_TAGIHAN AND SANTRI_KPC=SANTRI_AS', 'LEFT');
        $this->db->join('keu_pembayaran', 'TA_BAYAR=TA_AS AND SANTRI_BAYAR=SANTRI_AS AND TAGIHAN_BAYAR=ID_TAGIHAN', 'LEFT');

        $this->db->where(array(
            'ALUMNI_SANTRI' => 0,
            'AKTIF_SANTRI' => 1,
            'STATUS_MUTASI_SANTRI' => NULL,
            'SANTRI_KPC' => NULL,
            'KELAS_AS <> ' => NULL,
            'TA_AS' => $this->session->userdata("ID_TA"),
            'ID_BAYAR' => NULL,
        ));
    }

    public function get_datatable($post) {
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
}

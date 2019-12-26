<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Peminjaman_model extends CI_Model {

    var $table = 'perpus_buku';
    var $primaryKey = 'ID_AS';

    public function __construct() {
        parent::__construct();
    }

    public function get_buku() {
        $this->db->select('ID_BUKU as id, CONCAT("KODE: ",KODE_BUKU, " | ", "NAMA: ", NAMA_BUKU, " | ", "PENGARANG: ",PENGARANG_BUKU) as title');
        $this->db->from($this->table);
        $this->db->join('perpus_jenis_buku', 'JENIS_BUKU=ID_PJB');
        $this->db->join('(SELECT pb.* FROM (SELECT *, COUNT(BUKU_PINJAM) AS JUMLAH_BUKU_PINJAM FROM perpus_santri GROUP BY BUKU_PINJAM) pb LEFT JOIN perpus_buku ON BUKU_PINJAM=ID_BUKU) aq', 'ID_BUKU=BUKU_PINJAM', 'LEFT');
        $this->db->where('(ID_PINJAM IS NULL OR JUMLAH_BUKU_PINJAM < STOK_BUKU)');
        $result = $this->db->get();

        return $result->result();
    }

    public function proses_peminjaman($data) {
        foreach ($data['DATA_PINJAMAN'] as $id_buku) {
            $data_save = array(
                'TA_PINJAM' => $this->session->userdata('ID_TA'),
                'SANTRI_PINJAM' => $data['ID_SANTRI'],
                'BUKU_PINJAM' => $id_buku,
                'TANGGAL_PINJAM' => $this->datetime_handler->date_to_store(),
                'USER_PINJAM' => $this->session->userdata('ID_USER')
            );
            
            $result = $this->db->insert('perpus_santri', $data_save);
        }
        
        return $result;
    }

}

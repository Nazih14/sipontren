<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Pengembalian_model extends CI_Model {

    var $table = 'perpus_santri';
    var $primaryKey = 'ID_PINJAM';

    public function __construct() {
        parent::__construct();
    }

    public function get_buku() {
        $this->db->select('ID_PINJAM as id, CONCAT("KODE: ",KODE_BUKU, " | ", "NAMA: ", NAMA_BUKU, " | ", "PENGARANG: ",PENGARANG_BUKU, " | ", "PEMINJAM: ", NAMA_SANTRI) as title');
        $this->db->from($this->table);
        $this->db->join('perpus_buku', 'BUKU_PINJAM = ID_BUKU');
        $this->db->join('md_santri', 'SANTRI_PINJAM = ID_SANTRI');
        $this->db->where('KEMBALI_PINJAM', NULL);
        $result = $this->db->get();

        return $result->result();
    }

    public function proses_pengembalian($data) {
        $result = $this->db->update($this->table, array('KEMBALI_PINJAM' => $this->datetime_handler->date_to_store()), $data);

        return $result;
    }

}

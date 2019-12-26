<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Peminjaman extends CI_Controller {

    var $primaryKey = 'ID_SANTRI';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'peminjaman_model' => 'peminjaman',
            'data_santri_model' => 'data_santri',
            'data_buku_model' => 'data_buku',
            'jenis_buku_model' => 'jenis_buku',
        ));
        $this->auth->validation(array(1, 6));
    }

    public function index() {
        $data = array(
            'title' => 'Peminjaman Buku',
            'breadcrumb' => 'Perpustakaan > Peminjaman Buku',
            'santri' => $this->data_santri->get_all(),
            'buku' => $this->peminjaman->get_buku(),
            'table' => array(
                array(
                    'field' => "ID_BUKU",
                    'title' => "ID",
                    'sortable' => "ID_BUKU",
                    'show' => FALSE,
                    'filter' => array(
                        'ID_BUKU' => 'number'
                    )
                ),
                array(
                    'field' => "NAMA_PJB",
                    'title' => "Jenis Buku",
                    'sortable' => "NAMA_PJB",
                    'show' => true,
                    'filter' => array(
                        'ID_PJB' => 'select'
                    ),
                    'filterData' => $this->jenis_buku->get_all()
                ),
                array(
                    'field' => "KODE_BUKU",
                    'title' => "Kode Buku",
                    'sortable' => "KODE_BUKU",
                    'show' => true,
                    'filter' => array(
                        'KODE_BUKU' => 'text'
                    )
                ),
                array(
                    'field' => "NAMA_BUKU",
                    'title' => "Nama Buku",
                    'sortable' => "NAMA_BUKU",
                    'show' => true,
                    'filter' => array(
                        'NAMA_BUKU' => 'text'
                    )
                ),
                array(
                    'field' => "PENGARANG_BUKU",
                    'title' => "Pengarang",
                    'sortable' => "PENGARANG_BUKU",
                    'show' => true,
                    'filter' => array(
                        'PENGARANG_BUKU' => 'text'
                    )
                ),
                array(
                    'field' => "PENERBIT_BUKU",
                    'title' => "Penerbit",
                    'sortable' => "PENERBIT_BUKU",
                    'show' => true,
                    'filter' => array(
                        'PENERBIT_BUKU' => 'text'
                    )
                ),
                array(
                    'field' => "ACTION",
                    'title' => "Aksi",
                ),
            )
        );
        $this->output_handler->output_JSON($data);
    }
    
    public function get_buku() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->data_buku->get_by_id($post['ID_BUKU']);

        $this->output_handler->output_JSON($data);
    }

    public function proses_peminjaman() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->peminjaman->proses_peminjaman($data);
        $message = 'diproses';

        $this->output_handler->output_JSON($result, $message);
    }

}

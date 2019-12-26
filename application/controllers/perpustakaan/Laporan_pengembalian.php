<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_pengembalian extends CI_Controller {

    var $primaryKey = 'ID_BUKU';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'laporan_pengembalian_model' => 'laporan_pengembalian',
            'jenis_buku_model' => 'jenis',
        ));
        $this->auth->validation(array(1, 6));
    }

    public function index() {
        $data = array(
            'title' => 'Laporan Pengambalian',
            'breadcrumb' => 'Perpustakaan > Laporan -> Pengambalian',
            'wide' => true,
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
                    'field' => "ID_PJB",
                    'title' => "ID",
                    'sortable' => "ID_PJB",
                    'show' => FALSE,
                    'filter' => array(
                        'ID_PJB' => 'number'
                    )
                ),
                array(
                    'field' => "NIS_SANTRI",
                    'title' => "NIS",
                    'sortable' => "NIS_SANTRI",
                    'show' => TRUE,
                    'filter' => array(
                        'NIS_SANTRI' => 'text'
                    )
                ),
                array(
                    'field' => "NAMA_SANTRI",
                    'title' => "Nama Santri",
                    'sortable' => "NAMA_SANTRI",
                    'show' => TRUE,
                    'filter' => array(
                        'NAMA_SANTRI' => 'text'
                    )
                ),
                array(
                    'field' => "KAMAR_SANTRI",
                    'title' => "Kamar",
                    'sortable' => "KAMAR_SANTRI",
                    'show' => TRUE,
                    'filter' => array(
                        'KAMAR_SANTRI' => 'text'
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
                    'filterData' => $this->jenis->get_all()
                ),
                array(
                    'field' => "KEMBALI_PINJAM",
                    'title' => "Tanggal",
                    'sortable' => "KEMBALI_PINJAM",
                    'show' => true,
                    'filter' => array(
                        'KEMBALI_PINJAM' => 'text'
                    ),
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
            )
        );
        $this->output_handler->output_JSON($data);
    }

    public function datatable() {
        $post = json_decode(file_get_contents('php://input'), true);
        
        $data = $this->laporan_pengembalian->get_datatable($post);

        $this->output_handler->output_JSON($data);
    }
}

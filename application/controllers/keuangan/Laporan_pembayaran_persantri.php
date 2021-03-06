<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_pembayaran_persantri extends CI_Controller {

    var $primaryKey = 'ID_BAYAR';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'laporan_pembayaran_model' => 'laporan_pembayaran',
            'jk_model' => 'jk',
            'kamar_model' => 'kamar',
            'kelas_model' => 'kelas',
            'rombel_model' => 'rombel',
            'tagihan_model' => 'tagihan',
        ));
        $this->auth->validation(array(1, 5));
    }

    public function index() {
        $data = array(
            'title' => 'Laporan Pembayaran Santri',
            'breadcrumb' => 'Keuangan > Laporan > Laporan Pembayaran Santri',
            'wide' => true,
            'table' => array(
                array(
                    'field' => "ID_BAYAR",
                    'title' => "ID",
                    'sortable' => "ID_BAYAR",
                    'show' => FALSE,
                    'filter' => array(
                        'ID_BAYAR' => 'number'
                    ),
                ),
                array(
                    'field' => "CREATED_BAYAR",
                    'title' => "TANGGAL",
                    'sortable' => "CREATED_BAYAR",
                    'show' => true,
                    'filter' => array(
                        'CREATED_BAYAR' => 'text'
                    ),
                ),
                array(
                    'field' => "NIS_SANTRI",
                    'title' => "NIS",
                    'sortable' => "NIS_SANTRI",
                    'show' => true,
                    'filter' => array(
                        'NIS_SANTRI' => 'text'
                    ),
                ),
                array(
                    'field' => "NAMA_SANTRI",
                    'title' => "Nama Santri",
                    'sortable' => "NAMA_SANTRI",
                    'show' => true,
                    'filter' => array(
                        'NAMA_SANTRI' => 'text'
                    )
                ),
                array(
                    'field' => "JK_SANTRI",
                    'title' => "JK",
                    'sortable' => "JK_SANTRI",
                    'show' => false,
                    'filter' => array(
                        'JK_SANTRI' => 'select'
                    ),
                    'filterData' => $this->jk->get_all()
                ),
                array(
                    'field' => "AYAH_NAMA_SANTRI",
                    'title' => "Nama Ayah",
                    'sortable' => "AYAH_NAMA_SANTRI",
                    'show' => false,
                    'filter' => array(
                        'AYAH_NAMA_SANTRI' => 'text'
                    )
                ),
                array(
                    'field' => "ROMBEL_SANTRI",
                    'title' => "Kelas",
                    'sortable' => "ROMBEL_SANTRI",
                    'show' => true,
                    'filter' => array(
                        'ROMBEL_AS' => 'select'
                    ),
                    'filterData' => $this->rombel->get_all()
                ),
                array(
                    'field' => "KAMAR_SANTRI",
                    'title' => "Kamar",
                    'sortable' => "KAMAR_SANTRI",
                    'show' => true,
                    'filter' => array(
                        'ID_KAMAR' => 'select'
                    ),
                    'filterData' => $this->kamar->get_all()
                ),
                array(
                    'field' => "NAMA_TAGIHAN",
                    'title' => "Tagihan",
                    'sortable' => "NAMA_TAGIHAN",
                    'show' => true,
                    'filter' => array(
                        'ID_TAGIHAN' => 'select'
                    ),
                    'filterData' => $this->tagihan->get_all()
                ),
                array(
                    'field' => "NOMIMAL_TAGIHAN_SHOW",
                    'title' => "Tagihan",
                    'sortable' => "NOMIMAL_TAGIHAN_SHOW",
                    'show' => true,
                    'filter' => array(
                        'NOMIMAL_TAGIHAN' => 'number'
                    ),
                ),
                array(
                    'field' => "NAMA_UST",
                    'title' => "Petugas",
                    'sortable' => "NAMA_UST",
                    'show' => true,
                    'filter' => array(
                        'NAMA_UST' => 'text'
                    ),
                ),
                array(
                    'field' => "ACTION",
                    'title' => "Aksi",
                    'actions' => array(
                        array(
                            'title' => 'Hapus',
                            'delete' => true
                        )
                    )
                ),
            )
        );
        $this->output_handler->output_JSON($data);
    }

    public function datatable() {
        $post = json_decode(file_get_contents('php://input'), true);
        
        $data = $this->laporan_pembayaran->get_datatable_persantri($post);

        $this->output_handler->output_JSON($data);
    }

    public function delete() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->laporan_pembayaran->delete($post[$this->primaryKey]);
        $message = 'dihapus';

        $this->output_handler->output_JSON($result, $message);
    }

}

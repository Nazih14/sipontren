<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Saldo extends CI_Controller {

    var $primaryKey = 'ID_SANTRI';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'saldo_model' => 'saldo',
            'rombel_model' => 'rombel',
            'jk_model' => 'jk',
            'kamar_model' => 'kamar',
            'tagihan_model' => 'tagihan',
            'ustadz_model' => 'ustadz',
        ));
        $this->auth->validation(array(1, 5));
    }

    public function index() {
        $data = array(
            'title' => 'Saldo Pembayaran Santri',
            'breadcrumb' => 'Keuangan > Laporan > Saldo Keuangan',
            'ustadz' => $this->ustadz->get_all(),
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
            )
        );
        $this->output_handler->output_JSON($data);
    }

    public function datatable() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->saldo->get_datatable($post);

        $this->output_handler->output_JSON($data);
    }

}

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Presensi extends CI_Controller {

    var $primaryKey = 'ID_SANTRI';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'presensi_model' => 'presensi',
            'rombel_model' => 'rombel',
            'jk_model' => 'jk',
            'kamar_model' => 'kamar',
        ));
        $this->auth->validation(array(1, 4));
    }

    public function index() {
        $data = array(
            'title' => 'Presensi Santri',
            'breadcrumb' => 'Akademik > Presensi Santri',
            'rombel' => $this->rombel->get_all(),
            'table' => array(
                array(
                    'field' => "ID_SANTRI",
                    'title' => "ID",
                    'sortable' => "ID_SANTRI",
                    'show' => FALSE,
                    'filter' => array(
                        'ID_SANTRI' => 'number'
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
                    'field' => "KAMAR_GEDUNG",
                    'title' => "Kamar",
                    'sortable' => "KAMAR_GEDUNG",
                    'show' => true,
                    'filter' => array(
                        'KAMAR_SANTRI' => 'select'
                    ),
                    'filterData' => $this->kamar->get_all()
                ),
                array(
                    'field' => "ACTION",
                    'title' => "Aksi",
                ),
            )
        );
        $this->output_handler->output_JSON($data);
    }

    public function datatable() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->presensi->get_datatable($post);

        $this->output_handler->output_JSON($data);
    }

    public function prosesPresensi() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->presensi->prosesPresensi($data);
        $message = 'diproses';

        $this->output_handler->output_JSON($result, $message);
    }

}

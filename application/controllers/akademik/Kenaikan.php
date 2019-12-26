<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Kenaikan extends CI_Controller {

    var $primaryKey = 'ID_SANTRI';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'kenaikan_model' => 'kenaikan',
            'rombel_model' => 'rombel',
            'kamar_model' => 'kamar',
            'tahun_ajaran_model' => 'ta',
        ));
        $this->auth->validation(array(1, 4));
    }

    public function index() {
        $data = array(
            'title' => 'Kenaikan Santri',
            'breadcrumb' => 'Akademik > Kenaikan',
            'rombel' => $this->rombel->get_all(),
            'ta' => $this->ta->get_next_ta(),
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
                    'field' => "ID_AS",
                    'title' => "ID",
                    'sortable' => "ID_AS",
                    'show' => FALSE,
                    'filter' => array(
                        'ID_AS' => 'number'
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

        $data = $this->kenaikan->get_datatable($post);

        $this->output_handler->output_JSON($data);
    }

    public function proses_kenaikan() {
        $data = json_decode(file_get_contents('php://input'), true);

        $rombel = $this->rombel->get_by_id($data['ROMBEL_AS']);
        $data['KELAS_AS'] = $rombel['ID_KELAS'];

        $result = $this->kenaikan->proses_kenaikan($data);
        $message = 'diproses';

        $this->output_handler->output_JSON($result, $message);
    }

}

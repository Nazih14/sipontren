<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Nilai extends CI_Controller {

    var $primaryKey = 'ID_SANTRI';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'nilai_model' => 'nilai',
            'rombel_model' => 'rombel',
            'jadwal_model' => 'jadwal',
            'kamar_model' => 'kamar',
        ));
        $this->auth->validation(array(1, 4));
    }

    public function index() {
        $data = array(
            'title' => 'Nilai Santri',
            'breadcrumb' => 'Santri > Nilai',
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

        $data = $this->nilai->get_datatable($post);

        $this->output_handler->output_JSON($data);
    }

    public function proses_nilai() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->nilai->proses_nilai($data);
        $message = 'diproses';

        $this->output_handler->output_JSON($result, $message);
    }

    public function get_data() {
        $post = json_decode(file_get_contents('php://input'), true);

        if ($post['MODEL'] == 'JADWAL')
            $data = $this->jadwal->get_all($post['PARAMS']);
        
        $this->output_handler->output_JSON($data);
    }

}

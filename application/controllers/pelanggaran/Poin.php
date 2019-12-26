<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Poin extends CI_Controller {

    var $primaryKey = 'ID_PSN';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'pelanggaran_poin_model' => 'poin',
            'kamar_model' => 'kamar',
        ));
        $this->auth->validation(array(1, 7));
    }

    public function index() {
        $data = array(
            'title' => 'Poin Pelanggaran Siswa',
            'breadcrumb' => 'Pelanggaran > Poin',
            'wide' => true,
            'table' => array(
                array(
                    'field' => "ID_PSN",
                    'title' => "ID",
                    'sortable' => "ID_PSN",
                    'show' => FALSE,
                    'filter' => array(
                        'ID_PSN' => 'number'
                    )
                ),
                array(
                    'field' => "NIS_SANTRI",
                    'title' => "NIS",
                    'sortable' => "NIS_SANTRI",
                    'show' => true,
                    'filter' => array(
                        'NIS_SANTRI' => 'text'
                    )
                ),
                array(
                    'field' => "NAMA_SANTRI",
                    'title' => "Nama",
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
                        'ID_KAMAR' => 'select'
                    ),
                    'filterData' => $this->kamar->get_all()
                ),
                array(
                    'field' => "JUMLAH_POIN",
                    'title' => "Jumlah Poin",
                    'sortable' => "JUMLAH_POIN",
                    'show' => true,
                    'filter' => array(
                        'JUMLAH_POIN' => 'number'
                    )
                ),
            )
        );
        $this->output_handler->output_JSON($data);
    }

    public function datatable() {
        $post = json_decode(file_get_contents('php://input'), true);
        
        $data = $this->poin->get_datatable($post);

        $this->output_handler->output_JSON($data);
    }
}

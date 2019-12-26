<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Data extends CI_Controller {
    
    var $primaryKey = 'ID_SANTRI';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'data_akademik_model' => 'data_akademik',
            'jk_model' => 'jk',
            'kamar_model' => 'kamar',
            'kelas_model' => 'kelas',
            'rombel_model' => 'rombel',
        ));
        $this->auth->validation(array(1, 4));
    }

    public function index() {
        $data = array(
            'title' => 'Data Santri',
            'breadcrumb' => 'Santri > Data',
            'wide' => true,
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
                    'field' => "NAMA_JK",
                    'title' => "JK", 
                    'sortable' => "NAMA_JK", 
                    'show' => true,
                    'filter' => array(
                        'JK_SANTRI' => 'select'
                    ),
                    'filterData' => $this->jk->get_all()
                ),
                array(
                    'field' => "ANGKATAN_SANTRI",
                    'title' => "Angkatan", 
                    'sortable' => "ANGKATAN_SANTRI", 
                    'show' => true,
                    'filter' => array(
                        'ANGKATAN_SANTRI' => 'text'
                    )
                ),
                array(
                    'field' => "TTL_SANTRI",
                    'title' => "TTL", 
                    'sortable' => "TTL_SANTRI", 
                    'show' => true,
                    'filter' => array(
                        'TTL_SANTRI' => 'text'
                    )
                ),
                array(
                    'field' => "NOHP_SANTRI",
                    'title' => "No HP", 
                    'sortable' => "NOHP_SANTRI", 
                    'show' => true,
                    'filter' => array(
                        'NOHP_SANTRI' => 'text'
                    )
                ),
                array(
                    'field' => "AYAH_NAMA_SANTRI",
                    'title' => "Nama Ayah", 
                    'sortable' => "AYAH_NAMA_SANTRI", 
                    'show' => true,
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
            )
        );
        $this->output_handler->output_JSON($data);
    }
    
    public function datatable() {
        $data = $this->data_akademik->get_datatable();

        $this->output_handler->output_JSON($data);
    }

}

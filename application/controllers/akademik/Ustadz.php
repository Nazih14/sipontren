<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ustadz extends CI_Controller {
    
    var $primaryKey = 'ID_UST';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'ustadz_model' => 'data_ustadz',
            'jk_model' => 'jk',
            'rombel_model' => 'rombel',
        ));
        $this->auth->validation(array(1, 4));
    }

    public function index() {
        $data = array(
            'title' => 'Data Ustadz',
            'breadcrumb' => 'Akademik > Ustadz',
            'wide' => true,
            'table' => array(
                array(
                    'field' => "ID_UST",
                    'title' => "ID", 
                    'sortable' => "ID_UST", 
                    'show' => FALSE,
                    'filter' => array(
                        'ID_UST' => 'number'
                    ),
                ),
                array(
                    'field' => "NIP_UST",
                    'title' => "NIP", 
                    'sortable' => "NIP_UST", 
                    'show' => true,
                    'filter' => array(
                        'NIP_UST' => 'text'
                    )
                ),
                array(
                    'field' => "NAMA_UST",
                    'title' => "Nama", 
                    'sortable' => "NAMA_UST", 
                    'show' => true,
                    'filter' => array(
                        'NAMA_UST' => 'text'
                    )
                ),
                array(
                    'field' => "NAMA_JK",
                    'title' => "JK", 
                    'sortable' => "NAMA_JK", 
                    'show' => true,
                    'filter' => array(
                        'JK_UST' => 'text'
                    ),
                    'filterData' => $this->jk->get_all()
                ),
                array(
                    'field' => "TANGGAL_LAHIR_UST_SHOW",
                    'title' => "Tempat Lahir", 
                    'sortable' => "TANGGAL_LAHIR_UST_SHOW", 
                    'show' => true,
                    'filter' => array(
                        'TANGGAL_LAHIR_UST_SHOW' => 'text'
                    )
                ),
                array(
                    'field' => "TANGGAL_LAHIR_UST_SHOW",
                    'title' => "Tanggal Lahir", 
                    'sortable' => "TANGGAL_LAHIR_UST_SHOW", 
                    'show' => true,
                    'filter' => array(
                        'TANGGAL_LAHIR_UST_SHOW' => 'text'
                    )
                ),
                array(
                    'field' => "ALAMAT_LENGKAP_UST",
                    'title' => "Alamat", 
                    'sortable' => "ALAMAT_LENGKAP_UST", 
                    'show' => true,
                    'filter' => array(
                        'ALAMAT_LENGKAP_UST' => 'text'
                    )
                ),
                array(
                    'field' => "NOHP_UST",
                    'title' => "No HP", 
                    'sortable' => "NOHP_UST", 
                    'show' => true,
                    'filter' => array(
                        'NOHP_UST' => 'text'
                    )
                ),
                array(
                    'field' => "ROMBEL_KEGIATAN",
                    'title' => "Rombel", 
                    'sortable' => "ROMBEL_KEGIATAN", 
                    'show' => true,
                    'filter' => array(
                        'ROMBEL_KEGIATAN' => 'text'
                    )
                ),
                array(
                    'field' => "ACTION",
                    'title' => "Aksi", 
                    'actions' => array(
                        array(
                            'title' => 'Ubah',
                            'update' => true
                        ),
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
        $data = $this->data_ustadz->get_datatable();

        $this->output_handler->output_JSON($data);
    }
    
    public function form() {
        $data = array(
            'uri' => array(
                'kecamatan' => site_url('master_data/kecamatan/get_all'),
            ),
            'jk' => $this->jk->get_all(),
        );
        
        $this->output_handler->output_JSON($data);
    }

    public function data() {
        $data = $this->data_ustadz->get_datatables();

        $this->output_handler->output_JSON($data);
    }

    public function view() {
        $post = json_decode(file_get_contents('php://input'), true);
        
        $data = $this->data_ustadz->get_data_form($post[$this->primaryKey]);

        $this->output_handler->output_JSON($data);
    }

    public function save() {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $result = $this->data_ustadz->save($data);

        if (isset($data[$this->primaryKey]))
            $message = 'diubah';
        else
            $message = 'dibuat';

        $this->output_handler->output_JSON($result, $message);
    }

    public function delete() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->data_ustadz->delete($post[$this->primaryKey]);
        $message = 'dihapus';

        $this->output_handler->output_JSON($result, $message);
    }
    
    public function get_rombel() {
        $post = json_decode(file_get_contents('php://input'), true);
        
        $data = $this->data_ustadz->get_rombel($post[$this->primaryKey]);

        $this->output_handler->output_JSON($data);
    }

}

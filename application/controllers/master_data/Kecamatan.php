<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Kecamatan extends CI_Controller {
    
    var $primaryKey = 'ID_KEC';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'kecamatan_model' => 'kecamatan'
        ));
        $this->auth->validation(array(1, 2, 3));
    }

    public function index() {
        $data = array(
            'title' => 'Master Data Kecamatan',
            'breadcrumb' => 'Master Data > Kecamatan',
            'table' => array(
                array(
                    'field' => "ID_KEC",
                    'title' => "ID", 
                    'sortable' => "ID_KEC", 
                    'show' => false,
                    'filter' => array(
                        'ID_KEC' => 'number'
                    )
                ),
                array(
                    'field' => "NAMA_KEC",
                    'title' => "Nama Kecamatan", 
                    'sortable' => "NAMA_KEC", 
                    'show' => true,
                    'filter' => array(
                        'NAMA_KEC' => 'text'
                    )
                ),
                array(
                    'field' => "NAMA_KAB",
                    'title' => "Nama Kabupaten", 
                    'sortable' => "NAMA_KAB", 
                    'show' => true,
                    'filter' => array(
                        'NAMA_KAB' => 'text'
                    )
                ),
                array(
                    'field' => "NAMA_PROV",
                    'title' => "Nama Provinsi", 
                    'sortable' => "NAMA_PROV", 
                    'show' => true,
                    'filter' => array(
                        'NAMA_PROV' => 'text'
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
        $data = $this->kecamatan->get_datatable();

        $this->output_handler->output_JSON($data);
    }
    
    public function form() {
        $data = array(
            'uri' => array(
                'kabupaten' => site_url('master_data/kabupaten/get_all')
            )
        );
        
        $this->output_handler->output_JSON($data);
    }

    public function data() {
        $data = $this->kecamatan->get_datatables();

        $this->output_handler->output_JSON($data);
    }

    public function view() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->kecamatan->get_by_id($post[$this->primaryKey]);

        $this->output_handler->output_JSON($data);
    }

    public function save() {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $result = $this->kecamatan->save($data);

        if (isset($data[$this->primaryKey]))
            $message = 'diubah';
        else
            $message = 'dibuat';

        $this->output_handler->output_JSON($result, $message);
    }

    public function delete() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->kecamatan->delete($post[$this->primaryKey]);
        $message = 'dihapus';

        $this->output_handler->output_JSON($result, $message);
    }

    public function get_all() {
        $data = $this->kecamatan->get_all();

        $this->output_handler->output_JSON($data);
    }

}

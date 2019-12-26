<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Kabupaten extends CI_Controller {
    
    var $primaryKey = 'ID_KAB';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'kabupaten_model' => 'kabupaten'
        ));
        $this->auth->validation(1);
    }

    public function index() {
        $data = array(
            'title' => 'Master Data Kabupaten',
            'breadcrumb' => 'Master Data > Kabupaten',
            'table' => array(
                array(
                    'field' => "ID_KAB",
                    'title' => "ID", 
                    'sortable' => "ID_KAB", 
                    'show' => false,
                    'filter' => array(
                        'ID_KAB' => 'number'
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
        $data = $this->kabupaten->get_datatable();

        $this->output_handler->output_JSON($data);
    }
    
    public function form() {
        $data = array(
            'uri' => array(
                'provinsi' => site_url('master_data/provinsi/get_all')
            )
        );
        
        $this->output_handler->output_JSON($data);
    }

    public function data() {
        $data = $this->kabupaten->get_datatables();

        $this->output_handler->output_JSON($data);
    }

    public function view() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->kabupaten->get_by_id($post[$this->primaryKey]);

        $this->output_handler->output_JSON($data);
    }

    public function save() {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $result = $this->kabupaten->save($data);

        if (isset($data[$this->primaryKey]))
            $message = 'diubah';
        else
            $message = 'dibuat';

        $this->output_handler->output_JSON($result, $message);
    }

    public function delete() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->kabupaten->delete($post[$this->primaryKey]);
        $message = 'dihapus';

        $this->output_handler->output_JSON($result, $message);
    }

    public function get_all() {
        $data = $this->kabupaten->get_all();

        $this->output_handler->output_JSON($data);
    }

}

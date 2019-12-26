<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Penghasilan extends CI_Controller {

    var $primaryKey = 'ID_HASIL';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'penghasilan_model' => 'penghasilan'
        ));
        $this->auth->validation(1);
    }

    public function index() {
        $data = array(
            'title' => 'Master Data Penghasilan',
            'breadcrumb' => 'Master Data > Penghasilan',
            'table' => array(
                array(
                    'field' => "ID_HASIL",
                    'title' => "ID",
                    'sortable' => "ID_HASIL",
                    'show' => FALSE,
                    'filter' => array(
                        'ID_HASIL' => 'number'
                    )
                ),
                array(
                    'field' => "NAMA_HASIL",
                    'title' => "Nama Penghasilan",
                    'sortable' => "NAMA_HASIL",
                    'show' => true,
                    'filter' => array(
                        'NAMA_HASIL' => 'text'
                    )
                ),
                array(
                    'field' => "KODE_EMIS_HASIL",
                    'title' => "Kode EMIS",
                    'sortable' => "KODE_EMIS_HASIL",
                    'show' => true,
                    'filter' => array(
                        'KODE_EMIS_HASIL' => 'text'
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
        $data = $this->penghasilan->get_datatable();

        $this->output_handler->output_JSON($data);
    }

    public function form() {
        $data = array(
            
        );

        $this->output_handler->output_JSON($data);
    }

    public function data() {
        $data = $this->penghasilan->get_datatables();

        $this->output_handler->output_JSON($data);
    }

    public function view() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->penghasilan->get_by_id($post[$this->primaryKey]);

        $this->output_handler->output_JSON($data);
    }

    public function save() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->penghasilan->save($data);

        if (isset($data[$this->primaryKey]))
            $message = 'diubah';
        else
            $message = 'dibuat';

        $this->output_handler->output_JSON($result, $message);
    }

    public function delete() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->penghasilan->delete($post[$this->primaryKey]);
        $message = 'dihapus';

        $this->output_handler->output_JSON($result, $message);
    }

    public function get_all() {
        $data = $this->penghasilan->get_all();

        $this->output_handler->output_JSON($data);
    }

}

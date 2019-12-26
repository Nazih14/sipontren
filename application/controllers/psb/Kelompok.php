<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Kelompok extends CI_Controller {

    var $primaryKey = 'ID_PKK';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'kelompok_model' => 'kelompok'
        ));
        $this->auth->validation(array(1, 2));
    }

    public function index() {
        $data = array(
            'title' => 'Master Data Kelompok',
            'breadcrumb' => 'Master Data > Kelompok',
            'table' => array(
                array(
                    'field' => "ID_PKK",
                    'title' => "ID",
                    'sortable' => "ID_PKK",
                    'show' => FALSE,
                    'filter' => array(
                        'ID_PKK' => 'number'
                    )
                ),
                array(
                    'field' => "NAMA_PKK",
                    'title' => "Nama Kelompok",
                    'sortable' => "NAMA_PKK",
                    'show' => true,
                    'filter' => array(
                        'NAMA_PKK' => 'text'
                    )
                ),
                array(
                    'field' => "KETERANGAN_PKK",
                    'title' => "Keterangan",
                    'sortable' => "KETERANGAN_PKK",
                    'show' => true,
                    'filter' => array(
                        'KETERANGAN_PKK' => 'text'
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
        $data = $this->kelompok->get_datatable();

        $this->output_handler->output_JSON($data);
    }

    public function form() {
        $data = array(
            
        );

        $this->output_handler->output_JSON($data);
    }

    public function data() {
        $data = $this->kelompok->get_datatables();

        $this->output_handler->output_JSON($data);
    }

    public function view() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->kelompok->get_by_id($post[$this->primaryKey]);

        $this->output_handler->output_JSON($data);
    }

    public function save() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->kelompok->save($data);

        if (isset($data[$this->primaryKey]))
            $message = 'diubah';
        else
            $message = 'dibuat';

        $this->output_handler->output_JSON($result, $message);
    }

    public function delete() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->kelompok->delete($post[$this->primaryKey]);
        $message = 'dihapus';

        $this->output_handler->output_JSON($result, $message);
    }

    public function get_all() {
        $data = $this->kelompok->get_all();

        $this->output_handler->output_JSON($data);
    }

}

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Jenis_buku extends CI_Controller {

    var $primaryKey = 'ID_PJB';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'jenis_buku_model' => 'jenis_buku'
        ));
        $this->auth->validation(array(1, 6));
    }

    public function index() {
        $data = array(
            'title' => 'Jenis Buku',
            'breadcrumb' => 'Perpustakaan > Jenis Buku',
            'table' => array(
                array(
                    'field' => "ID_PJB",
                    'title' => "ID",
                    'sortable' => "ID_PJB",
                    'show' => FALSE,
                    'filter' => array(
                        'ID_PJB' => 'number'
                    )
                ),
                array(
                    'field' => "NAMA_PJB",
                    'title' => "Nama Jenis Buku",
                    'sortable' => "NAMA_PJB",
                    'show' => true,
                    'filter' => array(
                        'NAMA_PJB' => 'text'
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
        $data = $this->jenis_buku->get_datatable();

        $this->output_handler->output_JSON($data);
    }

    public function form() {
        $data = array(
            
        );

        $this->output_handler->output_JSON($data);
    }

    public function data() {
        $data = $this->jenis_buku->get_datatables();

        $this->output_handler->output_JSON($data);
    }

    public function view() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->jenis_buku->get_by_id($post[$this->primaryKey]);

        $this->output_handler->output_JSON($data);
    }

    public function save() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->jenis_buku->save($data);

        if (isset($data[$this->primaryKey]))
            $message = 'diubah';
        else
            $message = 'dibuat';

        $this->output_handler->output_JSON($result, $message);
    }

    public function delete() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->jenis_buku->delete($post[$this->primaryKey]);
        $message = 'dihapus';

        $this->output_handler->output_JSON($result, $message);
    }

    public function get_all() {
        $data = $this->jenis_buku->get_all();

        $this->output_handler->output_JSON($data);
    }

}

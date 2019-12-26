<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Jenis extends CI_Controller {

    var $primaryKey = 'ID_PJS';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'pelanggaran_jenis_model' => 'jenis',
        ));
        $this->auth->validation(array(1, 7));
    }

    public function index() {
        $data = array(
            'title' => 'Jenis Pelanggaran',
            'breadcrumb' => 'Pelanggaran > Jenis',
            'table' => array(
                array(
                    'field' => "ID_PJS",
                    'title' => "ID",
                    'sortable' => "ID_PJS",
                    'show' => FALSE,
                    'filter' => array(
                        'ID_PJS' => 'number'
                    )
                ),
                array(
                    'field' => "NAMA_PJS",
                    'title' => "Nama",
                    'sortable' => "NAMA_PJS",
                    'show' => true,
                    'filter' => array(
                        'NAMA_PJS' => 'text'
                    )
                ),
                array(
                    'field' => "POIN_PJS",
                    'title' => "Poin",
                    'sortable' => "POIN_PJS",
                    'show' => true,
                    'filter' => array(
                        'POIN_PJS' => 'number'
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
        $post = json_decode(file_get_contents('php://input'), true);
        
        $data = $this->jenis->get_datatable($post);

        $this->output_handler->output_JSON($data);
    }

    public function form() {
        $data = array(

        );

        $this->output_handler->output_JSON($data);
    }

    public function data() {
        $data = $this->jenis->get_datatables();

        $this->output_handler->output_JSON($data);
    }

    public function view() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->jenis->get_form_data($post[$this->primaryKey]);

        $this->output_handler->output_JSON($data);
    }

    public function save() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->jenis->save($data);

        if (isset($data[$this->primaryKey]))
            $message = 'diubah';
        else
            $message = 'dibuat';

        $this->output_handler->output_JSON($result, $message);
    }

    public function delete() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->jenis->delete($post[$this->primaryKey]);
        $message = 'dihapus';

        $this->output_handler->output_JSON($result, $message);
    }

    public function get_all() {
        $data = $this->jenis->get_all();

        $this->output_handler->output_JSON($data);
    }

}

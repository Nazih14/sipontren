<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Kamar extends CI_Controller {

    var $primaryKey = 'ID_KAMAR';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'kamar_model' => 'kamar',
            'gedung_model' => 'gedung',
        ));
        $this->auth->validation(array(1, 3));
    }

    public function index() {
        $data = array(
            'title' => 'Data Kamar',
            'breadcrumb' => 'Santri > Kamar',
            'table' => array(
                array(
                    'field' => "ID_KAMAR",
                    'title' => "ID",
                    'sortable' => "ID_KAMAR",
                    'show' => FALSE,
                    'filter' => array(
                        'ID_KAMAR' => 'number'
                    )
                ),
                array(
                    'field' => "NAMA_GEDUNG",
                    'title' => "Nama Gedung",
                    'sortable' => "NAMA_GEDUNG",
                    'show' => true,
                    'filter' => array(
                        'ID_GEDUNG' => 'select'
                    ),
                    'filterData' => $this->gedung->get_all()
                ),
                array(
                    'field' => "NAMA_KAMAR",
                    'title' => "Nama Kamar",
                    'sortable' => "NAMA_KAMAR",
                    'show' => true,
                    'filter' => array(
                        'NAMA_KAMAR' => 'text'
                    )
                ),
                array(
                    'field' => "KETERANGAN_KAMAR",
                    'title' => "Keterangan",
                    'sortable' => "KETERANGAN_KAMAR",
                    'show' => true,
                    'filter' => array(
                        'KETERANGAN_KAMAR' => 'text'
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
        $data = $this->kamar->get_datatable();

        $this->output_handler->output_JSON($data);
    }

    public function form() {
        $data = array(
            'dataGEDUNG_KAMAR' => $this->gedung->get_all()
        );

        $this->output_handler->output_JSON($data);
    }

    public function data() {
        $data = $this->kamar->get_datatables();

        $this->output_handler->output_JSON($data);
    }

    public function view() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->kamar->get_by_id($post[$this->primaryKey]);

        $this->output_handler->output_JSON($data);
    }

    public function save() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->kamar->save($data);

        if (isset($data[$this->primaryKey]))
            $message = 'diubah';
        else
            $message = 'dibuat';

        $this->output_handler->output_JSON($result, $message);
    }

    public function delete() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->kamar->delete($post[$this->primaryKey]);
        $message = 'dihapus';

        $this->output_handler->output_JSON($result, $message);
    }

    public function get_all() {
        $data = $this->kamar->get_all();

        $this->output_handler->output_JSON($data);
    }

}

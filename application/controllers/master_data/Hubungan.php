<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Hubungan extends CI_Controller {

    var $primaryKey = 'ID_HUB';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'hubungan_model' => 'hubungan'
        ));
        $this->auth->validation(array(1, 2, 3));
    }

    public function index() {
        $data = array(
            'title' => 'Master Data Hubungan Wali dengan Santri',
            'breadcrumb' => 'Master Data > Hubungan',
            'table' => array(
                array(
                    'field' => "ID_HUB",
                    'title' => "ID",
                    'sortable' => "ID_HUB",
                    'show' => FALSE,
                    'filter' => array(
                        'ID_HUB' => 'number'
                    )
                ),
                array(
                    'field' => "NAMA_HUB",
                    'title' => "Nama ",
                    'sortable' => "NAMA_HUB",
                    'show' => true,
                    'filter' => array(
                        'NAMA_HUB' => 'text'
                    )
                ),
                array(
                    'field' => "KODE_EMIS_HUB",
                    'title' => "Kode EMIS",
                    'sortable' => "KODE_EMIS_HUB",
                    'show' => true,
                    'filter' => array(
                        'KODE_EMIS_HUB' => 'text'
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
        $data = $this->hubungan->get_datatable();

        $this->output_handler->output_JSON($data);
    }

    public function form() {
        $data = array(
            
        );

        $this->output_handler->output_JSON($data);
    }

    public function data() {
        $data = $this->hubungan->get_datatables();

        $this->output_handler->output_JSON($data);
    }

    public function view() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->hubungan->get_by_id($post[$this->primaryKey]);

        $this->output_handler->output_JSON($data);
    }

    public function save() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->hubungan->save($data);

        if (isset($data[$this->primaryKey]))
            $message = 'diubah';
        else
            $message = 'dibuat';

        $this->output_handler->output_JSON($result, $message);
    }

    public function delete() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->hubungan->delete($post[$this->primaryKey]);
        $message = 'dihapus';

        $this->output_handler->output_JSON($result, $message);
    }

    public function get_all() {
        $data = $this->hubungan->get_all();

        $this->output_handler->output_JSON($data);
    }

}

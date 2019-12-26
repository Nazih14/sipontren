<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Akun extends CI_Controller {

    var $primaryKey = 'ID_SANTRI';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'akun_model' => 'akun',
        ));
        $this->auth->validation(array(1));
    }

    public function index() {
        $data = array(
            'title' => 'Akun',
            'breadcrumb' => 'Pengaturan > Akun',
            'table' => array(
                'akun' => array(
                    array(
                        'field' => "ID_USER",
                        'title' => "ID",
                        'sortable' => "ID_USER",
                        'show' => FALSE,
                        'filter' => array(
                            'ID_USER' => 'text'
                        ),
                    ),
                    array(
                        'field' => "ID_UST",
                        'title' => "ID",
                        'sortable' => "ID_UST",
                        'show' => FALSE,
                        'filter' => array(
                            'ID_UST' => 'text'
                        ),
                    ),
                    array(
                        'field' => "NAME_USER",
                        'title' => "Username",
                        'sortable' => "NAME_USER",
                        'show' => true,
                        'filter' => array(
                            'NAME_USER' => 'text'
                        ),
                    ),
                    array(
                        'field' => "NAMA_UST",
                        'title' => "Nama Ustadz",
                        'sortable' => "NAMA_UST",
                        'show' => true,
                        'filter' => array(
                            'NAMA_UST' => 'text'
                        ),
                    ),
                    array(
                        'field' => "STATUS_USER",
                        'title' => "Status",
                        'sortable' => "STATUS_USER",
                        'show' => true,
                        'filter' => array(
                            'STATUS_USER' => 'select'
                        ),
                        'filterData' => array(
                            array('id' => 'ACTIVE', 'title' => 'ACTIVE'),
                            array('id' => 'BLOCK', 'title' => 'BLOCK')
                        )
                    ),
                    array(
                        'field' => "ACTION",
                        'title' => "Aksi",
                    ),
                ),
                'hakakses' => array(
                    array(
                        'field' => "NAME_HAKAKSES",
                        'title' => "Hakakses",
                        'sortable' => "NAME_HAKAKSES",
                        'show' => true,
                        'filter' => array(
                            'NAME_HAKAKSES' => 'text'
                        ),
                    ),
                    array(
                        'field' => "ACTION",
                        'title' => "Aksi",
                    ),
                ),
            )
        );
        $this->output_handler->output_JSON($data);
    }

    public function get_datatable_akun() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->akun->get_datatable_akun($post);

        $this->output_handler->output_JSON($data);
    }

    public function get_datatable_hakakses() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->akun->get_datatable_hakakses($post);

        $this->output_handler->output_JSON($data);
    }

    public function proses_hakakses() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->akun->proses_hakakses($post);
        
        if ($post['set'])
            $message = 'diproses';
        else
            $message = 'dihapus';
        
        $extra = array(
            'hakakses' => true
        );

        $this->output_handler->output_JSON($result, $message, $extra);
    }

    public function proses_status() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->akun->proses_status($post);
        $message = 'diproses';
        
        $extra = array(
            'akun' => true
        );

        $this->output_handler->output_JSON($result, $message, $extra);
    }

    public function change_password() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->akun->change_password($post);
        $message = 'dirubah';
        
        $extra = array(
            'akun' => true
        );

        $this->output_handler->output_JSON($result, $message, $extra);
    }

}

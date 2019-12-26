<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pengembalian extends CI_Controller {

    var $primaryKey = 'ID_SANTRI';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'pengembalian_model' => 'pengembalian',
        ));
        $this->auth->validation(array(1, 6));
    }

    public function index() {
        $data = array(
            'title' => 'Pengembalian Buku',
            'breadcrumb' => 'Perpustakaan > Pengembalian Buku',
            'buku' => $this->pengembalian->get_buku(),
        );
        $this->output_handler->output_JSON($data);
    }

    public function proses_pengembalian() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->pengembalian->proses_pengembalian($data);
        $message = 'diproses';

        $this->output_handler->output_JSON($result, $message);
    }

}

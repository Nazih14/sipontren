<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Aplikasi extends CI_Controller {

    var $primaryKey = 'ID_SANTRI';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'pengaturan_model',
        ));
        $this->auth->validation(array(1));
    }

    public function index() {
        $data = array(
            'title' => 'Pengaturan Aplikasi',
            'breadcrumb' => 'Pengaturan > Aplikasi',
            'data' => array(
                'nama_aplikasi' => $this->pengaturan_model->get_by_id('nama_aplikasi')
            )
        );
        $this->output_handler->output_JSON($data);
    }

    public function save() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = TRUE;
        foreach ($data as $key => $value) {
            $result = $this->pengaturan_model->update($key, $value);
        }
        $message = 'diproses';

        $this->output_handler->output_JSON($result, $message);
    }

}

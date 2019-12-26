<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pembayaran extends CI_Controller {

    var $primaryKey = 'ID_SANTRI';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'pembayaran_model' => 'pembayaran',
            'data_santri_model' => 'data_santri',
        ));
        $this->auth->validation(array(1, 5));
    }

    public function index() {
        $data = array(
            'title' => 'Pembayaran Tagihan Santri',
            'breadcrumb' => 'Keuangan > Pembayaran Tagihan Santri',
            'santri' => $this->data_santri->get_all(),
            'table' => array(
                array(
                    'field' => "ID_TAGIHAN",
                    'title' => "ID",
                    'sortable' => "ID_TAGIHAN",
                    'show' => false,
                    'filter' => array(
                        'ID_TAGIHAN' => 'number'
                    )
                ),   
                array(
                    'field' => "NAMA_TAGIHAN",
                    'title' => "Nama Tagihan",
                    'sortable' => "NAMA_TAGIHAN",
                    'show' => true,
                    'filter' => array(
                        'NAMA_TAGIHAN' => 'text'
                    )
                ),
                array(
                    'field' => "NOMIMAL_TAGIHAN_SHOW",
                    'title' => "Nominal",
                    'sortable' => "NOMIMAL_TAGIHAN_SHOW",
                    'show' => true,
                    'filter' => array(
                        'NOMINAL_TAGIHAN' => 'number'
                    )
                ),
                array(
                    'field' => "ACTION",
                    'title' => "Aksi",
                ),
            )
        );
        $this->output_handler->output_JSON($data);
    }

    public function datatable() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->pembayaran->get_datatable($post);

        $this->output_handler->output_JSON($data);
    }

    public function get_tagihan() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->pembayaran->get_tagihan($post['ID_SANTRI']);

        if (count($data) == 0) {
            $data = array(
                array(
                    'id' => '',
                    'title' => 'Tidak ada tagihan'
                )
            );
        }

        $this->output_handler->output_JSON($data);
    }

    public function prosesPembayaran() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->pembayaran->prosesPembayaran($data);
        $message = 'diproses';

        $this->output_handler->output_JSON($result, $message);
    }

}

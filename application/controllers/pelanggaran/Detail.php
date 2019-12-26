<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Detail extends CI_Controller {

    var $primaryKey = 'ID_PSN';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'pelanggaran_detail_model' => 'detail',
            'kamar_model' => 'kamar',
        ));
        $this->auth->validation(array(1, 7));
    }

    public function index() {
        $data = array(
            'title' => 'Detail Pelanggaran',
            'breadcrumb' => 'Pelanggaran > Detail',
            'wide' => true,
            'table' => array(
                array(
                    'field' => "ID_PSN",
                    'title' => "ID",
                    'sortable' => "ID_PSN",
                    'show' => FALSE,
                    'filter' => array(
                        'ID_PSN' => 'number'
                    )
                ),
                array(
                    'field' => "TANGGAL_PSN",
                    'title' => "Tanggal",
                    'sortable' => "TANGGAL_PSN",
                    'show' => true,
                    'filter' => array(
                        'TANGGAL_PSN' => 'text'
                    )
                ),
                array(
                    'field' => "NIS_SANTRI",
                    'title' => "NIS",
                    'sortable' => "NIS_SANTRI",
                    'show' => true,
                    'filter' => array(
                        'NIS_SANTRI' => 'text'
                    )
                ),
                array(
                    'field' => "NAMA_SANTRI",
                    'title' => "Nama",
                    'sortable' => "NAMA_SANTRI",
                    'show' => true,
                    'filter' => array(
                        'NAMA_SANTRI' => 'text'
                    )
                ),
                array(
                    'field' => "KAMAR_GEDUNG",
                    'title' => "Kamar",
                    'sortable' => "KAMAR_GEDUNG",
                    'show' => true,
                    'filter' => array(
                        'ID_KAMAR' => 'select'
                    ),
                    'filterData' => $this->kamar->get_all()
                ),
                array(
                    'field' => "NAMA_PJS",
                    'title' => "Pelanggaran",
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
        
        $data = $this->detail->get_datatable($post);

        $this->output_handler->output_JSON($data);
    }

    public function form() {
        $data = array(
            'uri' => array(
                'santri' => site_url('santri/data/get_all'),
                'jenis' => site_url('pelanggaran/jenis/get_all'),
            ),
        );

        $this->output_handler->output_JSON($data);
    }

    public function data() {
        $data = $this->detail->get_datatables();

        $this->output_handler->output_JSON($data);
    }

    public function view() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->detail->get_form_data($post[$this->primaryKey]);

        $this->output_handler->output_JSON($data);
    }

    public function save() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->detail->save($data);

        if (isset($data[$this->primaryKey]))
            $message = 'diubah';
        else
            $message = 'dibuat';

        $this->output_handler->output_JSON($result, $message);
    }

    public function delete() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->detail->delete($post[$this->primaryKey]);
        $message = 'dihapus';

        $this->output_handler->output_JSON($result, $message);
    }

    public function get_all() {
        $data = $this->detail->get_all();

        $this->output_handler->output_JSON($data);
    }

}

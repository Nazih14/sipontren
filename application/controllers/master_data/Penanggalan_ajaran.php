<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Penanggalan_ajaran extends CI_Controller {

    var $primaryKey = 'ID_CAWU';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'penanggalan_ajaran_model' => 'penanggalan_ajaran'
        ));
        $this->auth->validation(1);
    }

    public function index() {
        $data = array(
            'title' => 'Master Data Penanggalan Ajaran',
            'breadcrumb' => 'Pengaturan > Akademik > Penanggalan Ajaran',
            'table' => array(
                array(
                    'field' => "ID_CAWU",
                    'title' => "ID",
                    'sortable' => "ID_CAWU",
                    'show' => FALSE,
                    'filter' => array(
                        'ID_CAWU' => 'number'
                    )
                ),
                array(
                    'field' => "NAMA_CAWU",
                    'title' => "Nama Penanggalan Ajaran",
                    'sortable' => "NAMA_CAWU",
                    'show' => true,
                    'filter' => array(
                        'NAMA_CAWU' => 'text'
                    )
                ),
                array(
                    'field' => "STATUS_AKTIF_CAWU",
                    'title' => "Status Aktif",
                    'sortable' => "STATUS_AKTIF_CAWU",
                    'show' => true,
                    'filter' => array(
                        'AKTIF_CAWU' => 'select'
                    ),
                    'filterData' => array(
                        array(
                            'id' => 1,
                            'title' => 'YA'
                        ),
                        array(
                            'id' => 0,
                            'title' => 'TIDAK'
                        )
                    )
                ),
                array(
                    'field' => "KETERANGAN_CAWU",
                    'title' => "Keterangan",
                    'sortable' => "KETERANGAN_CAWU",
                    'show' => true,
                    'filter' => array(
                        'KETERANGAN_CAWU' => 'text'
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
        $data = $this->penanggalan_ajaran->get_datatable();

        $this->output_handler->output_JSON($data);
    }

    public function form() {
        $data = array(
            'dataAKTIF_CAWU' => array(
                array('id' => 1, 'title' => 'YA'),
                array('id' => 0, 'title' => 'TIDAK'),
            )
        );

        $this->output_handler->output_JSON($data);
    }

    public function data() {
        $data = $this->penanggalan_ajaran->get_datatables();

        $this->output_handler->output_JSON($data);
    }

    public function view() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->penanggalan_ajaran->get_by_id($post[$this->primaryKey]);

        $this->output_handler->output_JSON($data);
    }

    public function save() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->penanggalan_ajaran->save($data);

        if (isset($data[$this->primaryKey]))
            $message = 'diubah';
        else
            $message = 'dibuat';

        $this->output_handler->output_JSON($result, $message);
    }

    public function delete() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->penanggalan_ajaran->delete($post[$this->primaryKey]);
        $message = 'dihapus';

        $this->output_handler->output_JSON($result, $message);
    }

    public function get_all() {
        $data = $this->penanggalan_ajaran->get_all();

        $this->output_handler->output_JSON($data);
    }
    
    public function change_session() {
        $post = json_decode(file_get_contents('php://input'), true);
        
        $this->session->set_userdata('ID_CAWU', $post['id']);
        $this->session->set_userdata('NAMA_CAWU', $post['title']);
        
        $this->output_handler->output_JSON($post);
    }

}

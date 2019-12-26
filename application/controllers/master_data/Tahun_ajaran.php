<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tahun_ajaran extends CI_Controller {

    var $primaryKey = 'ID_TA';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'tahun_ajaran_model' => 'tahun_ajaran',
            'Penanggalan_ajaran_model' => 'penanggalan_ajaran'
        ));
        $this->auth->validation(1);
    }

    public function index() {
        $data = array(
            'title' => 'Master Data Tahun Ajaran',
            'breadcrumb' => 'Pengaturan > Akademik > Tahun Ajaran',
            'table' => array(
                array(
                    'field' => "ID_TA",
                    'title' => "ID",
                    'sortable' => "ID_TA",
                    'show' => FALSE,
                    'filter' => array(
                        'ID_TA' => 'number'
                    )
                ),
                array(
                    'field' => "NAMA_TA",
                    'title' => "Nama Tahun Ajaran",
                    'sortable' => "NAMA_TA",
                    'show' => true,
                    'filter' => array(
                        'NAMA_TA' => 'text'
                    )
                ),
                array(
                    'field' => "TANGGAL_MULAI_TA",
                    'title' => "Tanggal Mulai",
                    'sortable' => "TANGGAL_MULAI_TA",
                    'show' => true,
                    'filter' => array(
                        'TANGGAL_MULAI_TA' => 'text'
                    )
                ),
                array(
                    'field' => "TANGGAL_AKHIR_TA",
                    'title' => "Tanggal Akhir",
                    'sortable' => "TANGGAL_AKHIR_TA",
                    'show' => true,
                    'filter' => array(
                        'TANGGAL_AKHIR_TA' => 'text'
                    )
                ),
                array(
                    'field' => "STATUS_AKTIF_TA",
                    'title' => "Status Aktif",
                    'sortable' => "STATUS_AKTIF_TA",
                    'show' => true,
                    'filter' => array(
                        'AKTIF_TA' => 'select'
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
                    'field' => "KETERANGAN_TA",
                    'title' => "Keterangan",
                    'sortable' => "KETERANGAN_TA",
                    'show' => true,
                    'filter' => array(
                        'KETERANGAN_TA' => 'text'
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
        $data = $this->tahun_ajaran->get_datatable();

        $this->output_handler->output_JSON($data);
    }

    public function form() {
        $data = array(
            'dataAKTIF_TA' => array(
                array('id' => 1, 'title' => 'YA'),
                array('id' => 0, 'title' => 'TIDAK'),
            )
        );

        $this->output_handler->output_JSON($data);
    }

    public function data() {
        $data = $this->tahun_ajaran->get_datatables();

        $this->output_handler->output_JSON($data);
    }

    public function view() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->tahun_ajaran->get_by_id($post[$this->primaryKey]);

        $this->output_handler->output_JSON($data);
    }

    public function save() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->tahun_ajaran->save($data);

        if (isset($data[$this->primaryKey]))
            $message = 'diubah';
        else
            $message = 'dibuat';

        $this->output_handler->output_JSON($result, $message);
    }

    public function delete() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->tahun_ajaran->delete($post[$this->primaryKey]);
        $message = 'dihapus';

        $this->output_handler->output_JSON($result, $message);
    }

    public function get_all() {
        $data = $this->tahun_ajaran->get_all();

        $this->output_handler->output_JSON($data);
    }

    public function get_ajaran_active() {
        $data_ta = $this->tahun_ajaran->get_ta_active();
        $data_cawu = $this->penanggalan_ajaran->get_active();
        
        $this->session->set_userdata('ID_TA', $data_ta['ID_TA']);
        $this->session->set_userdata('NAMA_TA', $data_ta['NAMA_TA']);
        $this->session->set_userdata('ID_CAWU', $data_cawu['ID_CAWU']);
        $this->session->set_userdata('NAMA_CAWU', $data_cawu['NAMA_CAWU']);

        $this->output_handler->output_JSON(array('ta' => $data_ta, 'cawu' => $data_cawu));
    }
    
    public function change_ta_session() {
        $post = json_decode(file_get_contents('php://input'), true);
        
        $this->session->set_userdata('ID_TA', $post['id']);
        $this->session->set_userdata('NAMA_TA', $post['title']);
        
        $this->output_handler->output_JSON($post);
    }

}

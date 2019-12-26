<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Tagihan extends CI_Controller
{
    public $primaryKey = 'ID_TAGIHAN';

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'tagihan_model' => 'tagihan',
            'kelas_model' => 'kelas',
            'tahun_ajaran_model' => 'ta',
        ));
        $this->auth->validation(array(1, 5));
    }

    public function index()
    {
        $data = array(
            'title' => 'Tagihan',
            'breadcrumb' => 'Keuangan > Tagihan',
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
                    'field' => "KELAS_KEGIATAN",
                    'title' => "Kelas",
                    'sortable' => "KELAS_KEGIATAN",
                    'show' => true,
                    'filter' => array(
                        'ID_KELAS' => 'select'
                    ),
                    'filterData' => $this->kelas->get_all()
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

    public function datatable()
    {
        $data = $this->tagihan->get_datatable();

        $this->output_handler->output_JSON($data);
    }

    public function form()
    {
        $data = array(
            'dataKELAS_TAGIHAN' => $this->kelas->get_all(),
        );

        $this->output_handler->output_JSON($data);
    }

    public function data()
    {
        $data = $this->tagihan->get_datatables();

        $this->output_handler->output_JSON($data);
    }

    public function view()
    {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->tagihan->get_form_data($post[$this->primaryKey]);

        $this->output_handler->output_JSON($data);
    }

    public function save()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->tagihan->save($data);

        if (isset($data[$this->primaryKey])) {
            $message = 'diubah';
        } else {
            $message = 'dibuat';
        }

        $this->output_handler->output_JSON($result, $message);
    }

    public function delete()
    {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->tagihan->delete($post[$this->primaryKey]);
        $message = 'dihapus';

        $this->output_handler->output_JSON($result, $message);
    }

    public function get_all()
    {
        $data = $this->tagihan->get_all();

        $this->output_handler->output_JSON($data);
    }
}

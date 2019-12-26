<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Rombongan Belajar';
$controller = 'mdRombel';
$data = array(
    array(
        'type' => 'select',
        'field' => 'KELAS_ROMBEL',
        'label' => 'Kelas'
    ),
    array(
        'type' => 'select',
        'field' => 'RUANG_ROMBEL',
        'label' => 'Ruang'
    ),
    array(
        'type' => 'text',
        'field' => 'NAMA_ROMBEL',
        'label' => 'Nama Rombongan Belajar'
    ),
    array(
        'type' => 'select',
        'field' => 'JURUSAN_ROMBEL',
        'label' => 'Jurusan'
    ),
    array(
        'type' => 'text',
        'field' => 'KETERANGAN_ROMBEL',
        'label' => 'Keterangan'
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>
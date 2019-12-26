<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Mapel';
$controller = 'akadMapel';
$data = array(
    array(
        'type' => 'select',
        'field' => 'KELAS_MAPEL',
        'label' => 'Kelas'
    ),
    array(
        'type' => 'text',
        'field' => 'KODE_MAPEL',
        'label' => 'Kode Mapel'
    ),
    array(
        'type' => 'text',
        'field' => 'NAMA_MAPEL',
        'label' => 'Nama Mapel'
    ),
    array(
        'required' => false,
        'type' => 'text',
        'field' => 'KETERANGAN_MAPEL',
        'label' => 'Keterangan Mapel'
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>
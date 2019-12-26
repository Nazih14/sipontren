<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Penanggalan Ajaran';
$controller = 'mdPA';
$data = array(
    array(
        'type' => 'text',
        'field' => 'NAMA_CAWU',
        'label' => 'Nama Penanggalan'
    ),
    array(
        'type' => 'select',
        'field' => 'AKTIF_CAWU',
        'label' => 'Status Aktif'
    ),
    array(
        'type' => 'text',
        'field' => 'KETERANGAN_CAWU',
        'label' => 'Keterangan'
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>
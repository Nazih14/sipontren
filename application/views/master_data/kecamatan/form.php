<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Kecamatan';
$controller = 'kecamatan';
$data = array(
    array(
        'type' => 'autocomplete',
        'field' => 'ID_KAB',
        'label' => 'Kabupaten'
    ),
    array(
        'type' => 'text',
        'field' => 'NAMA_KEC',
        'label' => 'Nama Kecamatan'
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>
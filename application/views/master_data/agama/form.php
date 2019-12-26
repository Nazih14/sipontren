<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Agama';
$controller = 'mdAgama';
$data = array(
    array(
        'type' => 'text',
        'field' => 'NAMA_AGAMA',
        'label' => 'Nama Agama'
    ),
    array(
        'type' => 'text',
        'field' => 'KODE_EMIS_AGAMA',
        'label' => 'Kode EMIS'
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>
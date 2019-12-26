<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Kondisi';
$controller = 'mdKondisi';
$data = array(
    array(
        'type' => 'text',
        'field' => 'NAMA_KONDISI',
        'label' => 'Nama Kondisi'
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>
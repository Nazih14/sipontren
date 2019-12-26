<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Tagihan';
$controller = 'akadTagihan';
$data = array(
    array(
        'type' => 'select',
        'field' => 'KELAS_TAGIHAN',
        'label' => 'Kelas'
    ),
    array(
        'type' => 'text',
        'field' => 'NAMA_TAGIHAN',
        'label' => 'Nama Tagihan'
    ),
    array(
        'type' => 'number',
        'field' => 'NOMINAL_TAGIHAN',
        'label' => 'Nominal Tagihan'
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>
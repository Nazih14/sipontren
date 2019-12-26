<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Data Orangtua Santri';
$controller = 'dataSantri';
$data = array(
    array(
        'type' => 'select',
        'field' => 'ORTU_PENGHASILAN_SANTRI',
        'label' => 'Penghasilan',
    ),
    array(
        'type' => 'number',
        'field' => 'ORTU_NOHP1_SANTRI',
        'label' => 'Nomor HP 1',
        'required' => FALSE
    ),
    array(
        'type' => 'number',
        'field' => 'ORTU_NOHP2_SANTRI',
        'label' => 'Nomor HP 2',
        'required' => FALSE
    ),
    array(
        'type' => 'number',
        'field' => 'ORTU_NOHP3_SANTRI',
        'label' => 'Nomor HP 3',
        'required' => FALSE
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>
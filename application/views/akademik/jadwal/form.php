<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Jadwal';
$controller = 'akadJadwal';
$data = array(
    array(
        'type' => 'select',
        'field' => 'MAPEL_AJ',
        'label' => 'Mapel'
    ),
    array(
        'type' => 'select',
        'field' => 'USTADZ_AJ',
        'label' => 'Ustadz'
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>
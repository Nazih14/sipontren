<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Pembayaran Santri';
$message = 'Untuk melakukan penambahan pelanggaran siswa dapat dilakukan di menu detail pelanggaran';

$this->output_handler->dialog_info($title, $message);

?>
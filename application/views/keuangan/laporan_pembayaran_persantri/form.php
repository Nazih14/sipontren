<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Pembayaran Santri';
$message = 'Untuk melakukan pembayaran dapat dilakukan di menu keuangan -> pembayaran';

$this->output_handler->dialog_info($title, $message);

?>
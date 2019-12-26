<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();
?>
<div layout="row" ng-controller="homeController" ng-cloak class="kk-bg-dark panel-datatable">
    <div style="margin: 30px;">
        <?php
        $this->output_handler->form_select(
                array(
                    'required' => false,
                    'type' => 'select',
                    'field' => 'ID_TA',
                    'label' => 'Tahun Ajaran Aktif'
        ));
        ?>
        <md-tooltip md-direction="bottom">
            Klik untuk memilih tahun ajaran
        </md-tooltip>
    </div>
    <div style="margin: 30px;">
        <?php
        $this->output_handler->form_select(
                array(
                    'required' => false,
                    'type' => 'select',
                    'field' => 'ID_CAWU',
                    'label' => 'Penanggalan Ajaran Aktif'
        ));
        ?>
        <md-tooltip md-direction="bottom">
            Klik untuk memilih tahun ajaran
        </md-tooltip>
    </div>
</div>
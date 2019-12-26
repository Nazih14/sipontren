<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$this->output_handler->start_content('keuSaldoController');
?>
<form ng-cloak name="form" ng-submit="pilihFilter(form);" novalidate>
    <div layout="row">
        <div flex="30">
            <div style="margin: 30px;">
                <?php
                $this->output_handler->form_date(
                        array(
                            'type' => 'date',
                            'field' => 'START_DATE',
                            'label' => 'Pilih tanggal terlebih dahulu'
                ));
                ?>
                <md-tooltip md-direction="bottom">
                    Klik untuk menentukan tanggal mulai
                </md-tooltip>
            </div>
        </div>
        <div flex="30">
            <div style="margin: 30px;">
                <?php
                $this->output_handler->form_date(
                        array(
                            'type' => 'date',
                            'field' => 'END_DATE',
                            'label' => 'Pilih tanggal terlebih dahulu'
                ));
                ?>
                <md-tooltip md-direction="bottom">
                    Klik untuk menentukan tanggal selesai
                </md-tooltip>
            </div>
        </div>
        <div flex="30">
            <div style="margin: 30px;">
                <?php
                $this->output_handler->form_select(
                        array(
                            'type' => 'select',
                            'field' => 'USER_BAYAR',
                            'label' => 'Pilih petugas terlebih dahulu'
                ));
                ?>
                <md-tooltip md-direction="bottom">
                    Klik untuk menentukan petugas
                </md-tooltip>
            </div>
        </div>
        <div flex="10" style="margin-top: 20px">
            <md-button type="submit" class="md-primary md-raised">
                PROSES
                <md-tooltip md-direction="bottom">
                    Klik untuk melihat data
                </md-tooltip>
            </md-button>
        </div>
    </div>
</form>
<div layout="row" ng-if="formReady">
    <div flex="100" layout layout-align="center center">
        <h2>SALDO: {{ total | currency }}</h2>
    </div>
</div>
<div layout="row" ng-if="formReady">
    <div flex>
        <table ng-table-dynamic="dataTables with table" class="table table-condensed table-bordered table-striped table-hover" show-filter="true">
            <tr ng-repeat="row in $data">
                <td ng-repeat="col in $columns">
                    {{row[col.field]}}
                </td>
            </tr>
        </table>
    </div>
</div>

<?php
$this->output_handler->end_content();
?>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$this->output_handler->start_content('akadNaikController');
?>
<div layout="row">
    <div flex="45">
        <div layout="row">
            <div flex="85">
                <div style="margin: 30px;">
                    <?php
                    $this->output_handler->form_select(
                            array(
                                'type' => 'select',
                                'field' => 'ROMBEL_LAMA',
                                'label' => 'Pilih rombel yang akan diproses'
                    ));
                    ?>
                    <md-tooltip md-direction="bottom">
                        Klik untuk memilih rombel
                    </md-tooltip>
                </div>
            </div>
            <div flex="15" style="margin-top: 20px" ng-if="formReady">
                <md-button type="button" class="md-primary md-raised" ng-click="naikSemua($event)">
                    Naikan Semua
                    <md-tooltip md-direction="bottom">
                        Klik untuk menaikan semua siswa
                    </md-tooltip>
                </md-button>
            </div>
        </div>
        <div layout="row" ng-if="formReady">
            <div flex>
                <table ng-table-dynamic="dataTablesSantriLama with table" class="table table-condensed table-bordered table-striped table-hover" show-filter="true">
                    <tr ng-repeat="row in $data">
                        <td ng-repeat="col in $columns">
                            <div ng-if="col.field === 'ACTION'">
                                <md-button ng-click="prosesNaik(row, true)" class="md-icon-button">
                                    <md-tooltip md-direction="bottom">
                                        Klik untuk menaikan santri
                                    </md-tooltip>
                                    <md-icon class="material-icons md-24 kk-icon-title" aria-label="Naik">check</md-icon>
                                </md-button>
                            </div>
                            {{row[col.field]}}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div flex="45" flex-offset="10">
        <div layout="row">
            <div flex="50">
                <div style="margin: 30px;">
                    <?php
                    $this->output_handler->form_select(
                            array(
                                'type' => 'select',
                                'field' => 'TA_BARU',
                                'label' => 'Pilih TA selanjutnya'
                    ));
                    ?>
                    <md-tooltip md-direction="bottom">
                        Klik untuk memilih TA selanjutnya
                    </md-tooltip>
                </div>
            </div>
            <div flex="50">
                <div style="margin: 30px;">
                    <?php
                    $this->output_handler->form_select(
                            array(
                                'type' => 'select',
                                'field' => 'ROMBEL_BARU',
                                'label' => 'Pilih rombel selanjutnya'
                    ));
                    ?>
                    <md-tooltip md-direction="bottom">
                        Klik untuk memilih rombel selanjutnya
                    </md-tooltip>
                </div>
            </div>
        </div>
        <div layout="row" ng-if="formReady">
            <div flex>
                <table ng-table-dynamic="dataTablesSantriBaru with table" class="table table-condensed table-bordered table-striped table-hover" show-filter="true">
                    <tr ng-repeat="row in $data">
                        <td ng-repeat="col in $columns">
                            <div ng-if="col.field === 'ACTION'">
                                <md-button ng-click="prosesNaik(row, false)" class="md-icon-button">
                                    <md-tooltip md-direction="bottom">
                                        Klik untuk membatalkan kenaikan santri
                                    </md-tooltip>
                                    <md-icon class="material-icons md-24 kk-icon-title" aria-label="Hapus Naik">clear</md-icon>
                                </md-button>
                            </div>
                            {{row[col.field]}}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
$this->output_handler->end_content();
?>
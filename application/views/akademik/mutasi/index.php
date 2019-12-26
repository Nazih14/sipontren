<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$this->output_handler->start_content('akadMutasiController');
?>
<form ng-cloak name="form" ng-submit="pilihFilter(form);" novalidate>
    <div layout="row">
        <div flex="40">
            <div style="margin: 30px;">
                <?php
                $this->output_handler->form_select(
                        array(
                            'type' => 'select',
                            'field' => 'ROMBEL_AS',
                            'label' => 'Pilih rombel terlebih dahulu'
                ));
                ?>
                <md-tooltip md-direction="bottom">
                    Klik untuk memilih rombel
                </md-tooltip>
            </div>
        </div>
        <div flex="30">
            <div style="margin: 30px;">
                <?php
                $this->output_handler->form_select(
                        array(
                            'type' => 'select',
                            'field' => 'STATUS_MUTASI_SANTRI',
                            'label' => 'Pilih status mutasi terlebih dahulu'
                ));
                ?>
                <md-tooltip md-direction="bottom">
                    Klik untuk memilih status mutasi
                </md-tooltip>
            </div>
        </div>
        <div flex="30" style="margin-top: 20px" ng-if="formReady">
            <md-button type="button" class="md-primary md-raised" ng-click="prosesSemua()">
                Luluskan/Mutasikan Semua
            </md-button>
        </div>
    </div>
</form>
<div layout="row" ng-if="formReady">
    <div flex>
        <table ng-table-dynamic="dataTablesSantri with table" class="table table-condensed table-bordered table-striped table-hover" show-filter="true">
            <tr ng-repeat="row in $data">
                <td ng-repeat="col in $columns">
                    <div ng-if="col.field === 'ACTION'">
                        <md-button ng-click="prosesMutasi(row)" class="md-icon-button">
                            <md-tooltip md-direction="bottom">
                                Klik untuk memproses mutasi/kelulusan
                            </md-tooltip>
                            <md-icon class="material-icons md-24 kk-icon-title" aria-label="Mutasi">check</md-icon>
                        </md-button>
                    </div>
                    {{row[col.field]}}
                </td>
            </tr>
        </table>
    </div>
</div>

<?php
$this->output_handler->end_content();
?>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$this->output_handler->start_content('keuTagihanController');
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
        <div flex="40">
            <div style="margin: 30px;">
                <?php
                $this->output_handler->form_select(
                        array(
                            'type' => 'select',
                            'field' => 'TAGIHAN_KPC',
                            'label' => 'Pilih tagihan terlebih dahulu'
                ));
                ?>
                <md-tooltip md-direction="bottom">
                    Pilih rombel terlebih dahulu
                </md-tooltip>
            </div>
        </div>
        <div flex="10" style="margin-top: 20px">
            <md-button type="submit" class="md-primary md-raised">
                Buka
            </md-button>
        </div>
<!--        <div flex="10" style="margin-top: 20px" ng-if="formReady">
            <md-button type="button" class="md-accent md-raised" ng-click="checkAll()">
                Check All
            </md-button>
        </div>-->
    </div>
</form>
<div layout="row" ng-if="formReady">
    <div flex>
        <table ng-table-dynamic="dataTablesSantri with table" class="table table-condensed table-bordered table-striped table-hover" show-filter="true">
            <tr ng-repeat="row in $data">
                <td ng-repeat="col in $columns">
                    <div ng-if="col.field === 'ACTION'">
                        <md-checkbox ng-click="prosesTagihan(row)" class="md-primary" ng-model="formDataTag.TAGIHAN_KPC[row['ID_SANTRI']]" style="margin: 10px;" aria-label="Pilih Tagihan">
                        </md-checkbox>
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
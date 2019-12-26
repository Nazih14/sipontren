<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$this->output_handler->start_content('akadNilaiController');
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
                            'field' => 'JADWAL_NILAI',
                            'label' => 'Pilih jadwal guru - mapel'
                ));
                ?>
                <md-tooltip md-direction="bottom">
                    Pilih rombel terlebih dahulu
                </md-tooltip>
            </div>
        </div>
        <div flex="40" style="margin-top: 20px">
            <md-button type="submit" class="md-primary md-raised">
                Buka
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
                        <md-input-container style="margin: unset;margin-top: 24px;">
                            <label>Nilai</label>
                            <input ng-model="formDataNilai.NILAI_NILAI[row['ID_AS']]" type="text">
                        </md-input-container>
                        <md-button ng-click="prosesNilai(row)" class="md-icon-button">
                            <md-tooltip md-direction="bottom">
                                Klik untuk menyimpan nilai
                            </md-tooltip>
                            <md-icon class="material-icons md-24 kk-icon-title" aria-label="Nilai">check</md-icon>
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
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$this->output_handler->start_content('akadPresensiController');
?>
<form ng-cloak name="form" ng-submit="pilihFilter(form);" novalidate>
    <div layout="row">
        <div flex="40">
            <div style="margin: 30px;">
                <?php
                $this->output_handler->form_select(
                        array(
                            'type' => 'select',
                            'field' => 'ROMBEL_ABSENSI',
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
                $this->output_handler->form_date(
                        array(
                            'type' => 'select',
                            'field' => 'TANGGAL_ABSENSI',
                            'label' => 'Pilih tanggal terlebih dahulu'
                ));
                ?>
                <md-tooltip md-direction="bottom">
                    Klik untuk memilih tanggal
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
                        <md-input-container style="margin: unset;">
                            <label>Alasan</label>
                            <md-select name="ALASAN_ABSENSI" ng-model="formDataPresensi.ALASAN_ABSENSI[row['ID_SANTRI']]" required>
                                <md-option value="HADIR">HADIR</md-option>
                                <md-option value="SAKIT">SAKIT</md-option>
                                <md-option value="IZIN">IZIN</md-option>
                                <md-option value="ALPHA">ALPHA</md-option>
                                <md-option value="TERLAMBAT">TERLAMBAT</md-option>
                            </md-select>
                            <div class="errors" ng-messages="form.ALASAN_ABSENSI.$error">
                                <div ng-message="required">Required</div>
                            </div>
                        </md-input-container>
                        <md-input-container style="margin: unset;margin-top: 24px;">
                            <label>Keterangan</label>
                            <input ng-model="formDataPresensi.KETERANGAN_ABSENSI[row['ID_SANTRI']]" type="text">
                        </md-input-container>
                        <md-button ng-click="prosesPresensi(row)" class="md-icon-button">
                            <md-tooltip md-direction="bottom">
                                Klik untuk menyimpan presensi
                            </md-tooltip>
                            <md-icon class="material-icons md-24 kk-icon-title" aria-label="Presensi">check</md-icon>
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
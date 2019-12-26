<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$this->output_handler->start_content('kegiatanSantriController');
?>
<hr class="hr">
<div layout="row">
    <div flex="48">
        <h4>Data santri yang belum masuk ke kelas</h4>
        <table ng-table-dynamic="dataTablesSantri with table" class="table table-condensed table-bordered table-striped table-hover" show-filter="true">
            <tr ng-repeat="row in $data">
                <td ng-repeat="col in $columns">
            <md-button ng-if="col.field === 'ACTION'" aria-label="Menu" class="md-icon-button" ng-click="prosesSantri(row, 'set')">
                <md-tooltip md-direction="bottom">
                    Klik untuk memasukan santri ke kelas
                </md-tooltip>
                <md-icon class="material-icons md-24 kk-icon-title" aria-label="Pindahkan">navigate_next</md-icon>
            </md-button>
            {{row[col.field]}}
            </td>
            </tr>
        </table>
    </div>
    <div flex="48" flex-offset="5">
        <h4>Data santri yang sudah masuk ke kelas</h4>
        <div style="margin: 30px;">
            <?php
            $this->output_handler->form_select(
                    array(
                        'required' => false,
                        'type' => 'select',
                        'field' => 'KELAS_AS',
                        'label' => 'Pilih kelas terlebih dahulu'
            ));
            ?>
            <md-tooltip md-direction="bottom">
                Klik untuk memilih kelas
            </md-tooltip>
        </div>
        <table ng-table-dynamic="dataTablesKegiatan with table" class="table table-condensed table-bordered table-striped table-hover" show-filter="true">
            <tr ng-repeat="row in $data">
                <td ng-repeat="col in $columns">
            <md-button ng-if="col.field === 'ACTION'" aria-label="Menu" class="md-icon-button" ng-click="prosesSantri(row, 'remove')">
                <md-tooltip md-direction="bottom">
                    Klik untuk menghapus santri dari kelas
                </md-tooltip>
                <md-icon class="material-icons md-24 kk-icon-title" aria-label="Hapus santri">clear</md-icon>
            </md-button>
            {{row[col.field]}}
            </td>
            </tr>
        </table>
    </div>
</div>

<?php
$this->output_handler->end_content();
?>
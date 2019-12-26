<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$this->output_handler->start_content('akadRaporController');
?>
<hr class="hr">
<div layout="row">
    <div flex="{{flexKelas}}">
        <h4>Data Kelas</h4>
        <table ng-table-dynamic="dataTablesKelas with tableKelas" class="table table-condensed table-bordered table-striped table-hover" show-filter="true">
            <tr ng-repeat="row in $data">
                <td ng-repeat="col in $columns">
                    <div ng-if="col.field === 'ACTION'">
                        <md-button aria-label="Menu" class="md-icon-button" ng-click="datatableSantri(row)">
                            <md-tooltip md-direction="bottom">
                                Klik untuk melihat siswa
                            </md-tooltip>
                            <md-icon class="material-icons md-24 kk-icon-title" aria-label="Pindahkan">supervisor_account</md-icon>
                        </md-button>
                        <md-button aria-label="Menu" class="md-icon-button" ng-click="printKelas(row)">
                            <md-tooltip md-direction="bottom">
                                Klik untuk mencetak rapor
                            </md-tooltip>
                            <md-icon class="material-icons md-24 kk-icon-title" aria-label="Pindahkan">print</md-icon>
                        </md-button>
                    </div>
                    {{row[col.field]}}
                </td>
            </tr>
        </table>
    </div>
    <div flex="{{flexSantri}}" flex-offset="5" ng-if="TABLE_SANTRI_SHOW">
        <h4>Data siswa di {{NAMA_KELAS}}</h4>
        <table ng-table-dynamic="dataTablesSantri with tableSantri" class="table table-condensed table-bordered table-striped table-hover" show-filter="true">
            <tr ng-repeat="row in $data">
                <td ng-repeat="col in $columns">
                    <div ng-if="col.field === 'ACTION'">
                        <md-button aria-label="Menu" class="md-icon-button" ng-click="datatableNilai(row)">
                            <md-tooltip md-direction="bottom">
                                Klik untuk melihat nilai santri
                            </md-tooltip>
                            <md-icon class="material-icons md-24 kk-icon-title" aria-label="Pindahkan">border_color</md-icon>
                        </md-button>
                    </div>
                    {{row[col.field]}}
                </td>
            </tr>
        </table>
    </div>
    <div flex="{{flexNilai}}" flex-offset="5" ng-if="TABLE_NILAI_SHOW">
        <h4>Nilai santri dari {{NAMA_SANTRI}}</h4>
        <table ng-table-dynamic="dataTablesNilai with tableNilai" class="table table-condensed table-bordered table-striped table-hover" show-filter="true">
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
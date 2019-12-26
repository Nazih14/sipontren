<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$this->output_handler->start_content('settingAkunController');
?>
<hr class="hr">
<div layout="row">
    <div flex="{{flexAkun}}">
        <h4>Data Akun</h4>
        <table ng-table-dynamic="dataTablesAkun with tableAkun" class="table table-condensed table-bordered table-striped table-hover" show-filter="true">
            <tr ng-repeat="row in $data">
                <td ng-repeat="col in $columns">
                    <div ng-if="col.field === 'ACTION'">
                        <md-button aria-label="Menu" class="md-icon-button" ng-click="changePassword(row)">
                            <md-tooltip md-direction="bottom">
                                Klik untuk merubah password user
                            </md-tooltip>
                            <md-icon class="material-icons md-24 kk-icon-title" aria-label="Ubah Passoword">vpn_key</md-icon>
                        </md-button>
                        <md-button aria-label="Menu" class="md-icon-button" ng-click="datatableHakakses(row)">
                            <md-tooltip md-direction="bottom">
                                Klik untuk melihat hakakses user
                            </md-tooltip>
                            <md-icon class="material-icons md-24 kk-icon-title" aria-label="Hakakses">settings_applications</md-icon>
                        </md-button>
                        <md-button aria-label="Menu" class="md-icon-button" ng-click="activatedUser(row)">
                            <md-tooltip md-direction="bottom">
                                Klik untuk mengaktifkan user
                            </md-tooltip>
                            <md-icon class="material-icons md-24 kk-icon-title" aria-label="Status Akun">check</md-icon>
                        </md-button>
                    </div>
                    {{row[col.field]}}
                </td>
            </tr>
        </table>
    </div>
    <div flex="{{flexHakakses}}" flex-offset="5" ng-if="tableHakaksesShow">
        <h4>Data hakakses akun {{NAMA_UST}}</h4>
        <table ng-table-dynamic="dataTablesHakakses with tableHakakses" class="table table-condensed table-bordered table-striped table-hover" show-filter="true">
            <tr ng-repeat="row in $data">
                <td ng-repeat="col in $columns">
                    <div ng-if="col.field === 'ACTION'">
                        <md-button aria-label="Menu" class="md-icon-button" ng-click="prosesHakakses(row, false)" ng-if="row.USER_HU !== null">
                            <md-tooltip md-direction="bottom">
                                Klik untuk menghapus hakakses user
                            </md-tooltip>
                            <md-icon class="material-icons md-24 kk-icon-title" aria-label="Hapus Hakakses">clear</md-icon>
                        </md-button>
                        <md-button aria-label="Menu" class="md-icon-button" ng-click="prosesHakakses(row, true)" ng-if="row.USER_HU === null">
                            <md-tooltip md-direction="bottom">
                                Klik untuk memasukan hakakses user
                            </md-tooltip>
                            <md-icon class="material-icons md-24 kk-icon-title" aria-label="Set Hakakses">check</md-icon>
                        </md-button>
                    </div>
                    {{row[col.field]}}
                </td>
            </tr>
        </table>
    </div>
    <div flex="{{flexHakakses}}" flex-offset="5" ng-if="tablePasswordShow">
        <h4>Merubah password akun {{NAMA_UST}}</h4>
        <form ng-cloak name="form" ng-submit="simpanPassword(form);" novalidate>
            <div layout="row">
                <div flex="70">
                    <div style="margin: 30px;">
                        <?php
                        $this->output_handler->form_input(
                                array(
                                    'type' => 'password',
                                    'field' => 'NEW_PASSWORD',
                                    'label' => 'Masukan password baru'
                        ));
                        ?>
                    </div>
                </div>
                <div flex="30" style="margin-top: 20px">
                    <md-button type="submit" class="md-primary md-raised">
                        SIMPAN
                        <md-tooltip md-direction="bottom">
                            Klik untuk meyimpan password baru
                        </md-tooltip>
                    </md-button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
$this->output_handler->end_content();
?>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$this->output_handler->start_content('perpusPeminajamanController');
?>
<form ng-cloak name="form" ng-submit="tambahPeminjaman(form);" novalidate>
    <div layout="row" style="margin-bottom: -25px;">
        <div flex="60" flex-offset='5'>
            <div style="margin: 12px;">
                <?php
                $this->output_handler->form_autocomplete(
                        array(
                            'type' => 'autocomplete',
                            'field' => 'ID_SANTRI',
                            'label' => 'santri terlebih dahulu'
                ));
                ?>
                <md-tooltip md-direction="bottom">
                    Klik untuk memilih santri
                </md-tooltip>
            </div>
        </div>
    </div>
    <div layout="row">
        <div flex="60" flex-offset='5'>
            <div style="margin: 12px;">
                <?php
                $this->output_handler->form_autocomplete(
                        array(
                            'type' => 'autocomplete',
                            'field' => 'ID_BUKU',
                            'label' => 'buku terlebih dahulu'
                ));
                ?>
                <md-tooltip md-direction="bottom">
                    Klik untuk memilih buku
                </md-tooltip>
            </div>
        </div>
        <div flex="10" style="margin-top: 20px">
            <md-button type="submit" class="md-primary md-raised">
                TAMBAH
            </md-button>
            <md-tooltip md-direction="bottom">
                Klik untuk memasukan buku ke list pinjaman
            </md-tooltip>
        </div>
        <div flex="10" style="margin-top: 20px">
            <md-button type="button" class="md-accent md-raised" ng-click="prosesPeminjaman()">
                SIMPAN
            </md-button>
            <md-tooltip md-direction="bottom">
                Klik untuk menyimpan pinjaman
            </md-tooltip>
        </div>
    </div>
</form>
<div layout="row" ng-if="formReady">
    <div flex>
        <md-list flex>
            <md-list-item class="md-2-line" ng-repeat="(index, buku) in data_buku" ng-click="hapusBuku(index, $event);">
                <div class="md-list-item-text" layout="column">
                    <h3>{{ buku.KODE_BUKU}} - {{ buku.NAMA_BUKU}}</h3>
                    <h4>Jenis: {{buku.NAMA_PJB}} | Pengarang: {{ buku.PENGARANG_BUKU}} | Penerbit: {{ buku.PENERBIT_BUKU}}</h4>
                </div>
                <md-divider ></md-divider>
            </md-list-item>
            <md-tooltip md-direction="bottom">
                Klik untuk menghapus buku
            </md-tooltip>
        </md-list>
    </div>
</div>

<?php
$this->output_handler->end_content();
?>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$this->output_handler->start_content('perpusPengembalianController');
?>
<form ng-cloak name="form" ng-submit="prosesPengembalian($event);" novalidate>
    <div layout="row">
        <div flex="75" flex-offset='5'>
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
                KEMBALIKAN
            </md-button>
            <md-tooltip md-direction="bottom">
                Klik untuk menyimpan pengembalian
            </md-tooltip>
        </div>
    </div>
</form>

<?php
$this->output_handler->end_content();
?>
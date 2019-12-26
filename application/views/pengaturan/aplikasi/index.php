<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$this->output_handler->start_content('settingAppsController');
?>
<form ng-cloak name="form" ng-submit="simpan(form);" novalidate>
    <div layout="row">
        <div flex="80">
            <div style="margin: 30px;">
                <?php
                $this->output_handler->form_input(
                        array(
                            'type' => 'text',
                            'field' => 'nama_aplikasi',
                            'label' => 'Nama Aplikasi'
                ));
                ?>
            </div>
        </div>
        <div flex="10" style="margin-top: 20px">
            <md-button type="submit" class="md-primary md-raised">
                SIMPAN
                <md-tooltip md-direction="bottom">
                    Klik untuk menyimpan
                </md-tooltip>
            </md-button>
        </div>
    </div>
</form>

<?php
$this->output_handler->end_content();
?>
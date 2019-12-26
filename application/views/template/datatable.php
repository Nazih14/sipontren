<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$this->output_handler->start_content('datatableController');
?>
<div layout="row">
    <div flex="100">
        <table ng-table-dynamic="dataTables with table" class="table table-condensed table-bordered table-striped table-hover" show-filter="true">
            <tr ng-repeat="row in $data">
                <td ng-repeat="col in $columns">
            <md-menu ng-if="col.field === 'ACTION'">
                <md-button aria-label="Menu" class="md-icon-button" ng-click="$mdMenu.open()">
                    <md-icon class="material-icons md-24 kk-icon-title" aria-label="Menu">menu</md-icon>
                </md-button>
                <md-menu-content width="3" ng-mouseleave="$mdMenu.close()">
                    <md-menu-item ng-repeat="action in col.actions">
                        <md-button ng-click="actionRow($event, action, row)">
                            {{action.title}}
                        </md-button>
                    </md-menu-item>
                </md-menu-content>
            </md-menu>
            {{row[col.field]}}
            </td>
            </tr>
        </table>
    </div>
</div>
<?php
$this->output_handler->end_content();
?>
<div layout="row" ng-controller="loginController" ng-cloak class="kk-bg-dark panel-datatable" style="margin-top: 120px">
    <div flex="40" flex-offset="30" class="md-whiteframe-3dp kk-reset-content animated fadeIn">
        <md-content layout-padding class='kk-content'>
            <div layout="row">
                <div flex="100" layout layout-align="center center">
                    <h2>LOGIN</h2>
                </div>
            </div>
            <div layout="row">
                <div flex="100" layout layout-align="center center">
                    <div>
                        <form name="loginForm" ng-submit="loginApp();">
                            <div layout-gt-sm="row">
                                <md-input-container class="md-block" flex-gt-sm>
                                    <label>Username</label>
                                    <input type="text" ng-model="formData.username" required name="username">
                                    <div ng-messages="loginForm.username.$error">
                                        <div ng-message="required">Wajid diisi</div>
                                    </div>
                                </md-input-container>

                                <md-input-container class="md-block" flex-gt-sm>
                                    <label>Password</label>
                                    <input type="password" ng-model="formData.password" required name="password">
                                    <div ng-messages="loginForm.username.$error">
                                        <div ng-message="required">Wajid diisi</div>
                                    </div>
                                </md-input-container>

                                <md-button class="md-fab md-primary md-mini" aria-label="Masuk Aplikasi" type="submit">
                                    <md-tooltip md-direction="bottom">Masuk Aplikasi</md-tooltip>
                                    <md-icon class="material-icons md-24">keyboard_arrow_right</md-icon>
                                </md-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div layout="row">
                <div flex="100" layout layout-align="center center">
                </div>
            </div>
        </md-content>
    </div>
</div>
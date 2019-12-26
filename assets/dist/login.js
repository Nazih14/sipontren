// CONTROLLERS APP

(function () {
    "use strict";

    angular.module('mainApp').controller('headerController', function ($rootScope, $scope, $http, url_menu, url_login, notificationService, $location, url_template, $mdBottomSheet, $mdDialog, url_home, dataScopeShared) {
        $http.get(url_menu).then(callbackMenu, notificationService.errorCallback);

        function callbackMenu(response) {
            $scope.name_app = response.data.name_app;
            $scope.menus = response.data.menus;
        }

        $scope.go = function (menus) {
            $location.path(menus.link);
        }

        $scope.showInfoDev = function () {
            $mdBottomSheet.show({
                templateUrl: url_template + 'template-info_dev.html',
            });
        };

        $scope.changePassword = function (event) {
            $mdDialog
                    .show({
                        clickOutsideToClose: true,
                        templateUrl: url_template + 'template-change_password.html',
                        targetEvent: event
                    })
                    .then(
                            function (notification) {
                                notificationService.toastSimple(notification);
                            },
                            function () {
                                // CANCEL DIALOG
                            }
                    );
        };

        $scope.logOut = function (ev) {
            var confirm = $mdDialog.confirm()
                    .title('Apakah Anda yakin keluar aplikasi?')
                    .targetEvent(ev)
                    .ok('YA')
                    .cancel('TIDAK');

            $mdDialog.show(confirm).then(function () {
                $rootScope.showMenu = false;

                $location.path(url_login);
            }, function () {

            });
        };

        $http.get('master_data/tahun_ajaran/get_ajaran_active').then(callbackTA, notificationService.errorCallback);

        function callbackTA(response) {
            if (typeof response.data === 'object') {
                dataScopeShared.addData('TA_ACTIVE', response.data.ta.ID_TA);
                dataScopeShared.addData('CAWU_ACTIVE', response.data.cawu.ID_CAWU);

                notificationService.toastSimple('Ajaran aktif adalah Tahun ' + response.data.ta.NAMA_TA + ' dan ' + response.data.cawu.NAMA_CAWU);
            } else {
                notificationService.toastSimple('Tahun Ajaran belum diatur. Silahkan diatur terlebih dahulu');

                $scope.routeToHome();
            }
        }

        $scope.routeToHome = function () {
            $location.path(url_home);
        }
    });

    angular.module('mainApp').controller('loginController', function ($rootScope, $scope, $http, notificationService, $routeParams, url_home, $location, $route, $templateCache) {
        $rootScope.showMenu = false;

        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.form = {
            'username': '',
            'password': ''
        };

//        $http.get($scope.mainURI + '/check_session').then(successCheck, notificationService.errorCallback);
//
//        function successCheck(response) {
//            if (response.data.status)
//                $location.path(url_home);
//            else
//                $http.get($scope.mainURI + '/logout');
//        }

        $http.get($scope.mainURI + '/logout');

        $scope.loginApp = function () {
            $http.post($scope.mainURI + '/proccess', $scope.formData).then(successCallback, notificationService.errorCallback);
        };

        function successCallback(response) {
            notificationService.toastSimple(response.data.notification);

            if (response.data.status) {
                $rootScope.showMenu = true;

                $location.path(url_home);

//                var currentPageTemplate = $route.current.templateUrl;
//                $templateCache.remove(currentPageTemplate);
//                $route.reload();
//                window.location.reload();
            }
        }
    });

})();
// CONTROLLERS APP

(function () {
    "use strict";

    angular.module('mainApp').controller('aboutController', function ($scope, $http, notificationService, dataScopeShared) {
        $scope.version = 0;

        $http.get('template/info').then(callbackSuccess, notificationService.errorCallback);

        function callbackSuccess(response) {
            $scope.version = response.data.version;
        }
    });

    angular.module('mainApp').controller('homeController', function ($scope, $http, notificationService, dataScopeShared) {
        $scope.dataID_TA = {};
        $scope.formData = {
            ID_TA: dataScopeShared.getData('TA_ACTIVE'),
            ID_CAWU: dataScopeShared.getData('CAWU_ACTIVE')
        };

        $http.get('master_data/tahun_ajaran/get_all').then(callbackSuccess, notificationService.errorCallback);

        function callbackSuccess(response) {
            $scope.dataID_TA = response.data;
        }

        $scope.$watch('formData.ID_TA', function (ID_TA) {
            angular.forEach($scope.dataID_TA, function (value, key) {
                if (ID_TA === value.id) {
                    $http.post('master_data/tahun_ajaran/change_ta_session', value).then(successCallbackTA, notificationService.errorCallback);
                }
            });
        });

        function successCallbackTA(response) {
            dataScopeShared.addData('TA_ACTIVE', response.data.id);
            notificationService.toastSimple('Tahun ajaran dirubah menjadi ' + response.data.title);
        }

        $http.get('master_data/penanggalan_ajaran/get_all').then(callbackSuccessCAWU, notificationService.errorCallback);

        function callbackSuccessCAWU(response) {
            $scope.dataID_CAWU = response.data;
        }

        $scope.$watch('formData.ID_CAWU', function (ID_CAWU) {
            angular.forEach($scope.dataID_CAWU, function (value, key) {
                if (ID_CAWU === value.id) {
                    $http.post('master_data/penanggalan_ajaran/change_session', value).then(successCallbackChangeCawu, notificationService.errorCallback);
                }
            });
        });

        function successCallbackChangeCawu(response) {
            dataScopeShared.addData('CAWU_ACTIVE', response.data.id);
            notificationService.toastSimple('Penganggalan ajaran dirubah menjadi ' + response.data.title);
        }
    });

    angular.module('mainApp').controller('changePasswordController', function ($scope, formHelper, notificationService, $routeParams, $http, $mdDialog, dataScopeShared) {
        $scope.ajaxRunning = false;
        $scope.addForm = false;

        $scope.formData = {
            NEW_PASSWORD: null,
            NEW_REPASSWORD: null,
        };

        $scope.cancelSumbit = function () {
            $mdDialog.cancel();
        };

        $scope.saveSubmit = function () {
            if ($scope.form.$valid) {
                if ($scope.formData.NEW_PASSWORD === $scope.formData.NEW_REPASSWORD) {
                    $scope.ajaxRunning = true;

                    $http.post('user/login/change_password', $scope.formData).then(callbackSuccessSaving, notificationService.errorCallback);
                } else {
                    notificationService.toastSimple('Password yang Anda masukan tidak sama');
                }
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function callbackSuccessSaving(response) {
            $scope.ajaxRunning = false;
            $mdDialog.hide(response.data.notification);
        }
    });

    angular.module('mainApp').controller('datatableController', function ($scope, $routeParams, $http, notificationService, NgTableParams, $mdDialog, url_template, $timeout, $mdSidenav, $route, $templateCache, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.mainTemplate = url_template + $routeParams.template;
        $scope.getData = getData();
        $scope.appReady = false;
        $scope.dataOriginal = null;
        $scope.fieldTable = [];
        $scope.flex = 80;
        $scope.flexOffset = 10;

        $scope.fabHidden = true;
        $scope.fabIsOpen = false;
        $scope.fabHover = false;

        $http.get($scope.mainURI + '/index').then(callbackSuccess, notificationService.errorCallback);

        function callbackSuccess(response) {
            $scope.title = response.data.title;
            $scope.breadcrumb = response.data.breadcrumb;
            $scope.table = response.data.table;

            $scope.fieldTable = [];
            angular.forEach($scope.table, function (item, key) {
                $scope.fieldTable.push(item.field);
            });

            if (response.data.wide) {
                $scope.flex = 90;
                $scope.flexOffset = 5;
            }

            getData();

            $scope.appReady = true;
        }

        function getData() {
            $http.post($scope.mainURI + '/datatable', $scope.fieldTable).then(callbackDatatables, notificationService.errorCallback);
        }

        function callbackDatatables(response) {
            $scope.dataOriginal = response.data.data;

            var initialParams = {
                count: 15
            };
            var initialSettings = {
                counts: [],
                dataset: response.data.data
            };

            $scope.dataTables = new NgTableParams(initialParams, initialSettings);
            $scope.fabHidden = false;
        }

        $scope.menuItems = [
            {id: "add_data", name: "Tambah Data", icon: "add"},
            {id: "download_data", name: "Unduh Data", icon: "file_download"},
            // {id: "print_data", name: "Catak Data", icon: "print"},
            {id: "reload_data", name: "Muat Ulang Data", icon: "refresh"},
            {id: "reload_page", name: "Muat Ulang Halaman", icon: "autorenew"},
            {id: "request_doc", name: "Dokumentasi", icon: "help"},
        ];

        $scope.openDialog = function ($event, item) {
            if (item.id === 'reload_data') {
                $scope.fabHidden = true;
                getData();
            } else if (item.id === 'reload_page') {
                reloadPage();
            } else if (item.id === 'request_doc') {
                $mdSidenav('right').toggle();
            } else if (item.id === 'add_data') {
                createDialog($event, 'form');
            } else if (item.id === 'print_data') {
                var mywindow = window.open('', 'PRINT', 'height=600,width=700');

                mywindow.document.write('<html><head><title>' + document.title + '</title><style type="text/css">body{font-family: "Roboto",Arial,sans-serif;overflow:visible;}.ng-table-filters,.ng-table-counts{display: none;} tr {border-top: 1px solid #f2f6f9;} .data-table{overflow: visible;} table{overflow:visible;}body, h1, h2, h3, ol, ul, div {     width: auto;     border: 0;     margin: 0 5%;     padding: 0;     float: none;     position: static;     overflow: visible; }</style>');
                mywindow.document.write('</head><body onload="window.print()">');
                mywindow.document.write('<h1>' + document.title + '</h1>');
                mywindow.document.write(document.getElementById('printable').innerHTML);
                mywindow.document.write('</body></html>');

                mywindow.document.close();
                mywindow.focus();

                return true;
            } else if (item.id === 'download_data') {
                if ($scope.dataOriginal === null)
                    notificationService.toastSimple('Data tidak ditemukan');
                else
                    alasql('SELECT * INTO XLSX("data_download.xlsx",{headers:true}) FROM ?', [$scope.dataOriginal]);
            }
        };

        function reloadPage() {
            var currentPageTemplate = $route.current.templateUrl;
            $templateCache.remove(currentPageTemplate);
            $route.reload();
        }

        function createDialog(event, mode) {
            $mdDialog
                    .show({
                        controller: DialogController,
                        clickOutsideToClose: false,
                        templateUrl: $scope.mainTemplate + '-' + mode + '.html',
                        targetEvent: event
                    })
                    .then(
                            function (notification) {
                                notificationService.toastSimple(notification);
                                getData();
                            },
                            function () {
                                // CANCEL DIALOG
                            }
                    );
        }

        function DialogController($scope, $mdDialog) {
            $scope.cancelSumbit = function () {
                dataScopeShared.addData('DATA_UPDATE', null);
                $mdDialog.cancel();
            };
        }

        $scope.actionRow = function ($event, action, data) {
            if (action.update)
                updateRow($event, data);
            else if (action.delete)
                deleteRow($event, data);
            else if (action.form)
                formRow($event, data, action.form);
        };

        function formRow(event, data, form) {
            dataScopeShared.addData('DATA_UPDATE', data);
            createDialog(event, form);
        }

        function updateRow(event, data) {
            dataScopeShared.addData('DATA_UPDATE', data);
            createDialog(event, 'form');
        }

        function deleteRow(event, data) {
            var confirm = $mdDialog.confirm()
                    .title('Apakah Anda yakin melanjutkan?')
                    .textContent('Data yang telah dihapus tidak dapat dikembalikan.')
                    .ariaLabel('Hapus data')
                    .targetEvent(event)
                    .ok('Ya')
                    .cancel('Tidak');

            $mdDialog.show(confirm).then(function () {
                $http.post($scope.mainURI + '/delete', data).then(callbackSuccessDelete, notificationService.errorCallback);
            }, function () {
                // cancel
            });
        }

        function callbackSuccessDelete(response) {
            notificationService.toastSimple(response.data.notification);
            getData();
        }
    });

    angular.module('mainApp').controller('kecamatanController', function ($scope, formHelper, notificationService, $routeParams, $http, $mdDialog, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.ajaxRunning = true;
        $scope.dataUpdate = dataScopeShared.getData('DATA_UPDATE');
        $scope.addForm = true;

        $scope.formData = {
            ID_KEC: null,
            KABUPATEN_KEC: null,
            NAMA_KEC: null
        };

        $http.get($scope.mainURI + '/form').then(callbackForm, notificationService.errorCallback);

        function callbackForm(response) {
            $http.get(response.data.uri.kabupaten).then(callbackFormData, notificationService.errorCallback);
        }

        function callbackFormData(response) {
            $scope.dataAutocomplete = response.data;
            $scope.ID_KAB = formHelper.autocomplete($scope);

            if ($scope.dataUpdate === null || typeof $scope.dataUpdate === 'undefined')
                formReady();
            else
                getData();
        }

        function getData() {
            $http.post($scope.mainURI + '/view', $scope.dataUpdate).then(callbackSuccessData, notificationService.errorCallback);
        }

        function callbackSuccessData(response) {
            angular.forEach($scope.ID_KAB.dataAll, function (value, key) {
                if (parseInt(response.data.ID_KAB) === parseInt(value.key)) {
                    $scope.ID_KAB.selectedItem = value;
                }
            });

            $scope.formData.ID_KEC = response.data.ID_KEC;
            $scope.formData.NAMA_KEC = response.data.NAMA_KEC;
            $scope.formData.KABUPATEN_KEC = response.data.KABUPATEN_KEC;

            $scope.addForm = false;

            formReady();
        }

        function formReady() {
            $scope.ajaxRunning = false;
        }

        $scope.cancelSumbit = function () {
            dataScopeShared.addData('DATA_UPDATE', null);
            $mdDialog.cancel();
        };

        $scope.saveSubmit = function () {
            if ($scope.form.ID_KAB.$valid && $scope.form.NAMA_KEC.$valid) {
                $scope.ajaxRunning = true;

                $http.post($scope.mainURI + '/save', $scope.formData).then(callbackSuccessSaving, notificationService.errorCallback);
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function callbackSuccessSaving(response) {
            $scope.ajaxRunning = false;
            $mdDialog.hide(response.data.notification);
            dataScopeShared.addData('DATA_UPDATE', null);
        }

        $scope.$watch('ID_KAB.selectedItem', function (selectedItem) {
            if (typeof selectedItem === 'undefined' || selectedItem.key === null)
                $scope.formData.KABUPATEN_KEC = null;
            else
                $scope.formData.KABUPATEN_KEC = selectedItem.key;
        });
    });

    angular.module('mainApp').controller('mdKabupatenController', function ($scope, formHelper, notificationService, $routeParams, $http, $mdDialog, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.ajaxRunning = true;
        $scope.dataUpdate = dataScopeShared.getData('DATA_UPDATE');
        $scope.addForm = true;

        $scope.formData = {
            ID_KAB: null,
            PROVINSI_KAB: null,
            NAMA_KAB: null
        };

        $http.get($scope.mainURI + '/form').then(callbackForm, notificationService.errorCallback);

        function callbackForm(response) {
            $http.get(response.data.uri.provinsi).then(callbackFormData, notificationService.errorCallback);
        }

        function callbackFormData(response) {
            $scope.dataAutocomplete = response.data;
            $scope.PROVINSI_KAB = formHelper.autocomplete($scope);

            if ($scope.dataUpdate === null || typeof $scope.dataUpdate === 'undefined')
                formReady();
            else
                getData();
        }

        function getData() {
            $http.post($scope.mainURI + '/view', $scope.dataUpdate).then(callbackSuccessData, notificationService.errorCallback);
        }

        function callbackSuccessData(response) {
            angular.forEach($scope.PROVINSI_KAB.dataAll, function (value, key) {
                if (parseInt(response.data.PROVINSI_KAB) === parseInt(value.key)) {
                    $scope.PROVINSI_KAB.selectedItem = value;
                }
            });

            $scope.formData.ID_KAB = response.data.ID_KAB;
            $scope.formData.NAMA_KAB = response.data.NAMA_KAB;
            $scope.formData.PROVINSI_KAB = response.data.PROVINSI_KAB;

            $scope.addForm = false;

            formReady();
        }

        function formReady() {
            $scope.ajaxRunning = false;
        }

        $scope.cancelSumbit = function () {
            dataScopeShared.addData('DATA_UPDATE', null);
            $mdDialog.cancel();
        };

        $scope.saveSubmit = function () {
            if ($scope.form.PROVINSI_KAB.$valid && $scope.form.NAMA_KAB.$valid) {
                $scope.ajaxRunning = true;

                $http.post($scope.mainURI + '/save', $scope.formData).then(callbackSuccessSaving, notificationService.errorCallback);
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function callbackSuccessSaving(response) {
            $scope.ajaxRunning = false;
            $mdDialog.hide(response.data.notification);
            dataScopeShared.addData('DATA_UPDATE', null);
        }

        $scope.$watch('PROVINSI_KAB.selectedItem', function (selectedItem) {
            if (typeof selectedItem === 'undefined' || selectedItem.key === null)
                $scope.formData.PROVINSI_KAB = null;
            else
                $scope.formData.PROVINSI_KAB = selectedItem.key;
        });
    });

    angular.module('mainApp').controller('mdTAController', function ($scope, formHelper, notificationService, $routeParams, $http, $mdDialog, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.ajaxRunning = true;
        $scope.dataUpdate = dataScopeShared.getData('DATA_UPDATE');
        $scope.addForm = true;

        $scope.formData = {
            ID_TA: null,
            NAMA_TA: null,
            TANGGAL_MULAI_TA: null,
            TANGGAL_AKHIR_TA: null,
            AKTIF_TA: null,
            KETERANGAN_TA: null,
        };

        $http.get($scope.mainURI + '/form').then(callbackForm, notificationService.errorCallback);

        function callbackForm(response) {
            callbackFormData(response);
        }

        function callbackFormData(response) {
            $scope.dataAKTIF_TA = response.data.dataAKTIF_TA;

            if ($scope.dataUpdate === null || typeof $scope.dataUpdate === 'undefined')
                formReady();
            else
                getData();
        }

        function getData() {
            $http.post($scope.mainURI + '/view', $scope.dataUpdate).then(callbackSuccessData, notificationService.errorCallback);
        }

        function callbackSuccessData(response) {
            $scope.formData.ID_TA = response.data.ID_TA;
            $scope.formData.NAMA_TA = response.data.NAMA_TA;
            $scope.formData.TANGGAL_MULAI_TA = response.data.TANGGAL_MULAI_TA;
            $scope.formData.TANGGAL_AKHIR_TA = response.data.TANGGAL_AKHIR_TA;
            $scope.formData.AKTIF_TA = response.data.AKTIF_TA;
            $scope.formData.KETERANGAN_TA = response.data.KETERANGAN_TA;

            $scope.addForm = false;

            formReady();
        }

        function formReady() {
            $scope.ajaxRunning = false;
        }

        $scope.cancelSumbit = function () {
            dataScopeShared.addData('DATA_UPDATE', null);
            $mdDialog.cancel();
        };

        $scope.saveSubmit = function () {
            if ($scope.form.$valid) {
                $scope.ajaxRunning = true;

                $http.post($scope.mainURI + '/save', $scope.formData).then(callbackSuccessSaving, notificationService.errorCallback);
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function callbackSuccessSaving(response) {
            $scope.ajaxRunning = false;
            $mdDialog.hide(response.data.notification);
            dataScopeShared.addData('DATA_UPDATE', null);
        }
    });

    angular.module('mainApp').controller('mdPAController', function ($scope, formHelper, notificationService, $routeParams, $http, $mdDialog, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.ajaxRunning = true;
        $scope.dataUpdate = dataScopeShared.getData('DATA_UPDATE');
        $scope.addForm = true;

        $scope.formData = {
            ID_CAWU: null,
            NAMA_CAWU: null,
            AKTIF_CAWU: null,
            KETERANGAN_CAWU: null,
        };

        $http.get($scope.mainURI + '/form').then(callbackForm, notificationService.errorCallback);

        function callbackForm(response) {
            callbackFormData(response);
        }

        function callbackFormData(response) {
            $scope.dataAKTIF_CAWU = response.data.dataAKTIF_CAWU;

            if ($scope.dataUpdate === null || typeof $scope.dataUpdate === 'undefined')
                formReady();
            else
                getData();
        }

        function getData() {
            $http.post($scope.mainURI + '/view', $scope.dataUpdate).then(callbackSuccessData, notificationService.errorCallback);
        }

        function callbackSuccessData(response) {
            $scope.formData.ID_CAWU = response.data.ID_CAWU;
            $scope.formData.NAMA_CAWU = response.data.NAMA_CAWU;
            $scope.formData.AKTIF_CAWU = response.data.AKTIF_CAWU;
            $scope.formData.KETERANGAN_CAWU = response.data.KETERANGAN_CAWU;

            $scope.addForm = false;

            formReady();
        }

        function formReady() {
            $scope.ajaxRunning = false;
        }

        $scope.cancelSumbit = function () {
            dataScopeShared.addData('DATA_UPDATE', null);
            $mdDialog.cancel();
        };

        $scope.saveSubmit = function () {
            if ($scope.form.$valid) {
                $scope.ajaxRunning = true;

                $http.post($scope.mainURI + '/save', $scope.formData).then(callbackSuccessSaving, notificationService.errorCallback);
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function callbackSuccessSaving(response) {
            $scope.ajaxRunning = false;
            $mdDialog.hide(response.data.notification);
            dataScopeShared.addData('DATA_UPDATE', null);
        }
    });

    angular.module('mainApp').controller('akadKelasController', function ($scope, formHelper, notificationService, $routeParams, $http, $mdDialog, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.ajaxRunning = true;
        $scope.dataUpdate = dataScopeShared.getData('DATA_UPDATE');
        $scope.addForm = true;

        $scope.formData = {
            ID_KELAS: null,
            KEGIATAN_KELAS: null,
            NAMA_KELAS: null,
            KETERANGAN_KELAS: null,
            KODE_EMIS_KELAS: null,
        };

        $http.get($scope.mainURI + '/form').then(callbackForm, notificationService.errorCallback);

        function callbackForm(response) {
            callbackFormData(response);
        }

        function callbackFormData(response) {
            $scope.dataKEGIATAN_KELAS = response.data.dataKEGIATAN_KELAS;

            if ($scope.dataUpdate === null || typeof $scope.dataUpdate === 'undefined')
                formReady();
            else
                getData();
        }

        function getData() {
            $http.post($scope.mainURI + '/view', $scope.dataUpdate).then(callbackSuccessData, notificationService.errorCallback);
        }

        function callbackSuccessData(response) {
            $scope.formData.ID_KELAS = response.data.ID_KELAS;
            $scope.formData.KEGIATAN_KELAS = response.data.KEGIATAN_KELAS;
            $scope.formData.NAMA_KELAS = response.data.NAMA_KELAS;
            $scope.formData.KETERANGAN_KELAS = response.data.KETERANGAN_KELAS;
            $scope.formData.KODE_EMIS_KELAS = response.data.KODE_EMIS_KELAS;

            $scope.addForm = false;

            formReady();
        }

        function formReady() {
            $scope.ajaxRunning = false;
        }

        $scope.cancelSumbit = function () {
            dataScopeShared.addData('DATA_UPDATE', null);
            $mdDialog.cancel();
        };

        $scope.saveSubmit = function () {
            if ($scope.form.$valid) {
                $scope.ajaxRunning = true;

                $http.post($scope.mainURI + '/save', $scope.formData).then(callbackSuccessSaving, notificationService.errorCallback);
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function callbackSuccessSaving(response) {
            $scope.ajaxRunning = false;
            $mdDialog.hide(response.data.notification);
            dataScopeShared.addData('DATA_UPDATE', null);
        }
    });

    angular.module('mainApp').controller('akadKegiatanController', function ($scope, formHelper, notificationService, $routeParams, $http, $mdDialog, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.ajaxRunning = true;
        $scope.dataUpdate = dataScopeShared.getData('DATA_UPDATE');
        $scope.addForm = true;

        $scope.formData = {
            ID_KEGIATAN: null,
            NAMA_KEGIATAN: null,
            KETERANGAN_KEGIATAN: null,
            KODE_EMIS_KEGIATAN: null,
        };

        $http.get($scope.mainURI + '/form').then(callbackForm, notificationService.errorCallback);

        function callbackForm(response) {
            callbackFormData(response);
        }

        function callbackFormData(response) {
            if ($scope.dataUpdate === null || typeof $scope.dataUpdate === 'undefined')
                formReady();
            else
                getData();
        }

        function getData() {
            $http.post($scope.mainURI + '/view', $scope.dataUpdate).then(callbackSuccessData, notificationService.errorCallback);
        }

        function callbackSuccessData(response) {
            $scope.formData.ID_KEGIATAN = response.data.ID_KEGIATAN;
            $scope.formData.NAMA_KEGIATAN = response.data.NAMA_KEGIATAN;
            $scope.formData.KETERANGAN_KEGIATAN = response.data.KETERANGAN_KEGIATAN;
            $scope.formData.KODE_EMIS_KEGIATAN = response.data.KODE_EMIS_KEGIATAN;

            $scope.addForm = false;

            formReady();
        }

        function formReady() {
            $scope.ajaxRunning = false;
        }

        $scope.cancelSumbit = function () {
            dataScopeShared.addData('DATA_UPDATE', null);
            $mdDialog.cancel();
        };

        $scope.saveSubmit = function () {
            if ($scope.form.$valid) {
                $scope.ajaxRunning = true;

                $http.post($scope.mainURI + '/save', $scope.formData).then(callbackSuccessSaving, notificationService.errorCallback);
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function callbackSuccessSaving(response) {
            $scope.ajaxRunning = false;
            $mdDialog.hide(response.data.notification);
            dataScopeShared.addData('DATA_UPDATE', null);
        }
    });

    angular.module('mainApp').controller('akadGedungController', function ($scope, formHelper, notificationService, $routeParams, $http, $mdDialog, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.ajaxRunning = true;
        $scope.dataUpdate = dataScopeShared.getData('DATA_UPDATE');
        $scope.addForm = true;

        $scope.formData = {
            ID_GEDUNG: null,
            NAMA_GEDUNG: null,
            KETERANGAN_GEDUNG: null,
        };

        $http.get($scope.mainURI + '/form').then(callbackForm, notificationService.errorCallback);

        function callbackForm(response) {
            callbackFormData(response);
        }

        function callbackFormData(response) {
            if ($scope.dataUpdate === null || typeof $scope.dataUpdate === 'undefined')
                formReady();
            else
                getData();
        }

        function getData() {
            $http.post($scope.mainURI + '/view', $scope.dataUpdate).then(callbackSuccessData, notificationService.errorCallback);
        }

        function callbackSuccessData(response) {
            $scope.formData.ID_GEDUNG = response.data.ID_GEDUNG;
            $scope.formData.NAMA_GEDUNG = response.data.NAMA_GEDUNG;
            $scope.formData.KETERANGAN_GEDUNG = response.data.KETERANGAN_GEDUNG;

            $scope.addForm = false;

            formReady();
        }

        function formReady() {
            $scope.ajaxRunning = false;
        }

        $scope.cancelSumbit = function () {
            dataScopeShared.addData('DATA_UPDATE', null);
            $mdDialog.cancel();
        };

        $scope.saveSubmit = function () {
            if ($scope.form.$valid) {
                $scope.ajaxRunning = true;

                $http.post($scope.mainURI + '/save', $scope.formData).then(callbackSuccessSaving, notificationService.errorCallback);
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function callbackSuccessSaving(response) {
            $scope.ajaxRunning = false;
            $mdDialog.hide(response.data.notification);
            dataScopeShared.addData('DATA_UPDATE', null);
        }
    });

    angular.module('mainApp').controller('akadRuangController', function ($scope, formHelper, notificationService, $routeParams, $http, $mdDialog, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.ajaxRunning = true;
        $scope.dataUpdate = dataScopeShared.getData('DATA_UPDATE');
        $scope.addForm = true;

        $scope.formData = {
            ID_RUANG: null,
            GEDUNG_RUANG: null,
            NAMA_RUANG: null,
            KETERANGAN_RUANG: null,
        };

        $http.get($scope.mainURI + '/form').then(callbackForm, notificationService.errorCallback);

        function callbackForm(response) {
            callbackFormData(response);
        }

        function callbackFormData(response) {
            $scope.dataGEDUNG_RUANG = response.data.dataGEDUNG_RUANG;

            if ($scope.dataUpdate === null || typeof $scope.dataUpdate === 'undefined')
                formReady();
            else
                getData();
        }

        function getData() {
            $http.post($scope.mainURI + '/view', $scope.dataUpdate).then(callbackSuccessData, notificationService.errorCallback);
        }

        function callbackSuccessData(response) {
            $scope.formData.ID_RUANG = response.data.ID_RUANG;
            $scope.formData.GEDUNG_RUANG = response.data.GEDUNG_RUANG;
            $scope.formData.NAMA_RUANG = response.data.NAMA_RUANG;
            $scope.formData.KETERANGAN_RUANG = response.data.KETERANGAN_RUANG;

            $scope.addForm = false;

            formReady();
        }

        function formReady() {
            $scope.ajaxRunning = false;
        }

        $scope.cancelSumbit = function () {
            dataScopeShared.addData('DATA_UPDATE', null);
            $mdDialog.cancel();
        };

        $scope.saveSubmit = function () {
            if ($scope.form.$valid) {
                $scope.ajaxRunning = true;

                $http.post($scope.mainURI + '/save', $scope.formData).then(callbackSuccessSaving, notificationService.errorCallback);
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function callbackSuccessSaving(response) {
            $scope.ajaxRunning = false;
            $mdDialog.hide(response.data.notification);
            dataScopeShared.addData('DATA_UPDATE', null);
        }
    });

    angular.module('mainApp').controller('mdRombelController', function ($scope, formHelper, notificationService, $routeParams, $http, $mdDialog, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.ajaxRunning = true;
        $scope.dataUpdate = dataScopeShared.getData('DATA_UPDATE');
        $scope.addForm = true;

        $scope.formData = {
            ID_ROMBEL: null,
            NAMA_ROMBEL: null,
            KELAS_ROMBEL: null,
            RUANG_ROMBEL: null,
            JURUSAN_ROMBEL: null,
            KETERANGAN_ROMBEL: null,
        };

        $http.get($scope.mainURI + '/form').then(callbackForm, notificationService.errorCallback);

        function callbackForm(response) {
            callbackFormData(response);
        }

        function callbackFormData(response) {
            $scope.dataKELAS_ROMBEL = response.data.dataKELAS_ROMBEL;
            $scope.dataRUANG_ROMBEL = response.data.dataRUANG_ROMBEL;
            $scope.dataJURUSAN_ROMBEL = response.data.dataJURUSAN_ROMBEL;

            if ($scope.dataUpdate === null || typeof $scope.dataUpdate === 'undefined')
                formReady();
            else
                getData();
        }

        function getData() {
            $http.post($scope.mainURI + '/view', $scope.dataUpdate).then(callbackSuccessData, notificationService.errorCallback);
        }

        function callbackSuccessData(response) {
            $scope.formData.ID_ROMBEL = response.data.ID_ROMBEL;
            $scope.formData.NAMA_ROMBEL = response.data.NAMA_ROMBEL;
            $scope.formData.KELAS_ROMBEL = response.data.KELAS_ROMBEL;
            $scope.formData.RUANG_ROMBEL = response.data.RUANG_ROMBEL;
            $scope.formData.JURUSAN_ROMBEL = response.data.JURUSAN_ROMBEL;
            $scope.formData.KETERANGAN_ROMBEL = response.data.KETERANGAN_ROMBEL;

            $scope.addForm = false;

            formReady();
        }

        function formReady() {
            $scope.ajaxRunning = false;
        }

        $scope.cancelSumbit = function () {
            dataScopeShared.addData('DATA_UPDATE', null);
            $mdDialog.cancel();
        };

        $scope.saveSubmit = function () {
            if ($scope.form.$valid) {
                $scope.ajaxRunning = true;

                $http.post($scope.mainURI + '/save', $scope.formData).then(callbackSuccessSaving, notificationService.errorCallback);
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function callbackSuccessSaving(response) {
            $scope.ajaxRunning = false;
            $mdDialog.hide(response.data.notification);
            dataScopeShared.addData('DATA_UPDATE', null);
        }
    });

    angular.module('mainApp').controller('mdJkController', function ($scope, formHelper, notificationService, $routeParams, $http, $mdDialog, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.ajaxRunning = true;
        $scope.dataUpdate = dataScopeShared.getData('DATA_UPDATE');
        $scope.addForm = true;

        $scope.formData = {
            ID_JK: null,
            NAMA_JK: null,
            KODE_EMIS_JK: null,
        };

        $http.get($scope.mainURI + '/form').then(callbackForm, notificationService.errorCallback);

        function callbackForm(response) {
            callbackFormData(response);
        }

        function callbackFormData(response) {
            if ($scope.dataUpdate === null || typeof $scope.dataUpdate === 'undefined')
                formReady();
            else
                getData();
        }

        function getData() {
            $http.post($scope.mainURI + '/view', $scope.dataUpdate).then(callbackSuccessData, notificationService.errorCallback);
        }

        function callbackSuccessData(response) {
            $scope.formData.ID_JK = response.data.ID_JK;
            $scope.formData.NAMA_JK = response.data.NAMA_JK;
            $scope.formData.KODE_EMIS_JK = response.data.KODE_EMIS_JK;

            $scope.addForm = false;

            formReady();
        }

        function formReady() {
            $scope.ajaxRunning = false;
        }

        $scope.cancelSumbit = function () {
            dataScopeShared.addData('DATA_UPDATE', null);
            $mdDialog.cancel();
        };

        $scope.saveSubmit = function () {
            if ($scope.form.$valid) {
                $scope.ajaxRunning = true;

                $http.post($scope.mainURI + '/save', $scope.formData).then(callbackSuccessSaving, notificationService.errorCallback);
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function callbackSuccessSaving(response) {
            $scope.ajaxRunning = false;
            $mdDialog.hide(response.data.notification);
            dataScopeShared.addData('DATA_UPDATE', null);
        }
    });

    angular.module('mainApp').controller('mdHubunganController', function ($scope, formHelper, notificationService, $routeParams, $http, $mdDialog, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.ajaxRunning = true;
        $scope.dataUpdate = dataScopeShared.getData('DATA_UPDATE');
        $scope.addForm = true;

        $scope.formData = {
            ID_HUB: null,
            NAMA_HUB: null,
            KODE_EMIS_HUB: null,
        };

        $http.get($scope.mainURI + '/form').then(callbackForm, notificationService.errorCallback);

        function callbackForm(response) {
            callbackFormData(response);
        }

        function callbackFormData(response) {
            if ($scope.dataUpdate === null || typeof $scope.dataUpdate === 'undefined')
                formReady();
            else
                getData();
        }

        function getData() {
            $http.post($scope.mainURI + '/view', $scope.dataUpdate).then(callbackSuccessData, notificationService.errorCallback);
        }

        function callbackSuccessData(response) {
            $scope.formData = response.data;

            $scope.addForm = false;

            formReady();
        }

        function formReady() {
            $scope.ajaxRunning = false;
        }

        $scope.cancelSumbit = function () {
            dataScopeShared.addData('DATA_UPDATE', null);
            $mdDialog.cancel();
        };

        $scope.saveSubmit = function () {
            if ($scope.form.$valid) {
                $scope.ajaxRunning = true;

                $http.post($scope.mainURI + '/save', $scope.formData).then(callbackSuccessSaving, notificationService.errorCallback);
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function callbackSuccessSaving(response) {
            $scope.ajaxRunning = false;
            $mdDialog.hide(response.data.notification);
            dataScopeShared.addData('DATA_UPDATE', null);
        }
    });

    angular.module('mainApp').controller('mdAgamaController', function ($scope, formHelper, notificationService, $routeParams, $http, $mdDialog, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.ajaxRunning = true;
        $scope.dataUpdate = dataScopeShared.getData('DATA_UPDATE');
        $scope.addForm = true;

        $scope.formData = {
            ID_AGAMA: null,
            NAMA_AGAMA: null,
            KODE_EMIS_AGAMA: null,
        };

        $http.get($scope.mainURI + '/form').then(callbackForm, notificationService.errorCallback);

        function callbackForm(response) {
            callbackFormData(response);
        }

        function callbackFormData(response) {
            if ($scope.dataUpdate === null || typeof $scope.dataUpdate === 'undefined')
                formReady();
            else
                getData();
        }

        function getData() {
            $http.post($scope.mainURI + '/view', $scope.dataUpdate).then(callbackSuccessData, notificationService.errorCallback);
        }

        function callbackSuccessData(response) {
            $scope.formData = response.data;

            $scope.addForm = false;

            formReady();
        }

        function formReady() {
            $scope.ajaxRunning = false;
        }

        $scope.cancelSumbit = function () {
            dataScopeShared.addData('DATA_UPDATE', null);
            $mdDialog.cancel();
        };

        $scope.saveSubmit = function () {
            if ($scope.form.$valid) {
                $scope.ajaxRunning = true;

                $http.post($scope.mainURI + '/save', $scope.formData).then(callbackSuccessSaving, notificationService.errorCallback);
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function callbackSuccessSaving(response) {
            $scope.ajaxRunning = false;
            $mdDialog.hide(response.data.notification);
            dataScopeShared.addData('DATA_UPDATE', null);
        }
    });

    angular.module('mainApp').controller('mdJenjangPendidikanController', function ($scope, formHelper, notificationService, $routeParams, $http, $mdDialog, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.ajaxRunning = true;
        $scope.dataUpdate = dataScopeShared.getData('DATA_UPDATE');
        $scope.addForm = true;

        $scope.formData = {
            ID_JP: null,
            NAMA_JP: null,
            KODE_EMIS_JP: null,
        };

        $http.get($scope.mainURI + '/form').then(callbackForm, notificationService.errorCallback);

        function callbackForm(response) {
            callbackFormData(response);
        }

        function callbackFormData(response) {
            if ($scope.dataUpdate === null || typeof $scope.dataUpdate === 'undefined')
                formReady();
            else
                getData();
        }

        function getData() {
            $http.post($scope.mainURI + '/view', $scope.dataUpdate).then(callbackSuccessData, notificationService.errorCallback);
        }

        function callbackSuccessData(response) {
            $scope.formData.ID_JP = response.data.ID_JP;
            $scope.formData.NAMA_JP = response.data.NAMA_JP;
            $scope.formData.KODE_EMIS_JP = response.data.KODE_EMIS_JP;

            $scope.addForm = false;

            formReady();
        }

        function formReady() {
            $scope.ajaxRunning = false;
        }

        $scope.cancelSumbit = function () {
            dataScopeShared.addData('DATA_UPDATE', null);
            $mdDialog.cancel();
        };

        $scope.saveSubmit = function () {
            if ($scope.form.$valid) {
                $scope.ajaxRunning = true;

                $http.post($scope.mainURI + '/save', $scope.formData).then(callbackSuccessSaving, notificationService.errorCallback);
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function callbackSuccessSaving(response) {
            $scope.ajaxRunning = false;
            $mdDialog.hide(response.data.notification);
            dataScopeShared.addData('DATA_UPDATE', null);
        }
    });

    angular.module('mainApp').controller('mdAsalSantriController', function ($scope, formHelper, notificationService, $routeParams, $http, $mdDialog, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.ajaxRunning = true;
        $scope.dataUpdate = dataScopeShared.getData('DATA_UPDATE');
        $scope.addForm = true;

        $scope.formData = {
            ID_ASSAN: null,
            NAMA_ASSAN: null,
            KODE_EMIS_ASSAN: null,
        };

        $http.get($scope.mainURI + '/form').then(callbackForm, notificationService.errorCallback);

        function callbackForm(response) {
            callbackFormData(response);
        }

        function callbackFormData(response) {
            if ($scope.dataUpdate === null || typeof $scope.dataUpdate === 'undefined')
                formReady();
            else
                getData();
        }

        function getData() {
            $http.post($scope.mainURI + '/view', $scope.dataUpdate).then(callbackSuccessData, notificationService.errorCallback);
        }

        function callbackSuccessData(response) {
            $scope.formData.ID_ASSAN = response.data.ID_ASSAN;
            $scope.formData.NAMA_ASSAN = response.data.NAMA_ASSAN;
            $scope.formData.KODE_EMIS_ASSAN = response.data.KODE_EMIS_ASSAN;

            $scope.addForm = false;

            formReady();
        }

        function formReady() {
            $scope.ajaxRunning = false;
        }

        $scope.cancelSumbit = function () {
            dataScopeShared.addData('DATA_UPDATE', null);
            $mdDialog.cancel();
        };

        $scope.saveSubmit = function () {
            if ($scope.form.$valid) {
                $scope.ajaxRunning = true;

                $http.post($scope.mainURI + '/save', $scope.formData).then(callbackSuccessSaving, notificationService.errorCallback);
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function callbackSuccessSaving(response) {
            $scope.ajaxRunning = false;
            $mdDialog.hide(response.data.notification);
            dataScopeShared.addData('DATA_UPDATE', null);
        }
    });

    angular.module('mainApp').controller('mdJurusanController', function ($scope, formHelper, notificationService, $routeParams, $http, $mdDialog, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.ajaxRunning = true;
        $scope.dataUpdate = dataScopeShared.getData('DATA_UPDATE');
        $scope.addForm = true;

        $scope.formData = {
            ID_JURUSAN: null,
            NAMA_JURUSAN: null,
            KODE_EMIS_JURUSAN: null,
        };

        $http.get($scope.mainURI + '/form').then(callbackForm, notificationService.errorCallback);

        function callbackForm(response) {
            callbackFormData(response);
        }

        function callbackFormData(response) {
            if ($scope.dataUpdate === null || typeof $scope.dataUpdate === 'undefined')
                formReady();
            else
                getData();
        }

        function getData() {
            $http.post($scope.mainURI + '/view', $scope.dataUpdate).then(callbackSuccessData, notificationService.errorCallback);
        }

        function callbackSuccessData(response) {
            $scope.formData = response.data;

            $scope.addForm = false;

            formReady();
        }

        function formReady() {
            $scope.ajaxRunning = false;
        }

        $scope.cancelSumbit = function () {
            dataScopeShared.addData('DATA_UPDATE', null);
            $mdDialog.cancel();
        };

        $scope.saveSubmit = function () {
            if ($scope.form.$valid) {
                $scope.ajaxRunning = true;

                $http.post($scope.mainURI + '/save', $scope.formData).then(callbackSuccessSaving, notificationService.errorCallback);
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function callbackSuccessSaving(response) {
            $scope.ajaxRunning = false;
            $mdDialog.hide(response.data.notification);
            dataScopeShared.addData('DATA_UPDATE', null);
        }
    });


    angular.module('mainApp').controller('mdKondisiController', function ($scope, formHelper, notificationService, $routeParams, $http, $mdDialog, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.ajaxRunning = true;
        $scope.dataUpdate = dataScopeShared.getData('DATA_UPDATE');
        $scope.addForm = true;

        $scope.formData = {
            ID_KONDISI: null,
            NAMA_KONDISI: null,
        };

        $http.get($scope.mainURI + '/form').then(callbackForm, notificationService.errorCallback);

        function callbackForm(response) {
            callbackFormData(response);
        }

        function callbackFormData(response) {
            if ($scope.dataUpdate === null || typeof $scope.dataUpdate === 'undefined')
                formReady();
            else
                getData();
        }

        function getData() {
            $http.post($scope.mainURI + '/view', $scope.dataUpdate).then(callbackSuccessData, notificationService.errorCallback);
        }

        function callbackSuccessData(response) {
            $scope.formData.ID_KONDISI = response.data.ID_KONDISI;
            $scope.formData.NAMA_KONDISI = response.data.NAMA_KONDISI;

            $scope.addForm = false;

            formReady();
        }

        function formReady() {
            $scope.ajaxRunning = false;
        }

        $scope.cancelSumbit = function () {
            dataScopeShared.addData('DATA_UPDATE', null);
            $mdDialog.cancel();
        };

        $scope.saveSubmit = function () {
            if ($scope.form.$valid) {
                $scope.ajaxRunning = true;

                $http.post($scope.mainURI + '/save', $scope.formData).then(callbackSuccessSaving, notificationService.errorCallback);
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function callbackSuccessSaving(response) {
            $scope.ajaxRunning = false;
            $mdDialog.hide(response.data.notification);
            dataScopeShared.addData('DATA_UPDATE', null);
        }
    });

    angular.module('mainApp').controller('mdStatusHidupController', function ($scope, formHelper, notificationService, $routeParams, $http, $mdDialog, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.ajaxRunning = true;
        $scope.dataUpdate = dataScopeShared.getData('DATA_UPDATE');
        $scope.addForm = true;

        $scope.formData = {
            ID_SO: null,
            NAMA_SO: null,
            KODE_EMIS_SO: null,
        };

        $http.get($scope.mainURI + '/form').then(callbackForm, notificationService.errorCallback);

        function callbackForm(response) {
            callbackFormData(response);
        }

        function callbackFormData(response) {
            if ($scope.dataUpdate === null || typeof $scope.dataUpdate === 'undefined')
                formReady();
            else
                getData();
        }

        function getData() {
            $http.post($scope.mainURI + '/view', $scope.dataUpdate).then(callbackSuccessData, notificationService.errorCallback);
        }

        function callbackSuccessData(response) {
            $scope.formData.ID_SO = response.data.ID_SO;
            $scope.formData.NAMA_SO = response.data.NAMA_SO;
            $scope.formData.KODE_EMIS_SO = response.data.KODE_EMIS_SO;

            $scope.addForm = false;

            formReady();
        }

        function formReady() {
            $scope.ajaxRunning = false;
        }

        $scope.cancelSumbit = function () {
            dataScopeShared.addData('DATA_UPDATE', null);
            $mdDialog.cancel();
        };

        $scope.saveSubmit = function () {
            if ($scope.form.$valid) {
                $scope.ajaxRunning = true;

                $http.post($scope.mainURI + '/save', $scope.formData).then(callbackSuccessSaving, notificationService.errorCallback);
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function callbackSuccessSaving(response) {
            $scope.ajaxRunning = false;
            $mdDialog.hide(response.data.notification);
            dataScopeShared.addData('DATA_UPDATE', null);
        }
    });

    angular.module('mainApp').controller('mdPekerjaanController', function ($scope, formHelper, notificationService, $routeParams, $http, $mdDialog, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.ajaxRunning = true;
        $scope.dataUpdate = dataScopeShared.getData('DATA_UPDATE');
        $scope.addForm = true;

        $scope.formData = {
            ID_JENPEK: null,
            NAMA_JENPEK: null,
            KODE_EMIS_JENPEK: null,
        };

        $http.get($scope.mainURI + '/form').then(callbackForm, notificationService.errorCallback);

        function callbackForm(response) {
            callbackFormData(response);
        }

        function callbackFormData(response) {
            if ($scope.dataUpdate === null || typeof $scope.dataUpdate === 'undefined')
                formReady();
            else
                getData();
        }

        function getData() {
            $http.post($scope.mainURI + '/view', $scope.dataUpdate).then(callbackSuccessData, notificationService.errorCallback);
        }

        function callbackSuccessData(response) {
            $scope.formData.ID_JENPEK = response.data.ID_JENPEK;
            $scope.formData.NAMA_JENPEK = response.data.NAMA_JENPEK;
            $scope.formData.KODE_EMIS_JENPEK = response.data.KODE_EMIS_JENPEK;

            $scope.addForm = false;

            formReady();
        }

        function formReady() {
            $scope.ajaxRunning = false;
        }

        $scope.cancelSumbit = function () {
            dataScopeShared.addData('DATA_UPDATE', null);
            $mdDialog.cancel();
        };

        $scope.saveSubmit = function () {
            if ($scope.form.$valid) {
                $scope.ajaxRunning = true;

                $http.post($scope.mainURI + '/save', $scope.formData).then(callbackSuccessSaving, notificationService.errorCallback);
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function callbackSuccessSaving(response) {
            $scope.ajaxRunning = false;
            $mdDialog.hide(response.data.notification);
            dataScopeShared.addData('DATA_UPDATE', null);
        }
    });

    angular.module('mainApp').controller('mdPenghasilanController', function ($scope, formHelper, notificationService, $routeParams, $http, $mdDialog, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.ajaxRunning = true;
        $scope.dataUpdate = dataScopeShared.getData('DATA_UPDATE');
        $scope.addForm = true;

        $scope.formData = {
            ID_HASIL: null,
            NAMA_HASIL: null,
            KODE_EMIS_HASIL: null,
        };

        $http.get($scope.mainURI + '/form').then(callbackForm, notificationService.errorCallback);

        function callbackForm(response) {
            callbackFormData(response);
        }

        function callbackFormData(response) {
            if ($scope.dataUpdate === null || typeof $scope.dataUpdate === 'undefined')
                formReady();
            else
                getData();
        }

        function getData() {
            $http.post($scope.mainURI + '/view', $scope.dataUpdate).then(callbackSuccessData, notificationService.errorCallback);
        }

        function callbackSuccessData(response) {
            $scope.formData.ID_HASIL = response.data.ID_HASIL;
            $scope.formData.NAMA_HASIL = response.data.NAMA_HASIL;
            $scope.formData.KODE_EMIS_HASIL = response.data.KODE_EMIS_HASIL;

            $scope.addForm = false;

            formReady();
        }

        function formReady() {
            $scope.ajaxRunning = false;
        }

        $scope.cancelSumbit = function () {
            dataScopeShared.addData('DATA_UPDATE', null);
            $mdDialog.cancel();
        };

        $scope.saveSubmit = function () {
            if ($scope.form.$valid) {
                $scope.ajaxRunning = true;

                $http.post($scope.mainURI + '/save', $scope.formData).then(callbackSuccessSaving, notificationService.errorCallback);
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function callbackSuccessSaving(response) {
            $scope.ajaxRunning = false;
            $mdDialog.hide(response.data.notification);
            dataScopeShared.addData('DATA_UPDATE', null);
        }
    });

    angular.module('mainApp').controller('mdTempatTinggalController', function ($scope, formHelper, notificationService, $routeParams, $http, $mdDialog, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.ajaxRunning = true;
        $scope.dataUpdate = dataScopeShared.getData('DATA_UPDATE');
        $scope.addForm = true;

        $scope.formData = {
            ID_TEMTING: null,
            NAMA_TEMTING: null,
            KODE_EMIS_TEMTING: null,
        };

        $http.get($scope.mainURI + '/form').then(callbackForm, notificationService.errorCallback);

        function callbackForm(response) {
            callbackFormData(response);
        }

        function callbackFormData(response) {
            if ($scope.dataUpdate === null || typeof $scope.dataUpdate === 'undefined')
                formReady();
            else
                getData();
        }

        function getData() {
            $http.post($scope.mainURI + '/view', $scope.dataUpdate).then(callbackSuccessData, notificationService.errorCallback);
        }

        function callbackSuccessData(response) {
            $scope.formData.ID_TEMTING = response.data.ID_TEMTING;
            $scope.formData.NAMA_TEMTING = response.data.NAMA_TEMTING;
            $scope.formData.KODE_EMIS_TEMTING = response.data.KODE_EMIS_TEMTING;

            $scope.addForm = false;

            formReady();
        }

        function formReady() {
            $scope.ajaxRunning = false;
        }

        $scope.cancelSumbit = function () {
            dataScopeShared.addData('DATA_UPDATE', null);
            $mdDialog.cancel();
        };

        $scope.saveSubmit = function () {
            if ($scope.form.$valid) {
                $scope.ajaxRunning = true;

                $http.post($scope.mainURI + '/save', $scope.formData).then(callbackSuccessSaving, notificationService.errorCallback);
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function callbackSuccessSaving(response) {
            $scope.ajaxRunning = false;
            $mdDialog.hide(response.data.notification);
            dataScopeShared.addData('DATA_UPDATE', null);
        }
    });

    angular.module('mainApp').controller('mdStatusKeluarController', function ($scope, formHelper, notificationService, $routeParams, $http, $mdDialog, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.ajaxRunning = true;
        $scope.dataUpdate = dataScopeShared.getData('DATA_UPDATE');
        $scope.addForm = true;

        $scope.formData = {
            ID_MUTASI: null,
            NAMA_MUTASI: null,
        };

        $http.get($scope.mainURI + '/form').then(callbackForm, notificationService.errorCallback);

        function callbackForm(response) {
            callbackFormData(response);
        }

        function callbackFormData(response) {
            if ($scope.dataUpdate === null || typeof $scope.dataUpdate === 'undefined')
                formReady();
            else
                getData();
        }

        function getData() {
            $http.post($scope.mainURI + '/view', $scope.dataUpdate).then(callbackSuccessData, notificationService.errorCallback);
        }

        function callbackSuccessData(response) {
            $scope.formData.ID_MUTASI = response.data.ID_MUTASI;
            $scope.formData.NAMA_MUTASI = response.data.NAMA_MUTASI;

            $scope.addForm = false;

            formReady();
        }

        function formReady() {
            $scope.ajaxRunning = false;
        }

        $scope.cancelSumbit = function () {
            dataScopeShared.addData('DATA_UPDATE', null);
            $mdDialog.cancel();
        };

        $scope.saveSubmit = function () {
            if ($scope.form.$valid) {
                $scope.ajaxRunning = true;

                $http.post($scope.mainURI + '/save', $scope.formData).then(callbackSuccessSaving, notificationService.errorCallback);
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function callbackSuccessSaving(response) {
            $scope.ajaxRunning = false;
            $mdDialog.hide(response.data.notification);
            dataScopeShared.addData('DATA_UPDATE', null);
        }
    });

    angular.module('mainApp').controller('mdProvinsiController', function ($scope, formHelper, notificationService, $routeParams, $http, $mdDialog, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.ajaxRunning = true;
        $scope.dataUpdate = dataScopeShared.getData('DATA_UPDATE');
        $scope.addForm = true;

        $scope.formData = {
            ID_PROV: null,
            NAMA_PROV: null,
        };

        $http.get($scope.mainURI + '/form').then(callbackForm, notificationService.errorCallback);

        function callbackForm(response) {
            callbackFormData(response);
        }

        function callbackFormData(response) {
            if ($scope.dataUpdate === null || typeof $scope.dataUpdate === 'undefined')
                formReady();
            else
                getData();
        }

        function getData() {
            $http.post($scope.mainURI + '/view', $scope.dataUpdate).then(callbackSuccessData, notificationService.errorCallback);
        }

        function callbackSuccessData(response) {
            $scope.formData.ID_PROV = response.data.ID_PROV;
            $scope.formData.NAMA_PROV = response.data.NAMA_PROV;

            $scope.addForm = false;

            formReady();
        }

        function formReady() {
            $scope.ajaxRunning = false;
        }

        $scope.cancelSumbit = function () {
            dataScopeShared.addData('DATA_UPDATE', null);
            $mdDialog.cancel();
        };

        $scope.saveSubmit = function () {
            if ($scope.form.$valid) {
                $scope.ajaxRunning = true;

                $http.post($scope.mainURI + '/save', $scope.formData).then(callbackSuccessSaving, notificationService.errorCallback);
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function callbackSuccessSaving(response) {
            $scope.ajaxRunning = false;
            $mdDialog.hide(response.data.notification);
            dataScopeShared.addData('DATA_UPDATE', null);
        }
    });

    angular.module('mainApp').controller('dataPSBController', function ($scope, formHelper, notificationService, $routeParams, $http, $mdDialog, dataScopeShared, $q) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.ajaxRunning = true;
        $scope.dataUpdate = dataScopeShared.getData('DATA_UPDATE');
        $scope.addForm = true;
        $scope.dataForm = [];

        $scope.formData = {
            ID_SANTRI: null,
            PSB_KELOMPOK_SANTRI: null,
            NAMA_SANTRI: null,
            JK_SANTRI: null,
            TEMPAT_LAHIR_SANTRI: null,
            TANGGAL_LAHIR_SANTRI: null,
            KECAMATAN_SANTRI: null,
            ALAMAT_SANTRI: null,
            NOHP_SANTRI: null,
            AYAH_NAMA_SANTRI: null,
            ID_TA: dataScopeShared.getData('TA_ACTIVE'),
            KEGIATAN_SANTRI: [],
        };

        $http.get($scope.mainURI + '/form').then(callbackForm, notificationService.errorCallback);

        function callbackForm(response) {
            $scope.dataPSB_KELOMPOK_SANTRI = response.data.kelompok;
            $scope.dataJK_SANTRI = response.data.jk;
            $scope.dataKEGIATAN_SANTRI = response.data.kelas;

            var urlGetDataForm = [];

            urlGetDataForm.push($http.get(response.data.uri.kecamatan));

            $q.all(urlGetDataForm)
                    .then(
                            function (result) {
                                callbackFormData(result);
                            },
                            function (error) {
                                $scope.cancelSumbit();
                            }
                    );
        }

        function callbackFormData(response) {
            $scope.KECAMATAN_SANTRI = {
                dataAutocomplete: response[0].data
            };
            $scope.KECAMATAN_SANTRI = formHelper.autocomplete($scope.KECAMATAN_SANTRI);

            if ($scope.dataUpdate === null || typeof $scope.dataUpdate === 'undefined')
                formReady();
            else
                getData();
        }

        function getData() {
            $http.post($scope.mainURI + '/view', $scope.dataUpdate).then(callbackSuccessData, notificationService.errorCallback);
        }

        function callbackSuccessData(response) {
            angular.forEach($scope.KECAMATAN_SANTRI.dataAll, function (value, key) {
                if (parseInt(response.data.KECAMATAN_SANTRI) === parseInt(value.key)) {
                    $scope.KECAMATAN_SANTRI.selectedItem = value;
                }
            });

            $scope.formData.PSB_KELOMPOK_SANTRI = response.data.PSB_KELOMPOK_SANTRI;
            $scope.formData.ID_SANTRI = response.data.ID_SANTRI;
            $scope.formData.NAMA_SANTRI = response.data.NAMA_SANTRI;
            $scope.formData.JK_SANTRI = response.data.JK_SANTRI;
            $scope.formData.TEMPAT_LAHIR_SANTRI = response.data.TEMPAT_LAHIR_SANTRI;
            $scope.formData.TANGGAL_LAHIR_SANTRI = response.data.TANGGAL_LAHIR_SANTRI;
            $scope.formData.KECAMATAN_SANTRI = response.data.KECAMATAN_SANTRI;
            $scope.formData.ALAMAT_SANTRI = response.data.ALAMAT_SANTRI;
            $scope.formData.NOHP_SANTRI = response.data.NOHP_SANTRI;
            $scope.formData.AYAH_NAMA_SANTRI = response.data.AYAH_NAMA_SANTRI;
            $scope.formData.KEGIATAN_SANTRI = [];

            angular.forEach(response.data.KEGIATAN_SANTRI, function (value, key) {
                $scope.formData.KEGIATAN_SANTRI.push(value);
                $scope.existsKEGIATAN_SANTRI(value);
            });

            $scope.addForm = false;

            formReady();
        }

        $scope.existsKEGIATAN_SANTRI = function (ID) {
            return $scope.formData.KEGIATAN_SANTRI.indexOf(ID) > -1;
        };

        $scope.toggleKEGIATAN_SANTRI = function (ID) {
            var idx = $scope.formData.KEGIATAN_SANTRI.indexOf(ID);

            if (idx > -1)
                $scope.formData.KEGIATAN_SANTRI.splice(idx, 1);
            else
                $scope.formData.KEGIATAN_SANTRI.push(ID);
        };

        $scope.$watch('formData.KEGIATAN_SANTRI', function (KEGIATAN_SANTRI) {
            console.log(KEGIATAN_SANTRI);
        });

        function formReady() {
            $scope.ajaxRunning = false;
        }

        $scope.cancelSumbit = function () {
            dataScopeShared.addData('DATA_UPDATE', null);
            $mdDialog.cancel();
        };

        $scope.saveSubmit = function () {
            if ($scope.formData.KEGIATAN_SANTRI.length === 0) {
                notificationService.toastSimple('Kegiatan santri tidak boleh kosong.');
            } else if ($scope.form.KECAMATAN_SANTRI.$valid
                    && $scope.form.PSB_KELOMPOK_SANTRI.$valid
                    && $scope.form.NAMA_SANTRI.$valid
                    && $scope.form.JK_SANTRI.$valid
                    && $scope.form.TEMPAT_LAHIR_SANTRI.$valid
                    && $scope.form.TANGGAL_LAHIR_SANTRI.$valid
                    && $scope.form.NOHP_SANTRI.$valid
                    && $scope.form.ALAMAT_SANTRI.$valid
                    && $scope.form.AYAH_NAMA_SANTRI.$valid
                    ) {
                $scope.ajaxRunning = true;

                $http.post($scope.mainURI + '/save', $scope.formData).then(callbackSuccessSaving, notificationService.errorCallback);
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function callbackSuccessSaving(response) {
            $scope.ajaxRunning = false;
            $mdDialog.hide(response.data.notification);
            dataScopeShared.addData('DATA_UPDATE', null);
        }

        $scope.$watch('KECAMATAN_SANTRI.selectedItem', function (selectedItem) {
            if (typeof selectedItem === 'undefined' || selectedItem.key === null)
                $scope.formData.KECAMATAN_SANTRI = null;
            else
                $scope.formData.KECAMATAN_SANTRI = selectedItem.key;
        });
    });

    angular.module('mainApp').controller('psbKelompokController', function ($scope, formHelper, notificationService, $routeParams, $http, $mdDialog, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.ajaxRunning = true;
        $scope.dataUpdate = dataScopeShared.getData('DATA_UPDATE');
        $scope.addForm = true;

        $scope.formData = {
            ID_PKK: null,
            NAMA_PKK: null,
            KETERANGAN_PKK: null,
        };

        $http.get($scope.mainURI + '/form').then(callbackForm, notificationService.errorCallback);

        function callbackForm(response) {
            callbackFormData(response);
        }

        function callbackFormData(response) {
            if ($scope.dataUpdate === null || typeof $scope.dataUpdate === 'undefined')
                formReady();
            else
                getData();
        }

        function getData() {
            $http.post($scope.mainURI + '/view', $scope.dataUpdate).then(callbackSuccessData, notificationService.errorCallback);
        }

        function callbackSuccessData(response) {
            $scope.formData.ID_PKK = response.data.ID_PKK;
            $scope.formData.NAMA_PKK = response.data.NAMA_PKK;
            $scope.formData.KETERANGAN_PKK = response.data.KETERANGAN_PKK;

            $scope.addForm = false;

            formReady();
        }

        function formReady() {
            $scope.ajaxRunning = false;
        }

        $scope.cancelSumbit = function () {
            dataScopeShared.addData('DATA_UPDATE', null);
            $mdDialog.cancel();
        };

        $scope.saveSubmit = function () {
            if ($scope.form.$valid) {
                $scope.ajaxRunning = true;

                $http.post($scope.mainURI + '/save', $scope.formData).then(callbackSuccessSaving, notificationService.errorCallback);
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function callbackSuccessSaving(response) {
            $scope.ajaxRunning = false;
            $mdDialog.hide(response.data.notification);
            dataScopeShared.addData('DATA_UPDATE', null);
        }
    });

    angular.module('mainApp').controller('docDatatableController', function ($scope, $timeout, $mdSidenav, $log) {
        $scope.title = 'Dokumentasi';
        $scope.content = 'Isi Dokumentasi';
    });

    angular.module('mainApp').controller('santriKamarController', function ($scope, formHelper, notificationService, $routeParams, $http, $mdDialog, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.ajaxRunning = true;
        $scope.dataUpdate = dataScopeShared.getData('DATA_UPDATE');
        $scope.addForm = true;

        $scope.formData = {
            ID_KAMAR: null,
            GEDUNG_KAMAR: null,
            NAMA_KAMAR: null,
            KETERANGAN_KAMAR: null,
        };

        $http.get($scope.mainURI + '/form').then(callbackForm, notificationService.errorCallback);

        function callbackForm(response) {
            callbackFormData(response);
        }

        function callbackFormData(response) {
            $scope.dataGEDUNG_KAMAR = response.data.dataGEDUNG_KAMAR;

            if ($scope.dataUpdate === null || typeof $scope.dataUpdate === 'undefined')
                formReady();
            else
                getData();
        }

        function getData() {
            $http.post($scope.mainURI + '/view', $scope.dataUpdate).then(callbackSuccessData, notificationService.errorCallback);
        }

        function callbackSuccessData(response) {
            $scope.formData.ID_KAMAR = response.data.ID_KAMAR;
            $scope.formData.GEDUNG_KAMAR = response.data.GEDUNG_KAMAR;
            $scope.formData.NAMA_KAMAR = response.data.NAMA_KAMAR;
            $scope.formData.KETERANGAN_KAMAR = response.data.KETERANGAN_KAMAR;

            $scope.addForm = false;

            formReady();
        }

        function formReady() {
            $scope.ajaxRunning = false;
        }

        $scope.cancelSumbit = function () {
            dataScopeShared.addData('DATA_UPDATE', null);
            $mdDialog.cancel();
        };

        $scope.saveSubmit = function () {
            if ($scope.form.$valid) {
                $scope.ajaxRunning = true;

                $http.post($scope.mainURI + '/save', $scope.formData).then(callbackSuccessSaving, notificationService.errorCallback);
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function callbackSuccessSaving(response) {
            $scope.ajaxRunning = false;
            $mdDialog.hide(response.data.notification);
            dataScopeShared.addData('DATA_UPDATE', null);
        }
    });

    angular.module('mainApp').controller('penempatanKamarController', function ($scope, $routeParams, $http, notificationService, NgTableParams, $mdDialog, url_template, $timeout, $mdSidenav, $route, $templateCache, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.mainTemplate = url_template + $routeParams.template;
        $scope.getData = getDataSantri();
        $scope.appReady = false;
        $scope.dataOriginal = null;
        $scope.flex = 90;
        $scope.flexOffset = 5;

        $scope.fabHidden = true;
        $scope.fabIsOpen = false;
        $scope.fabHover = false;

        $scope.formData = {
            KAMAR_SK: null
        };

        $http.get($scope.mainURI + '/index').then(callbackSuccess, notificationService.errorCallback);

        function callbackSuccess(response) {
            $scope.title = response.data.title;
            $scope.breadcrumb = response.data.breadcrumb;
            $scope.table = response.data.table;
            $scope.dataKAMAR_SK = response.data.kamar;

            getDataSantri();

            $scope.appReady = true;
        }

        function getDataSantri() {
            $http.get($scope.mainURI + '/datatable_santri_no_kamar').then(callbackDatatablesSantri, notificationService.errorCallback);
        }

        function callbackDatatablesSantri(response) {
            $scope.dataOriginal = response.data.data;

            var initialParams = {
                count: 15
            };
            var initialSettings = {
                counts: [],
                dataset: response.data.data
            };

            $scope.dataTablesSantri = new NgTableParams(initialParams, initialSettings);
            $scope.fabHidden = false;
        }

        $scope.$watch('formData.KAMAR_SK', function (KAMAR_SK) {
            if (KAMAR_SK !== null)
                getDataSantriKamar();
        });

        function getDataSantriKamar() {
            $http.post($scope.mainURI + '/datatable_santri_kamar', $scope.formData).then(callbackDatatablesSantriKamar, notificationService.errorCallback);
        }

        function callbackDatatablesSantriKamar(response) {
            $scope.dataOriginal = response.data.data;

            var initialParams = {
                count: 15
            };
            var initialSettings = {
                counts: [],
                dataset: response.data.data
            };

            $scope.dataTablesKamar = new NgTableParams(initialParams, initialSettings);
            $scope.fabHidden = false;
        }

        $scope.menuItems = [
            {id: "add_data", name: "Tambah Data", icon: "add"},
            {id: "download_data", name: "Unduh Data", icon: "file_download"},
            // {id: "print_data", name: "Catak Data", icon: "print"},
            {id: "reload_data", name: "Muat Ulang Data", icon: "refresh"},
            {id: "reload_page", name: "Muat Ulang Halaman", icon: "autorenew"},
            {id: "request_doc", name: "Dokumentasi", icon: "help"},
        ];

        $scope.openDialog = function ($event, item) {
            if (item.id === 'reload_data') {
                $scope.fabHidden = true;
                getDataSantri();
            } else if (item.id === 'reload_page') {
                reloadPage();
            } else if (item.id === 'request_doc') {
                $mdSidenav('right').toggle();
            } else if (item.id === 'add_data') {
                createDialog($event, 'form');
            } else if (item.id === 'print_data') {
                var mywindow = window.open('', 'PRINT', 'height=600,width=700');

                mywindow.document.write('<html><head><title>' + document.title + '</title><style type="text/css">body{font-family: "Roboto",Arial,sans-serif;overflow:visible;}.ng-table-filters,.ng-table-counts{display: none;} tr {border-top: 1px solid #f2f6f9;} .data-table{overflow: visible;} table{overflow:visible;}body, h1, h2, h3, ol, ul, div {     width: auto;     border: 0;     margin: 0 5%;     padding: 0;     float: none;     position: static;     overflow: visible; }</style>');
                mywindow.document.write('</head><body onload="window.print()">');
                mywindow.document.write('<h1>' + document.title + '</h1>');
                mywindow.document.write(document.getElementById('printable').innerHTML);
                mywindow.document.write('</body></html>');

                mywindow.document.close();
                mywindow.focus();

                return true;
            } else if (item.id === 'download_data') {
                if ($scope.dataOriginal === null)
                    notificationService.toastSimple('Data tidak ditemukan');
                else
                    alasql('SELECT * INTO XLSX("data_download.xlsx",{headers:true}) FROM ?', [$scope.dataOriginal]);
            }
        };

        function reloadPage() {
            var currentPageTemplate = $route.current.templateUrl;
            $templateCache.remove(currentPageTemplate);
            $route.reload();
        }

        function createDialog(event, mode) {
            $mdDialog
                    .show({
                        controller: DialogController,
                        clickOutsideToClose: false,
                        templateUrl: $scope.mainTemplate + '-' + mode + '.html',
                        targetEvent: event
                    })
                    .then(
                            function (text) {
                                notificationService.toastSimple(text);
                                getData();
                            },
                            function () {
                                // CANCEL DIALOG
                            }
                    );
        }

        function DialogController($scope, $mdDialog) {
            $scope.cancelSumbit = function () {
                dataScopeShared.addData('DATA_UPDATE', null);
                $mdDialog.cancel();
            };
        }

        $scope.prosesSantri = function (row, action) {
            if ($scope.formData.KAMAR_SK === null) {
                notificationService.toastSimple('Silahkan pilih kamar terlebih dahulu');
            } else {
                var dataSantri = {
                    ACTION: action,
                    ID_SANTRI: row.ID_SANTRI,
                    KAMAR_SANTRI: $scope.formData.KAMAR_SK,
                };
                $http.post($scope.mainURI + '/prosesSantri', dataSantri).then(callbackSuccessProsesSantri, notificationService.errorCallback);
            }
        }

        function callbackSuccessProsesSantri(response) {
            notificationService.toastSimple(response.data.notification);

            if (response.data.status) {
                getDataSantri();
                getDataSantriKamar();
            }
        }
    });
    ;

    angular.module('mainApp').controller('dataSantriController', function ($scope, formHelper, notificationService, $routeParams, $http, $mdDialog, dataScopeShared, $q) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.ajaxRunning = true;
        $scope.dataUpdate = dataScopeShared.getData('DATA_UPDATE');
        $scope.addForm = true;
        $scope.dataForm = [];

        $scope.formData = {
            ID_SANTRI: null,
            NIS_SANTRI: null,
            NIK_SANTRI: null,
            NAMA_SANTRI: null,
            PANGGILAN_SANTRI: null,
            ANGKATAN_SANTRI: null,
            JK_SANTRI: null,
            TEMPAT_LAHIR_SANTRI: null,
            TANGGAL_LAHIR_SANTRI: null,
            KECAMATAN_SANTRI: null,
            ALAMAT_SANTRI: null,
            NOHP_SANTRI: null,
            EMAIL_SANTRI: null,
            SUKU_SANTRI: null,
            AGAMA_SANTRI: null,
            ANAK_KE_SANTRI: null,
            KONDISI_SANTRI: null,
            BERAT_SANTRI: null,
            JUMLAH_SDR_SANTRI: null,
            TINGGI_SANTRI: null,
            GOL_DARAH_SANTRI: null,
            RIWAYAT_KESEHATAN_SANTRI: null,
            NO_IJASAH_SANTRI: null,
            TANGGAL_IJASAH_SANTRI: null,
            AYAH_NIK_SANTRI: null,
            AYAH_NAMA_SANTRI: null,
            AYAH_HIDUP_SANTRI: null,
            AYAH_TEMPAT_LAHIR_SANTRI: null,
            AYAH_TANGGAL_LAHIR_SANTRI: null,
            AYAH_PENDIDIKAN_SANTRI: null,
            AYAH_PEKERJAAN_SANTRI: null,
            IBU_NIK_SANTRI: null,
            IBU_NAMA_SANTRI: null,
            IBU_HIDUP_SANTRI: null,
            IBU_TEMPAT_LAHIR_SANTRI: null,
            IBU_TANGGAL_LAHIR_SANTRI: null,
            IBU_PENDIDIKAN_SANTRI: null,
            IBU_PEKERJAAN_SANTRI: null,
            WALI_NIK_SANTRI: null,
            WALI_NAMA_SANTRI: null,
            WALI_HUBUNGAN_SANTRI: null,
            WALI_PENDIDIKAN_SANTRI: null,
            WALI_PEKERJAAN_SANTRI: null,
            ORTU_PENGHASILAN_SANTRI: null,
            ORTU_NOHP1_SANTRI: null,
            ORTU_NOHP2_SANTRI: null,
            ORTU_NOHP3_SANTRI: null,
            ORTU_EMAIL_SANTRI: null,
            STATUS_ASAL_SANTRI: null,
            STATUS_MUTASI_SANTRI: null,
            TANGGAL_MUTASI_SANTRI: null,
        };

        $http.get($scope.mainURI + '/form').then(callbackForm, notificationService.errorCallback);

        function callbackForm(response) {
            $scope.dataJK_SANTRI = response.data.JK;
            $scope.dataSUKU_SANTRI = response.data.SUKU;
            $scope.dataKONDISI_SANTRI = response.data.KONDISI;
            $scope.dataAGAMA_SANTRI = response.data.AGAMA;
            $scope.dataGOL_DARAH_SANTRI = response.data.GOL_DARAH;
            $scope.dataAYAH_HIDUP_SANTRI = response.data.HIDUP_SANTRI;
            $scope.dataAYAH_PENDIDIKAN_SANTRI = response.data.PENDIDIKAN_SANTRI;
            $scope.dataAYAH_PEKERJAAN_SANTRI = response.data.PEKERJAAN_SANTRI;
            $scope.dataIBU_HIDUP_SANTRI = response.data.HIDUP_SANTRI;
            $scope.dataIBU_PENDIDIKAN_SANTRI = response.data.PENDIDIKAN_SANTRI;
            $scope.dataIBU_PEKERJAAN_SANTRI = response.data.PEKERJAAN_SANTRI;
            $scope.dataWALI_HUBUNGAN_SANTRI = response.data.HUBUNGAN_SANTRI;
            $scope.dataWALI_PENDIDIKAN_SANTRI = response.data.PENDIDIKAN_SANTRI;
            $scope.dataWALI_PEKERJAAN_SANTRI = response.data.PEKERJAAN_SANTRI;
            $scope.dataSTATUS_MUTASI_SANTRI = response.data.STATUS_MUTASI;
            $scope.dataORTU_PENGHASILAN_SANTRI = response.data.ORTU_PENGHASILAN_SANTRI;
            $scope.dataSTATUS_ASAL_SANTRI = response.data.STATUS_ASAL_SANTRI;

            var urlGetDataForm = [];

            urlGetDataForm.push($http.get(response.data.uri.kecamatan));

            $q.all(urlGetDataForm)
                    .then(
                            function (result) {
                                callbackFormData(result);
                            },
                            function (error) {
                                $scope.cancelSumbit();
                            }
                    );
        }

        function callbackFormData(response) {
            $scope.KECAMATAN_SANTRI = {
                dataAutocomplete: response[0].data
            };
            $scope.KECAMATAN_SANTRI = formHelper.autocomplete($scope.KECAMATAN_SANTRI);

            if ($scope.dataUpdate === null || typeof $scope.dataUpdate === 'undefined')
                formReady();
            else
                getData();
        }

        function getData() {
            $http.post($scope.mainURI + '/view', $scope.dataUpdate).then(callbackSuccessData, notificationService.errorCallback);
        }

        function callbackSuccessData(response) {
            angular.forEach($scope.KECAMATAN_SANTRI.dataAll, function (value, key) {
                if (parseInt(response.data.KECAMATAN_SANTRI) === parseInt(value.key)) {
                    $scope.KECAMATAN_SANTRI.selectedItem = value;
                }
            });

            $scope.formData = response.data;

            $scope.addForm = false;

            formReady();
        }

        function formReady() {
            if ($scope.addForm) {
                notificationService.toastSimple('Menambahkan santri hanya dapat dilakukan pada menu PSB.');

                $mdDialog.cancel();
            }

            $scope.ajaxRunning = false;
        }

        $scope.cancelSumbit = function () {
            dataScopeShared.addData('DATA_UPDATE', null);
            $mdDialog.cancel();
        };

        $scope.saveSubmit = function () {
            if (
                    ((typeof $scope.form.KECAMATAN_SANTRI === 'object') && (
                            $scope.form.KECAMATAN_SANTRI.$valid
                            && $scope.form.NIS_SANTRI.$valid
                            && $scope.form.NIK_SANTRI.$valid
                            && $scope.form.NAMA_SANTRI.$valid
                            && $scope.form.ANGKATAN_SANTRI.$valid
                            && $scope.form.JK_SANTRI.$valid
                            && $scope.form.TEMPAT_LAHIR_SANTRI.$valid
                            && $scope.form.TANGGAL_LAHIR_SANTRI.$valid
                            && $scope.form.ALAMAT_SANTRI.$valid
                            && $scope.form.NOHP_SANTRI.$valid
                            ))
                    ||
                    ((typeof $scope.form.STATUS_MUTASI_SANTRI === 'object')
                            && $scope.form.STATUS_MUTASI_SANTRI.$valid
                            )
                    ||
                    ((typeof $scope.form.TANGGAL_MUTASI_SANTRI === 'object')
                            && $scope.form.TANGGAL_MUTASI_SANTRI.$valid
                            )
                    ||
                    ((typeof $scope.form.AYAH_NAMA_SANTRI === 'object')
                            && $scope.form.AYAH_NAMA_SANTRI.$valid
                            )
                    ||
                    ((typeof $scope.form.IBU_NAMA_SANTRI === 'object')
                            && $scope.form.IBU_NAMA_SANTRI.$valid
                            )
                    ||
                    ((typeof $scope.form.ORTU_PENGHASILAN_SANTRI === 'object')
                            && $scope.form.ORTU_PENGHASILAN_SANTRI.$valid
                            )
                    ||
                    ((typeof $scope.form.WALI_NAMA_SANTRI === 'object')
                            && $scope.form.WALI_NAMA_SANTRI.$valid
                            )) {
                $scope.ajaxRunning = true;

                $http.post($scope.mainURI + '/save', $scope.formData).then(callbackSuccessSaving, notificationService.errorCallback);
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function callbackSuccessSaving(response) {
            $scope.ajaxRunning = false;
            $mdDialog.hide(response.data.notification);
            dataScopeShared.addData('DATA_UPDATE', null);
        }

        $scope.$watch('KECAMATAN_SANTRI.selectedItem', function (selectedItem) {
            if (typeof selectedItem === 'undefined' || selectedItem.key === null)
                $scope.formData.KECAMATAN_SANTRI = null;
            else
                $scope.formData.KECAMATAN_SANTRI = selectedItem.key;
        });
    });

    angular.module('mainApp').controller('kelasSantriController', function ($scope, $routeParams, $http, notificationService, NgTableParams, $mdDialog, url_template, $timeout, $mdSidenav, $route, $templateCache, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.mainTemplate = url_template + $routeParams.template;
        $scope.getData = getDataSantri();
        $scope.appReady = false;
        $scope.dataOriginal = null;
        $scope.flex = 90;
        $scope.flexOffset = 5;

        $scope.fabHidden = true;
        $scope.fabIsOpen = false;
        $scope.fabHover = false;

        $scope.formData = {
            KAMAR_SK: null
        };

        $http.get($scope.mainURI + '/index').then(callbackSuccess, notificationService.errorCallback);

        function callbackSuccess(response) {
            $scope.title = response.data.title;
            $scope.breadcrumb = response.data.breadcrumb;
            $scope.table = response.data.table;
            $scope.dataKAMAR_SK = response.data.kamar;

            getDataSantri();

            $scope.appReady = true;
        }

        function getDataSantri() {
            $http.get($scope.mainURI + '/datatable_santri_no_kamar').then(callbackDatatablesSantri, notificationService.errorCallback);
        }

        function callbackDatatablesSantri(response) {
            $scope.dataOriginal = response.data.data;

            var initialParams = {
                count: 15
            };
            var initialSettings = {
                counts: [],
                dataset: response.data.data
            };

            $scope.dataTablesSantri = new NgTableParams(initialParams, initialSettings);
            $scope.fabHidden = false;
        }

        $scope.$watch('formData.KAMAR_SK', function (KAMAR_SK) {
            if (KAMAR_SK !== null)
                getDataSantriKamar();
        });

        function getDataSantriKamar() {
            $http.post($scope.mainURI + '/datatable_santri_kamar', $scope.formData).then(callbackDatatablesSantriKamar, notificationService.errorCallback);
        }

        function callbackDatatablesSantriKamar(response) {
            $scope.dataOriginal = response.data.data;

            var initialParams = {
                count: 15
            };
            var initialSettings = {
                counts: [],
                dataset: response.data.data
            };

            $scope.dataTablesKamar = new NgTableParams(initialParams, initialSettings);
            $scope.fabHidden = false;
        }

        $scope.menuItems = [
            {id: "add_data", name: "Tambah Data", icon: "add"},
            {id: "download_data", name: "Unduh Data", icon: "file_download"},
            // {id: "print_data", name: "Catak Data", icon: "print"},
            {id: "reload_data", name: "Muat Ulang Data", icon: "refresh"},
            {id: "reload_page", name: "Muat Ulang Halaman", icon: "autorenew"},
            {id: "request_doc", name: "Dokumentasi", icon: "help"},
        ];

        $scope.openDialog = function ($event, item) {
            if (item.id === 'reload_data') {
                $scope.fabHidden = true;
                getDataSantri();
            } else if (item.id === 'reload_page') {
                reloadPage();
            } else if (item.id === 'request_doc') {
                $mdSidenav('right').toggle();
            } else if (item.id === 'add_data') {
                createDialog($event, 'form');
            } else if (item.id === 'print_data') {
                var mywindow = window.open('', 'PRINT', 'height=600,width=700');

                mywindow.document.write('<html><head><title>' + document.title + '</title><style type="text/css">body{font-family: "Roboto",Arial,sans-serif;overflow:visible;}.ng-table-filters,.ng-table-counts{display: none;} tr {border-top: 1px solid #f2f6f9;} .data-table{overflow: visible;} table{overflow:visible;}body, h1, h2, h3, ol, ul, div {     width: auto;     border: 0;     margin: 0 5%;     padding: 0;     float: none;     position: static;     overflow: visible; }</style>');
                mywindow.document.write('</head><body onload="window.print()">');
                mywindow.document.write('<h1>' + document.title + '</h1>');
                mywindow.document.write(document.getElementById('printable').innerHTML);
                mywindow.document.write('</body></html>');

                mywindow.document.close();
                mywindow.focus();

                return true;
            } else if (item.id === 'download_data') {
                if ($scope.dataOriginal === null)
                    notificationService.toastSimple('Data tidak ditemukan');
                else
                    alasql('SELECT * INTO XLSX("data_download.xlsx",{headers:true}) FROM ?', [$scope.dataOriginal]);
            }
        };

        function reloadPage() {
            var currentPageTemplate = $route.current.templateUrl;
            $templateCache.remove(currentPageTemplate);
            $route.reload();
        }

        function createDialog(event, mode) {
            $mdDialog
                    .show({
                        controller: DialogController,
                        clickOutsideToClose: false,
                        templateUrl: $scope.mainTemplate + '-' + mode + '.html',
                        targetEvent: event
                    })
                    .then(
                            function (text) {
                                notificationService.toastSimple(text);
                                getData();
                            },
                            function () {
                                // CANCEL DIALOG
                            }
                    );
        }

        function DialogController($scope, $mdDialog) {
            $scope.cancelSumbit = function () {
                dataScopeShared.addData('DATA_UPDATE', null);
                $mdDialog.cancel();
            };
        }

        $scope.prosesSantri = function (row, action) {
            if ($scope.formData.KAMAR_SK === null) {
                notificationService.toastSimple('Silahkan pilih kamar terlebih dahulu');
            } else {
                var dataSantri = {
                    ACTION: action,
                    ID_SANTRI: row.ID_SANTRI,
                    KAMAR_SANTRI: $scope.formData.KAMAR_SK,
                };
                $http.post($scope.mainURI + '/prosesSantri', dataSantri).then(callbackSuccessProsesSantri, notificationService.errorCallback);
            }
        }

        function callbackSuccessProsesSantri(response) {
            notificationService.toastSimple(response.data.notification);

            if (response.data.status) {
                getDataSantri();
                getDataSantriKamar();
            }
        }
    });

    angular.module('mainApp').controller('kegiatanSantriController', function ($scope, $routeParams, $http, notificationService, NgTableParams, $mdDialog, url_template, $timeout, $mdSidenav, $route, $templateCache, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.mainTemplate = url_template + $routeParams.template;
        $scope.appReady = false;
        $scope.dataOriginal = null;
        $scope.flex = 90;
        $scope.flexOffset = 5;

        $scope.fabHidden = true;
        $scope.fabIsOpen = false;
        $scope.fabHover = false;

        $scope.formData = {
            KELAS_AS: null
        };

        $http.get($scope.mainURI + '/index').then(callbackSuccess, notificationService.errorCallback);

        function callbackSuccess(response) {
            $scope.title = response.data.title;
            $scope.breadcrumb = response.data.breadcrumb;
            $scope.table = response.data.table;
            $scope.dataKELAS_AS = response.data.kelas;

            $scope.appReady = true;
        }

        function getDataSantri() {
            $http.post($scope.mainURI + '/datatable_santri_no_kegiatan', $scope.formData).then(callbackDatatablesSantri, notificationService.errorCallback);
        }

        function callbackDatatablesSantri(response) {
            $scope.dataOriginal = response.data.data;

            var initialParams = {
                count: 15
            };
            var initialSettings = {
                counts: [],
                dataset: response.data.data
            };

            $scope.dataTablesSantri = new NgTableParams(initialParams, initialSettings);
            $scope.fabHidden = false;
        }

        $scope.$watch('formData.KELAS_AS', function (KELAS_AS) {
            if (KELAS_AS !== null) {
                getDataSantri();
                getDataSantriKegiatan();
            }
        });

        function getDataSantriKegiatan() {
            $http.post($scope.mainURI + '/datatable_santri_kegiatan', $scope.formData).then(callbackDatatablesSantriKamar, notificationService.errorCallback);
        }

        function callbackDatatablesSantriKamar(response) {
            $scope.dataOriginal = response.data.data;

            var initialParams = {
                count: 15
            };
            var initialSettings = {
                counts: [],
                dataset: response.data.data
            };

            $scope.dataTablesKegiatan = new NgTableParams(initialParams, initialSettings);
            $scope.fabHidden = false;
        }

        $scope.menuItems = [
            {id: "add_data", name: "Tambah Data", icon: "add"},
            {id: "download_data", name: "Unduh Data", icon: "file_download"},
            // {id: "print_data", name: "Catak Data", icon: "print"},
            {id: "reload_data", name: "Muat Ulang Data", icon: "refresh"},
            {id: "reload_page", name: "Muat Ulang Halaman", icon: "autorenew"},
            {id: "request_doc", name: "Dokumentasi", icon: "help"},
        ];

        $scope.openDialog = function ($event, item) {
            if (item.id === 'reload_data') {
                $scope.fabHidden = true;
                getDataSantri();
            } else if (item.id === 'reload_page') {
                reloadPage();
            } else if (item.id === 'request_doc') {
                $mdSidenav('right').toggle();
            } else if (item.id === 'add_data') {
                createDialog($event, 'form');
            } else if (item.id === 'print_data') {
                var mywindow = window.open('', 'PRINT', 'height=600,width=700');

                mywindow.document.write('<html><head><title>' + document.title + '</title><style type="text/css">body{font-family: "Roboto",Arial,sans-serif;overflow:visible;}.ng-table-filters,.ng-table-counts{display: none;} tr {border-top: 1px solid #f2f6f9;} .data-table{overflow: visible;} table{overflow:visible;}body, h1, h2, h3, ol, ul, div {     width: auto;     border: 0;     margin: 0 5%;     padding: 0;     float: none;     position: static;     overflow: visible; }</style>');
                mywindow.document.write('</head><body onload="window.print()">');
                mywindow.document.write('<h1>' + document.title + '</h1>');
                mywindow.document.write(document.getElementById('printable').innerHTML);
                mywindow.document.write('</body></html>');

                mywindow.document.close();
                mywindow.focus();

                return true;
            } else if (item.id === 'download_data') {
                if ($scope.dataOriginal === null)
                    notificationService.toastSimple('Data tidak ditemukan');
                else
                    alasql('SELECT * INTO XLSX("data_download.xlsx",{headers:true}) FROM ?', [$scope.dataOriginal]);
            }
        };

        function reloadPage() {
            var currentPageTemplate = $route.current.templateUrl;
            $templateCache.remove(currentPageTemplate);
            $route.reload();
        }

        function createDialog(event, mode) {
            $mdDialog
                    .show({
                        controller: DialogController,
                        clickOutsideToClose: false,
                        templateUrl: $scope.mainTemplate + '-' + mode + '.html',
                        targetEvent: event
                    })
                    .then(
                            function (text) {
                                notificationService.toastSimple(text);
                                getDataSantri();
                            },
                            function () {
                                // CANCEL DIALOG
                            }
                    );
        }

        function DialogController($scope, $mdDialog) {
            $scope.cancelSumbit = function () {
                dataScopeShared.addData('DATA_UPDATE', null);
                $mdDialog.cancel();
            };
        }

        $scope.prosesSantri = function (row, action) {
            if ($scope.formData.KELAS_AS === null) {
                notificationService.toastSimple('Silahkan pilih kamar terlebih dahulu');
            } else {
                var dataSantri = {
                    ACTION: action,
                    SANTRI_AS: row.ID_SANTRI,
                    KELAS_AS: $scope.formData.KELAS_AS,
                };
                $http.post($scope.mainURI + '/prosesSantri', dataSantri).then(callbackSuccessProsesSantri, notificationService.errorCallback);
            }
        }

        function callbackSuccessProsesSantri(response) {
            notificationService.toastSimple(response.data.notification);

            if (response.data.status) {
                getDataSantri();
                getDataSantriKegiatan();
            }
        }
    });

    angular.module('mainApp').controller('rombelSantriController', function ($scope, $routeParams, $http, notificationService, NgTableParams, $mdDialog, url_template, $timeout, $mdSidenav, $route, $templateCache, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.mainTemplate = url_template + $routeParams.template;
        $scope.appReady = false;
        $scope.dataOriginal = null;
        $scope.flex = 90;
        $scope.flexOffset = 5;

        $scope.fabHidden = true;
        $scope.fabIsOpen = false;
        $scope.fabHover = false;

        $scope.formData = {
            ROMBEL_AS: null
        };

        $http.get($scope.mainURI + '/index').then(callbackSuccess, notificationService.errorCallback);

        function callbackSuccess(response) {
            $scope.title = response.data.title;
            $scope.breadcrumb = response.data.breadcrumb;
            $scope.table = response.data.table;
            $scope.dataROMBEL_AS = response.data.rombel;

            $scope.appReady = true;
        }

        function getDataSantri() {
            $http.post($scope.mainURI + '/datatable_santri_no_rombel', $scope.formData).then(callbackDatatablesSantri, notificationService.errorCallback);
        }

        function callbackDatatablesSantri(response) {
            $scope.dataOriginal = response.data.data;

            var initialParams = {
                count: 15
            };
            var initialSettings = {
                counts: [],
                dataset: response.data.data
            };

            $scope.dataTablesSantri = new NgTableParams(initialParams, initialSettings);
            $scope.fabHidden = false;
        }

        $scope.$watch('formData.ROMBEL_AS', function (ROMBEL_AS) {
            if (ROMBEL_AS !== null) {
                getDataSantri();
                getDataSantriRombel();
            }
        });

        function getDataSantriRombel() {
            $http.post($scope.mainURI + '/datatable_santri_rombel', $scope.formData).then(callbackDatatablesSantriKamar, notificationService.errorCallback);
        }

        function callbackDatatablesSantriKamar(response) {
            $scope.dataOriginal = response.data.data;

            var initialParams = {
                count: 15
            };
            var initialSettings = {
                counts: [],
                dataset: response.data.data
            };

            $scope.dataTablesRombel = new NgTableParams(initialParams, initialSettings);
            $scope.fabHidden = false;
        }

        $scope.menuItems = [
            {id: "add_data", name: "Tambah Data", icon: "add"},
            {id: "download_data", name: "Unduh Data", icon: "file_download"},
            // {id: "print_data", name: "Catak Data", icon: "print"},
            {id: "reload_data", name: "Muat Ulang Data", icon: "refresh"},
            {id: "reload_page", name: "Muat Ulang Halaman", icon: "autorenew"},
            {id: "request_doc", name: "Dokumentasi", icon: "help"},
        ];

        $scope.openDialog = function ($event, item) {
            if (item.id === 'reload_data') {
                $scope.fabHidden = true;
                getDataSantri();
            } else if (item.id === 'reload_page') {
                reloadPage();
            } else if (item.id === 'request_doc') {
                $mdSidenav('right').toggle();
            } else if (item.id === 'add_data') {
                createDialog($event, 'form');
            } else if (item.id === 'print_data') {
                var mywindow = window.open('', 'PRINT', 'height=600,width=700');

                mywindow.document.write('<html><head><title>' + document.title + '</title><style type="text/css">body{font-family: "Roboto",Arial,sans-serif;overflow:visible;}.ng-table-filters,.ng-table-counts{display: none;} tr {border-top: 1px solid #f2f6f9;} .data-table{overflow: visible;} table{overflow:visible;}body, h1, h2, h3, ol, ul, div {     width: auto;     border: 0;     margin: 0 5%;     padding: 0;     float: none;     position: static;     overflow: visible; }</style>');
                mywindow.document.write('</head><body onload="window.print()">');
                mywindow.document.write('<h1>' + document.title + '</h1>');
                mywindow.document.write(document.getElementById('printable').innerHTML);
                mywindow.document.write('</body></html>');

                mywindow.document.close();
                mywindow.focus();

                return true;
            } else if (item.id === 'download_data') {
                if ($scope.dataOriginal === null)
                    notificationService.toastSimple('Data tidak ditemukan');
                else
                    alasql('SELECT * INTO XLSX("data_download.xlsx",{headers:true}) FROM ?', [$scope.dataOriginal]);
            }
        };

        function reloadPage() {
            var currentPageTemplate = $route.current.templateUrl;
            $templateCache.remove(currentPageTemplate);
            $route.reload();
        }

        function createDialog(event, mode) {
            $mdDialog
                    .show({
                        controller: DialogController,
                        clickOutsideToClose: false,
                        templateUrl: $scope.mainTemplate + '-' + mode + '.html',
                        targetEvent: event
                    })
                    .then(
                            function (text) {
                                notificationService.toastSimple(text);
                                getDataSantri();
                            },
                            function () {
                                // CANCEL DIALOG
                            }
                    );
        }

        function DialogController($scope, $mdDialog) {
            $scope.cancelSumbit = function () {
                dataScopeShared.addData('DATA_UPDATE', null);
                $mdDialog.cancel();
            };
        }

        $scope.prosesSantri = function (row, action) {
            if ($scope.formData.ROMBEL_AS === null) {
                notificationService.toastSimple('Silahkan pilih kamar terlebih dahulu');
            } else {
                var dataSantri = {
                    ACTION: action,
                    SANTRI_AS: row.ID_SANTRI,
                    ROMBEL_AS: $scope.formData.ROMBEL_AS,
                };
                $http.post($scope.mainURI + '/prosesSantri', dataSantri).then(callbackSuccessProsesSantri, notificationService.errorCallback);
            }
        }

        function callbackSuccessProsesSantri(response) {
            notificationService.toastSimple(response.data.notification);

            if (response.data.status) {
                getDataSantri();
                getDataSantriRombel();
            }
        }
    });

    angular.module('mainApp').controller('akadPresensiController', function ($scope, $routeParams, $http, notificationService, NgTableParams, $mdDialog, url_template, $timeout, $mdSidenav, $route, $templateCache, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.mainTemplate = url_template + $routeParams.template;
        $scope.appReady = false;
        $scope.dataOriginal = null;
        $scope.flex = 90;
        $scope.flexOffset = 5;

        $scope.fabHidden = true;
        $scope.fabIsOpen = false;
        $scope.fabHover = false;

        $scope.formReady = false;

        $scope.formData = {
            ROMBEL_ABSENSI: null,
            TANGGAL_ABSENSI: null,
        };

        $scope.formDataPresensi = {
            ALASAN_ABSENSI: [],
            KETERANGAN_ABSENSI: [],
        };

        $scope.$watch('formData.ROMBEL_ABSENSI', function (ROMBEL_ABSENSI) {
            $scope.formReady = false;
        });

        $scope.$watch('formData.TANGGAL_ABSENSI', function (TANGGAL_ABSENSI) {
            $scope.formReady = false;
        });

        $http.get($scope.mainURI + '/index').then(callbackSuccess, notificationService.errorCallback);

        function callbackSuccess(response) {
            $scope.title = response.data.title;
            $scope.breadcrumb = response.data.breadcrumb;
            $scope.table = response.data.table;
            $scope.dataROMBEL_ABSENSI = response.data.rombel;

            $scope.appReady = true;
        }

        $scope.pilihFilter = function (form) {
            if (form.$valid) {
                getDataSantri();
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function getDataSantri() {
            $http.post($scope.mainURI + '/datatable', $scope.formData).then(callbackDatatablesSantri, notificationService.errorCallback);
        }

        function callbackDatatablesSantri(response) {
            $scope.dataOriginal = response.data.data;

            var initialParams = {
                count: 15
            };
            var initialSettings = {
                counts: [],
                dataset: response.data.data
            };

            $scope.dataTablesSantri = new NgTableParams(initialParams, initialSettings);
            $scope.fabHidden = false;
            $scope.formReady = true;

            setAlasanPresensi();
        }

        function setAlasanPresensi() {
            angular.forEach($scope.dataOriginal, function (item, index) {
                $scope.formDataPresensi.ALASAN_ABSENSI[item.ID_SANTRI] = item.ALASAN_ABSENSI === null ? 'HADIR' : item.ALASAN_ABSENSI;
                $scope.formDataPresensi.KETERANGAN_ABSENSI[item.ID_SANTRI] = item.KETERANGAN_ABSENSI;
            });
        }

        $scope.prosesPresensi = function (row) {
            var dataSantri = {
                SANTRI_ABSENSI: row.ID_AS,
                ROMBEL_ABSENSI: $scope.formData.ROMBEL_ABSENSI,
                TANGGAL_ABSENSI: $scope.formData.TANGGAL_ABSENSI,
                ALASAN_ABSENSI: $scope.formDataPresensi.ALASAN_ABSENSI[row.ID_SANTRI],
                KETERANGAN_ABSENSI: $scope.formDataPresensi.KETERANGAN_ABSENSI[row.ID_SANTRI],
            };
            $http.post($scope.mainURI + '/prosesPresensi', dataSantri).then(callbackSuccessProsesSantri, notificationService.errorCallback);
        }

        function callbackSuccessProsesSantri(response) {
            notificationService.toastSimple(response.data.notification);
        }

        $scope.menuItems = [
            {id: "add_data", name: "Tambah Data", icon: "add"},
            {id: "download_data", name: "Unduh Data", icon: "file_download"},
            // {id: "print_data", name: "Catak Data", icon: "print"},
            {id: "reload_data", name: "Muat Ulang Data", icon: "refresh"},
            {id: "reload_page", name: "Muat Ulang Halaman", icon: "autorenew"},
            {id: "request_doc", name: "Dokumentasi", icon: "help"},
        ];

        $scope.openDialog = function ($event, item) {
            if (item.id === 'reload_data') {
                $scope.fabHidden = true;
                getDataSantri();
            } else if (item.id === 'reload_page') {
                reloadPage();
            } else if (item.id === 'request_doc') {
                $mdSidenav('right').toggle();
            } else if (item.id === 'add_data') {
                createDialog($event, 'form');
            } else if (item.id === 'print_data') {
                var mywindow = window.open('', 'PRINT', 'height=600,width=700');

                mywindow.document.write('<html><head><title>' + document.title + '</title><style type="text/css">body{font-family: "Roboto",Arial,sans-serif;overflow:visible;}.ng-table-filters,.ng-table-counts{display: none;} tr {border-top: 1px solid #f2f6f9;} .data-table{overflow: visible;} table{overflow:visible;}body, h1, h2, h3, ol, ul, div {     width: auto;     border: 0;     margin: 0 5%;     padding: 0;     float: none;     position: static;     overflow: visible; }</style>');
                mywindow.document.write('</head><body onload="window.print()">');
                mywindow.document.write('<h1>' + document.title + '</h1>');
                mywindow.document.write(document.getElementById('printable').innerHTML);
                mywindow.document.write('</body></html>');

                mywindow.document.close();
                mywindow.focus();

                return true;
            } else if (item.id === 'download_data') {
                if ($scope.dataOriginal === null)
                    notificationService.toastSimple('Data tidak ditemukan');
                else
                    alasql('SELECT * INTO XLSX("data_download.xlsx",{headers:true}) FROM ?', [$scope.dataOriginal]);
            }
        };

        function reloadPage() {
            var currentPageTemplate = $route.current.templateUrl;
            $templateCache.remove(currentPageTemplate);
            $route.reload();
        }

        function createDialog(event, mode) {
            $mdDialog
                    .show({
                        controller: DialogController,
                        clickOutsideToClose: false,
                        templateUrl: $scope.mainTemplate + '-' + mode + '.html',
                        targetEvent: event
                    })
                    .then(
                            function (text) {
                                notificationService.toastSimple(text);
                                getDataSantri();
                            },
                            function () {
                                // CANCEL DIALOG
                            }
                    );
        }

        function DialogController($scope, $mdDialog) {
            $scope.cancelSumbit = function () {
                dataScopeShared.addData('DATA_UPDATE', null);
                $mdDialog.cancel();
            };
        }
    });

    angular.module('mainApp').controller('akadMapelController', function ($scope, formHelper, notificationService, $routeParams, $http, $mdDialog, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.ajaxRunning = true;
        $scope.dataUpdate = dataScopeShared.getData('DATA_UPDATE');
        $scope.addForm = true;

        $scope.formData = {
            ID_MAPEL: null,
            KODE_MAPEL: null,
            KELAS_MAPEL: null,
            NAMA_MAPEL: null,
            KETERANGAN_MAPEL: null,
        };

        $http.get($scope.mainURI + '/form').then(callbackForm, notificationService.errorCallback);

        function callbackForm(response) {
            callbackFormData(response);
        }

        function callbackFormData(response) {
            $scope.dataKELAS_MAPEL = response.data.dataKELAS_MAPEL;

            if ($scope.dataUpdate === null || typeof $scope.dataUpdate === 'undefined')
                formReady();
            else
                getData();
        }

        function getData() {
            $http.post($scope.mainURI + '/view', $scope.dataUpdate).then(callbackSuccessData, notificationService.errorCallback);
        }

        function callbackSuccessData(response) {
            $scope.formData.ID_MAPEL = response.data.ID_MAPEL;
            $scope.formData.KODE_MAPEL = response.data.KODE_MAPEL;
            $scope.formData.KELAS_MAPEL = response.data.KELAS_MAPEL;
            $scope.formData.NAMA_MAPEL = response.data.NAMA_MAPEL;
            $scope.formData.KETERANGAN_MAPEL = response.data.KETERANGAN_MAPEL;

            $scope.addForm = false;

            formReady();
        }

        function formReady() {
            $scope.ajaxRunning = false;
        }

        $scope.cancelSumbit = function () {
            dataScopeShared.addData('DATA_UPDATE', null);
            $mdDialog.cancel();
        };

        $scope.saveSubmit = function () {
            if ($scope.form.$valid) {
                $scope.ajaxRunning = true;

                $http.post($scope.mainURI + '/save', $scope.formData).then(callbackSuccessSaving, notificationService.errorCallback);
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function callbackSuccessSaving(response) {
            $scope.ajaxRunning = false;
            $mdDialog.hide(response.data.notification);
            dataScopeShared.addData('DATA_UPDATE', null);
        }
    });

    angular.module('mainApp').controller('dataUstadzController', function ($scope, formHelper, notificationService, $routeParams, $http, $mdDialog, dataScopeShared, $q) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.ajaxRunning = true;
        $scope.dataUpdate = dataScopeShared.getData('DATA_UPDATE');
        $scope.addForm = true;
        $scope.dataForm = [];

        $scope.formData = {
            ID_UST: null,
            NIP_UST: null,
            NIK_UST: null,
            NAMA_UST: null,
            GELAR_AWAL_UST: null,
            GELAR_AKHIR_UST: null,
            JK_UST: null,
            TEMPAT_LAHIR_UST: null,
            TANGGAL_LAHIR_UST: null,
            ALAMAT_UST: null,
            KECAMATAN_UST: null,
            NOHP_UST: null,
            EMAIL_UST: null,
            ROMBEL_UST: null,
        };

        $http.get($scope.mainURI + '/form').then(callbackForm, notificationService.errorCallback);

        function callbackForm(response) {
            $scope.dataPSB_KELOMPOK_UST = response.data.kelompok;
            $scope.dataJK_UST = response.data.jk;
            $scope.dataKEGIATAN_UST = response.data.kelas;

            var urlGetDataForm = [];

            urlGetDataForm.push($http.get(response.data.uri.kecamatan));

            $q.all(urlGetDataForm)
                    .then(
                            function (result) {
                                callbackFormData(result);
                            },
                            function (error) {
                                $scope.cancelSumbit();
                            }
                    );
        }

        function callbackFormData(response) {
            $scope.KECAMATAN_UST = {
                dataAutocomplete: response[0].data
            };
            $scope.KECAMATAN_UST = formHelper.autocomplete($scope.KECAMATAN_UST);

            if ($scope.dataUpdate === null || typeof $scope.dataUpdate === 'undefined')
                formReady();
            else
                getData();
        }

        function getData() {
            var urlDataForm = [];

            urlDataForm.push($http.post($scope.mainURI + '/view', $scope.dataUpdate));
            urlDataForm.push($http.post($scope.mainURI + '/get_rombel', $scope.dataUpdate));

            $q.all(urlDataForm)
                    .then(
                            function (result) {
                                callbackFormDataView(result);
                            },
                            function (error) {
                                $scope.cancelSumbit();
                            }
                    );
        }

        function callbackFormDataView(response) {
            angular.forEach($scope.KECAMATAN_UST.dataAll, function (value, key) {
                if (parseInt(response[0].data.KECAMATAN_UST) === parseInt(value.key)) {
                    $scope.KECAMATAN_UST.selectedItem = value;
                }
            });

            $scope.formData = response[0].data;
            $scope.dataROMBEL_UST = response[1].data;

            $scope.addForm = false;

            formReady();
        }

        function formReady() {
            $scope.ajaxRunning = false;
        }

        $scope.cancelSumbit = function () {
            dataScopeShared.addData('DATA_UPDATE', null);
            $mdDialog.cancel();
        };

        $scope.saveSubmit = function () {
            if ($scope.form.KECAMATAN_UST.$valid
                    && $scope.form.NIP_UST.$valid
                    && $scope.form.NAMA_UST.$valid
                    && $scope.form.JK_UST.$valid
                    && $scope.form.TEMPAT_LAHIR_UST.$valid
                    && $scope.form.TANGGAL_LAHIR_UST.$valid
                    && $scope.form.ALAMAT_UST.$valid
                    ) {
                $scope.ajaxRunning = true;

                $http.post($scope.mainURI + '/save', $scope.formData).then(callbackSuccessSaving, notificationService.errorCallback);
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function callbackSuccessSaving(response) {
            $scope.ajaxRunning = false;
            $mdDialog.hide(response.data.notification);
            dataScopeShared.addData('DATA_UPDATE', null);
        }

        $scope.$watch('KECAMATAN_UST.selectedItem', function (selectedItem) {
            if (typeof selectedItem === 'undefined' || selectedItem.key === null)
                $scope.formData.KECAMATAN_UST = null;
            else
                $scope.formData.KECAMATAN_UST = selectedItem.key;
        });
    });

    angular.module('mainApp').controller('akadJadwalController', function ($scope, formHelper, notificationService, $routeParams, $http, $mdDialog, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.ajaxRunning = true;
        $scope.dataUpdate = dataScopeShared.getData('DATA_UPDATE');
        $scope.addForm = true;

        $scope.formData = {
            ID_AJ: null,
            MAPEL_AJ: null,
            USTADZ_AJ: null,
        };

        $http.get($scope.mainURI + '/form').then(callbackForm, notificationService.errorCallback);

        function callbackForm(response) {
            callbackFormData(response);
        }

        function callbackFormData(response) {
            $scope.dataMAPEL_AJ = response.data.mapel;
            $scope.dataUSTADZ_AJ = response.data.ustadz;

            if ($scope.dataUpdate === null || typeof $scope.dataUpdate === 'undefined')
                formReady();
            else
                getData();
        }

        function getData() {
            $http.post($scope.mainURI + '/view', $scope.dataUpdate).then(callbackSuccessData, notificationService.errorCallback);
        }

        function callbackSuccessData(response) {
            $scope.formData = response.data;

            $scope.addForm = false;

            formReady();
        }

        function formReady() {
            $scope.ajaxRunning = false;
        }

        $scope.cancelSumbit = function () {
            dataScopeShared.addData('DATA_UPDATE', null);
            $mdDialog.cancel();
        };

        $scope.saveSubmit = function () {
            if ($scope.form.$valid) {
                $scope.ajaxRunning = true;

                $http.post($scope.mainURI + '/save', $scope.formData).then(callbackSuccessSaving, notificationService.errorCallback);
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function callbackSuccessSaving(response) {
            $scope.ajaxRunning = false;
            $mdDialog.hide(response.data.notification);
            dataScopeShared.addData('DATA_UPDATE', null);
        }
    });

    angular.module('mainApp').controller('akadNilaiController', function ($scope, $routeParams, $http, notificationService, NgTableParams, $mdDialog, url_template, $timeout, $mdSidenav, $route, $templateCache, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.mainTemplate = url_template + $routeParams.template;
        $scope.appReady = false;
        $scope.dataOriginal = null;
        $scope.flex = 90;
        $scope.flexOffset = 5;

        $scope.fabHidden = true;
        $scope.fabIsOpen = false;
        $scope.fabHover = false;

        $scope.formReady = false;

        $scope.formData = {
            ROMBEL_AS: null,
            JADWAL_NILAI: null,
        };

        $scope.formDataNilai = {
            NILAI_NILAI: [],
        };

        $scope.$watch('formData.ROMBEL_AS', function (ROMBEL_AS) {
            $scope.formReady = false;
            $scope.formData.JADWAL_NILAI = null;

            if (ROMBEL_AS !== null)
                getDataJadwal();
        });

        function getDataJadwal() {
            var dataRombel = {
                MODEL: 'JADWAL',
                PARAMS: {
                    ID_ROMBEL: $scope.formData.ROMBEL_AS
                }
            };

            $http.post($scope.mainURI + '/get_data', dataRombel).then(callbackJadwal, notificationService.errorCallback);
        }

        function callbackJadwal(response) {
            $scope.dataJADWAL_NILAI = response.data;
        }

        $scope.$watch('formData.JADWAL_NILAI', function (JADWAL_NILAI) {
            $scope.formReady = false;
        });

        $http.get($scope.mainURI + '/index').then(callbackSuccess, notificationService.errorCallback);

        function callbackSuccess(response) {
            $scope.title = response.data.title;
            $scope.breadcrumb = response.data.breadcrumb;
            $scope.table = response.data.table;
            $scope.dataROMBEL_AS = response.data.rombel;

            $scope.appReady = true;
        }

        $scope.pilihFilter = function (form) {
            if (form.$valid) {
                getDataSantri();
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function getDataSantri() {
            $http.post($scope.mainURI + '/datatable', $scope.formData).then(callbackDatatablesSantri, notificationService.errorCallback);
        }

        function callbackDatatablesSantri(response) {
            $scope.dataOriginal = response.data.data;

            var initialParams = {
                count: 15
            };
            var initialSettings = {
                counts: [],
                dataset: response.data.data
            };

            $scope.dataTablesSantri = new NgTableParams(initialParams, initialSettings);
            $scope.fabHidden = false;
            $scope.formReady = true;

            setInputNilai();
        }

        function setInputNilai() {
            angular.forEach($scope.dataOriginal, function (item, index) {
                $scope.formDataNilai.NILAI_NILAI[item.ID_AS] = item.NILAI_NILAI;
            });
        }

        $scope.prosesNilai = function (row) {
            var dataSantri = {
                SANTRI_NILAI: row.ID_AS,
                JADWAL_NILAI: $scope.formData.JADWAL_NILAI,
                NILAI_NILAI: $scope.formDataNilai.NILAI_NILAI[row.ID_AS],
            };
            $http.post($scope.mainURI + '/proses_nilai', dataSantri).then(callbackSuccessProsesSantri, notificationService.errorCallback);
        }

        function callbackSuccessProsesSantri(response) {
            notificationService.toastSimple(response.data.notification);
        }

        $scope.menuItems = [
            {id: "add_data", name: "Tambah Data", icon: "add"},
            {id: "download_data", name: "Unduh Data", icon: "file_download"},
            // {id: "print_data", name: "Catak Data", icon: "print"},
            {id: "reload_data", name: "Muat Ulang Data", icon: "refresh"},
            {id: "reload_page", name: "Muat Ulang Halaman", icon: "autorenew"},
            {id: "request_doc", name: "Dokumentasi", icon: "help"},
        ];

        $scope.openDialog = function ($event, item) {
            if (item.id === 'reload_data') {
                $scope.fabHidden = true;
                getDataSantri();
            } else if (item.id === 'reload_page') {
                reloadPage();
            } else if (item.id === 'request_doc') {
                $mdSidenav('right').toggle();
            } else if (item.id === 'add_data') {
                createDialog($event, 'form');
            } else if (item.id === 'print_data') {
                var mywindow = window.open('', 'PRINT', 'height=600,width=700');

                mywindow.document.write('<html><head><title>' + document.title + '</title><style type="text/css">body{font-family: "Roboto",Arial,sans-serif;overflow:visible;}.ng-table-filters,.ng-table-counts{display: none;} tr {border-top: 1px solid #f2f6f9;} .data-table{overflow: visible;} table{overflow:visible;}body, h1, h2, h3, ol, ul, div {     width: auto;     border: 0;     margin: 0 5%;     padding: 0;     float: none;     position: static;     overflow: visible; }</style>');
                mywindow.document.write('</head><body onload="window.print()">');
                mywindow.document.write('<h1>' + document.title + '</h1>');
                mywindow.document.write(document.getElementById('printable').innerHTML);
                mywindow.document.write('</body></html>');

                mywindow.document.close();
                mywindow.focus();

                return true;
            } else if (item.id === 'download_data') {
                if ($scope.dataOriginal === null)
                    notificationService.toastSimple('Data tidak ditemukan');
                else
                    alasql('SELECT * INTO XLSX("data_download.xlsx",{headers:true}) FROM ?', [$scope.dataOriginal]);
            }
        };

        function reloadPage() {
            var currentPageTemplate = $route.current.templateUrl;
            $templateCache.remove(currentPageTemplate);
            $route.reload();
        }

        function createDialog(event, mode) {
            $mdDialog
                    .show({
                        controller: DialogController,
                        clickOutsideToClose: false,
                        templateUrl: $scope.mainTemplate + '-' + mode + '.html',
                        targetEvent: event
                    })
                    .then(
                            function (text) {
                                notificationService.toastSimple(text);
                                getDataSantri();
                            },
                            function () {
                                // CANCEL DIALOG
                            }
                    );
        }

        function DialogController($scope, $mdDialog) {
            $scope.cancelSumbit = function () {
                dataScopeShared.addData('DATA_UPDATE', null);
                $mdDialog.cancel();
            };
        }
    });

    angular.module('mainApp').controller('akadRaporController', function ($scope, $routeParams, $http, notificationService, NgTableParams, $mdDialog, url_template, $timeout, $mdSidenav, $route, $templateCache, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.mainTemplate = url_template + $routeParams.template;
        $scope.appReady = false;
        $scope.dataOriginal = null;
        $scope.flex = 90;
        $scope.flexOffset = 5;

        $scope.flexKelas = 20;
        $scope.flexSantri = 40;
        $scope.flexNilai = 30;

        $scope.fabHidden = true;

        $scope.NAMA_KELAS = null;
        $scope.NAMA_SANTRI = null;
        $scope.TABLE_SANTRI_SHOW = false;
        $scope.TABLE_NILAI_SHOW = false;

        $http.get($scope.mainURI + '/index').then(callbackSuccess, notificationService.errorCallback);

        function callbackSuccess(response) {
            $scope.title = response.data.title;
            $scope.breadcrumb = response.data.breadcrumb;
            $scope.tableKelas = response.data.table.kelas;
            $scope.tableSantri = response.data.table.santri;
            $scope.tableNilai = response.data.table.nilai;

            $scope.appReady = true;

            getDataKelas();
        }

        function getDataKelas() {
            $http.post($scope.mainURI + '/get_datatable_kelas', $scope.formData).then(callbackDatatablesKelas, notificationService.errorCallback);
        }

        function callbackDatatablesKelas(response) {
            $scope.dataOriginal = response.data.data;

            var initialParams = {
                count: 15
            };
            var initialSettings = {
                counts: [],
                dataset: response.data.data
            };

            $scope.dataTablesKelas = new NgTableParams(initialParams, initialSettings);
        }

        $scope.datatableSantri = function (row) {
            $scope.TABLE_SANTRI_SHOW = false;
            $scope.TABLE_NILAI_SHOW = false;

            $scope.NAMA_KELAS = row.NAMA_KELAS + ' - ' + row.NAMA_KEGIATAN;

            $http.post($scope.mainURI + '/get_datatable_santri', row).then(callbackDatatablesSantri, notificationService.errorCallback);
        }

        function callbackDatatablesSantri(response) {
            $scope.dataOriginal = response.data.data;

            var initialParams = {
                count: 15
            };
            var initialSettings = {
                counts: [],
                dataset: response.data.data
            };

            $scope.dataTablesSantri = new NgTableParams(initialParams, initialSettings);

            $scope.TABLE_SANTRI_SHOW = true;
        }

        $scope.datatableNilai = function (row) {
            $scope.TABLE_NILAI_SHOW = false;

            $scope.NAMA_SANTRI = (row.NIS_SANTRI === null ? '' : row.NIS_SANTRI) + ' - ' + row.NAMA_SANTRI;

            $http.post($scope.mainURI + '/get_datatable_nilai', row).then(callbackDatatablesNilai, notificationService.errorCallback);
        }

        function callbackDatatablesNilai(response) {
            $scope.dataOriginal = response.data.data;

            var initialParams = {
                count: 15
            };
            var initialSettings = {
                counts: [],
                dataset: response.data.data
            };

            $scope.dataTablesNilai = new NgTableParams(initialParams, initialSettings);

            $scope.TABLE_NILAI_SHOW = true;
        }
    });

    angular.module('mainApp').controller('akadKelasController', function ($scope, formHelper, notificationService, $routeParams, $http, $mdDialog, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.ajaxRunning = true;
        $scope.dataUpdate = dataScopeShared.getData('DATA_UPDATE');
        $scope.addForm = true;

        $scope.formData = {
            ID_KELAS: null,
            KEGIATAN_KELAS: null,
            NAMA_KELAS: null,
            KETERANGAN_KELAS: null,
            KODE_EMIS_KELAS: null,
        };

        $http.get($scope.mainURI + '/form').then(callbackForm, notificationService.errorCallback);

        function callbackForm(response) {
            callbackFormData(response);
        }

        function callbackFormData(response) {
            $scope.dataKEGIATAN_KELAS = response.data.dataKEGIATAN_KELAS;

            if ($scope.dataUpdate === null || typeof $scope.dataUpdate === 'undefined')
                formReady();
            else
                getData();
        }

        function getData() {
            $http.post($scope.mainURI + '/view', $scope.dataUpdate).then(callbackSuccessData, notificationService.errorCallback);
        }

        function callbackSuccessData(response) {
            $scope.formData.ID_KELAS = response.data.ID_KELAS;
            $scope.formData.KEGIATAN_KELAS = response.data.KEGIATAN_KELAS;
            $scope.formData.NAMA_KELAS = response.data.NAMA_KELAS;
            $scope.formData.KETERANGAN_KELAS = response.data.KETERANGAN_KELAS;
            $scope.formData.KODE_EMIS_KELAS = response.data.KODE_EMIS_KELAS;

            $scope.addForm = false;

            formReady();
        }

        function formReady() {
            $scope.ajaxRunning = false;
        }

        $scope.cancelSumbit = function () {
            dataScopeShared.addData('DATA_UPDATE', null);
            $mdDialog.cancel();
        };

        $scope.saveSubmit = function () {
            if ($scope.form.$valid) {
                $scope.ajaxRunning = true;

                $http.post($scope.mainURI + '/save', $scope.formData).then(callbackSuccessSaving, notificationService.errorCallback);
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function callbackSuccessSaving(response) {
            $scope.ajaxRunning = false;
            $mdDialog.hide(response.data.notification);
            dataScopeShared.addData('DATA_UPDATE', null);
        }
    });

    angular.module('mainApp').controller('akadTagihanController', function ($scope, formHelper, notificationService, $routeParams, $http, $mdDialog, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.ajaxRunning = true;
        $scope.dataUpdate = dataScopeShared.getData('DATA_UPDATE');
        $scope.addForm = true;

        $scope.formData = {
            ID_TAGIHAN: null,
            KELAS_TAGIHAN: null,
            NAMA_TAGIHAN: null,
            NOMINAL_TAGIHAN: null,
        };

        $http.get($scope.mainURI + '/form').then(callbackForm, notificationService.errorCallback);

        function callbackForm(response) {
            callbackFormData(response);
        }

        function callbackFormData(response) {
            $scope.dataKELAS_TAGIHAN = response.data.dataKELAS_TAGIHAN;

            if ($scope.dataUpdate === null || typeof $scope.dataUpdate === 'undefined')
                formReady();
            else
                getData();
        }

        function getData() {
            $http.post($scope.mainURI + '/view', $scope.dataUpdate).then(callbackSuccessData, notificationService.errorCallback);
        }

        function callbackSuccessData(response) {
            $scope.formData = response.data;

            $scope.addForm = false;

            formReady();
        }

        function formReady() {
            $scope.ajaxRunning = false;
        }

        $scope.cancelSumbit = function () {
            dataScopeShared.addData('DATA_UPDATE', null);
            $mdDialog.cancel();
        };

        $scope.saveSubmit = function () {
            if ($scope.form.$valid) {
                $scope.ajaxRunning = true;

                $http.post($scope.mainURI + '/save', $scope.formData).then(callbackSuccessSaving, notificationService.errorCallback);
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function callbackSuccessSaving(response) {
            $scope.ajaxRunning = false;
            $mdDialog.hide(response.data.notification);
            dataScopeShared.addData('DATA_UPDATE', null);
        }
    });

    angular.module('mainApp').controller('keuTagihanController', function ($scope, $routeParams, $http, notificationService, NgTableParams, $mdDialog, url_template, $timeout, $mdSidenav, $route, $templateCache, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.mainTemplate = url_template + $routeParams.template;
        $scope.appReady = false;
        $scope.dataOriginal = null;
        $scope.flex = 90;
        $scope.flexOffset = 5;

        $scope.fabHidden = true;
        $scope.fabIsOpen = false;
        $scope.fabHover = false;

        $scope.formReady = false;

        $scope.formData = {
            ROMBEL_AS: null,
            TAGIHAN_KPC: null,
        };

        $scope.formDataTag = {
            TAGIHAN_KPC: [],
        };

        $scope.$watch('formData.TAGIHAN_KPC', function (ROMBEL_AS) {
            $scope.formReady = false;
        });

        $scope.$watch('formData.ROMBEL_AS', function (ROMBEL_AS) {
            $scope.formReady = false;
            $scope.formData.TAGIHAN_KPC = null;

            if (ROMBEL_AS !== null)
                $http.post($scope.mainURI + '/get_tagihan', $scope.formData).then(callbackSuccessTagihan, notificationService.errorCallback);
        });

        function callbackSuccessTagihan(response) {
            $scope.dataTAGIHAN_KPC = response.data;
        }

        $http.get($scope.mainURI + '/index').then(callbackSuccess, notificationService.errorCallback);

        function callbackSuccess(response) {
            $scope.title = response.data.title;
            $scope.breadcrumb = response.data.breadcrumb;
            $scope.table = response.data.table;
            $scope.dataROMBEL_AS = response.data.rombel;

            $scope.appReady = true;
        }

        $scope.pilihFilter = function (form) {
            if (form.$valid) {
                getDataSantri();
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function getDataSantri() {
            if ($scope.formData.TAGIHAN_KPC !== '')
                $http.post($scope.mainURI + '/datatable', $scope.formData).then(callbackDatatablesSantri, notificationService.errorCallback);
            else
                notificationService.toastSimple('Tidak ada tagihan pada rombel tersebut.');
        }

        function callbackDatatablesSantri(response) {
            $scope.dataOriginal = response.data.data;

            var initialParams = {
                count: 15
            };
            var initialSettings = {
                counts: [],
                dataset: response.data.data
            };

            $scope.dataTablesSantri = new NgTableParams(initialParams, initialSettings);
            $scope.fabHidden = false;
            $scope.formReady = true;

            setDatatableKeu();
        }

        function setDatatableKeu() {
            angular.forEach($scope.dataOriginal, function (item, index) {
                $scope.formDataTag.TAGIHAN_KPC[item.ID_SANTRI] = item.TAGIHAN_KPC_SHOW === null ? true : false;
            });
        }

        $scope.prosesTagihan = function (row) {
            var dataSantri = {
                SANTRI_KPC: row.ID_SANTRI,
                STATUS_TAGIHAN: $scope.formDataTag.TAGIHAN_KPC[parseInt(row.ID_SANTRI)],
                TAGIHAN_KPC: $scope.formData.TAGIHAN_KPC,
            };

            $http.post($scope.mainURI + '/prosesTagihan', dataSantri).then(callbackSuccessProsesSantri, notificationService.errorCallback);
        }

        function callbackSuccessProsesSantri(response) {
            notificationService.toastSimple(response.data.notification);
        }

        $scope.menuItems = [
            {id: "add_data", name: "Tambah Data", icon: "add"},
            {id: "download_data", name: "Unduh Data", icon: "file_download"},
            // {id: "print_data", name: "Catak Data", icon: "print"},
            {id: "reload_data", name: "Muat Ulang Data", icon: "refresh"},
            {id: "reload_page", name: "Muat Ulang Halaman", icon: "autorenew"},
            {id: "request_doc", name: "Dokumentasi", icon: "help"},
        ];

        $scope.openDialog = function ($event, item) {
            if (item.id === 'reload_data') {
                $scope.fabHidden = true;
                getDataSantri();
            } else if (item.id === 'reload_page') {
                reloadPage();
            } else if (item.id === 'request_doc') {
                $mdSidenav('right').toggle();
            } else if (item.id === 'add_data') {
                createDialog($event, 'form');
            } else if (item.id === 'print_data') {
                var mywindow = window.open('', 'PRINT', 'height=600,width=700');

                mywindow.document.write('<html><head><title>' + document.title + '</title><style type="text/css">body{font-family: "Roboto",Arial,sans-serif;overflow:visible;}.ng-table-filters,.ng-table-counts{display: none;} tr {border-top: 1px solid #f2f6f9;} .data-table{overflow: visible;} table{overflow:visible;}body, h1, h2, h3, ol, ul, div {     width: auto;     border: 0;     margin: 0 5%;     padding: 0;     float: none;     position: static;     overflow: visible; }</style>');
                mywindow.document.write('</head><body onload="window.print()">');
                mywindow.document.write('<h1>' + document.title + '</h1>');
                mywindow.document.write(document.getElementById('printable').innerHTML);
                mywindow.document.write('</body></html>');

                mywindow.document.close();
                mywindow.focus();

                return true;
            } else if (item.id === 'download_data') {
                if ($scope.dataOriginal === null)
                    notificationService.toastSimple('Data tidak ditemukan');
                else
                    alasql('SELECT * INTO XLSX("data_download.xlsx",{headers:true}) FROM ?', [$scope.dataOriginal]);
            }
        };

        function reloadPage() {
            var currentPageTemplate = $route.current.templateUrl;
            $templateCache.remove(currentPageTemplate);
            $route.reload();
        }

        function createDialog(event, mode) {
            $mdDialog
                    .show({
                        controller: DialogController,
                        clickOutsideToClose: false,
                        templateUrl: $scope.mainTemplate + '-' + mode + '.html',
                        targetEvent: event
                    })
                    .then(
                            function (text) {
                                notificationService.toastSimple(text);
                                getDataSantri();
                            },
                            function () {
                                // CANCEL DIALOG
                            }
                    );
        }

        function DialogController($scope, $mdDialog) {
            $scope.cancelSumbit = function () {
                dataScopeShared.addData('DATA_UPDATE', null);
                $mdDialog.cancel();
            };
        }
    });

    angular.module('mainApp').controller('keuPembayaranController', function ($scope, $routeParams, $http, notificationService, NgTableParams, $mdDialog, url_template, $timeout, $mdSidenav, $route, $templateCache, dataScopeShared, formHelper) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.mainTemplate = url_template + $routeParams.template;
        $scope.appReady = false;
        $scope.dataOriginal = null;
        $scope.flex = 80;
        $scope.flexOffset = 10;

        $scope.fabHidden = true;
        $scope.fabIsOpen = false;
        $scope.fabHover = false;

        $scope.formReady = false;
        $scope.total_tagihan = 0;
        $scope.data_tagihan = [];

        $scope.formData = {
            ID_SANTRI: null,
        };

        $scope.formDataTag = {
            TAGIHAN_BAYAR: [],
            NOMINAL_TAGIHAN: [],
        };

        $scope.$watch('formData.ID_SANTRI', function (ID_SANTRI) {
            $scope.formReady = false;

            getTagihan();
        });

        $http.get($scope.mainURI + '/index').then(callbackSuccess, notificationService.errorCallback);

        function callbackSuccess(response) {
            $scope.title = response.data.title;
            $scope.breadcrumb = response.data.breadcrumb;
            $scope.table = response.data.table;
            $scope.ID_SANTRI = {
                dataAutocomplete: response.data.santri
            };
            $scope.ID_SANTRI = formHelper.autocomplete($scope.ID_SANTRI);

            $scope.appReady = true;
        }

        $scope.$watch('ID_SANTRI.selectedItem', function (selectedItem) {
            if (typeof selectedItem === 'undefined' || selectedItem.key === null || selectedItem === null) {
                $scope.formData.ID_SANTRI = null;
            } else {
                $scope.formData.ID_SANTRI = selectedItem.key;
            }
        });

        $scope.pilihFilter = function (form) {
            if (form.ID_SANTRI.$valid) {
                getTagihan();
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function getTagihan() {
            if ($scope.formData.ID_SANTRI !== null)
                $http.post($scope.mainURI + '/get_tagihan', $scope.formData).then(callbackSuccessTagihan, notificationService.errorCallback);
        }

        function callbackSuccessTagihan(response) {
            $scope.dataOriginal = response.data.data;

            var initialParams = {
                count: 15
            };
            var initialSettings = {
                counts: [],
                dataset: response.data.data
            };

            $scope.dataTablesTagihan = new NgTableParams(initialParams, initialSettings);
            $scope.fabHidden = false;
            $scope.formReady = true;

            $scope.total_tagihan = 0;
            setDatatableKeu();
        }

        function setDatatableKeu() {
            angular.forEach($scope.dataOriginal, function (item, index) {
                $scope.formDataTag.TAGIHAN_BAYAR[item.ID_TAGIHAN] = item.TAGIHAN_BAYAR === null ? false : true;
                $scope.formDataTag.NOMINAL_TAGIHAN[item.ID_TAGIHAN] = parseInt(item.NOMINAL_TAGIHAN);
            });
        }

        $scope.prosesPembayaran = function (ev) {
            if ($scope.data_tagihan.length > 0) {
                var confirm = $mdDialog.confirm()
                        .title('Santri membayar sebanyak Rp ' + $scope.total_tagihan + '. Simpan pembayaran?')
                        .targetEvent(ev)
                        .ok('YA')
                        .cancel('TIDAK');

                $mdDialog.show(confirm).then(function () {
                    var dataSantri = {
                        DATA_TAGIHAN: $scope.data_tagihan,
                        SANTRI_BAYAR: $scope.formData.ID_SANTRI,
                    };

                    $http.post($scope.mainURI + '/prosesPembayaran', dataSantri).then(callbackSuccessProsesSantri, notificationService.errorCallback);
                }, function () {

                });
            } else {
                notificationService.toastSimple('Silahkan pilih tagihan yang akan dibayar terlebih dahulu');
            }
        }

        function callbackSuccessProsesSantri(response) {
            $scope.hapusInput();

            notificationService.toastSimple(response.data.notification);
        }

        $scope.hitungTagihan = function () {
            $scope.total_tagihan = 0;

            $scope.data_tagihan = [];

            angular.forEach($scope.formDataTag.TAGIHAN_BAYAR, function (status, id) {
                if (status) {
                    $scope.data_tagihan.push(id);
                    $scope.total_tagihan += $scope.formDataTag.NOMINAL_TAGIHAN[id];
                }
            });

        }

        $scope.hapusInput = function () {
            $scope.formDataTag.TAGIHAN_BAYAR = [];
            $scope.ID_SANTRI.selectedItem = null;
            $scope.formData.ID_SANTRI = null;
        }

        $scope.menuItems = [
            {id: "add_data", name: "Tambah Data", icon: "add"},
            {id: "download_data", name: "Unduh Data", icon: "file_download"},
            // {id: "print_data", name: "Catak Data", icon: "print"},
            {id: "reload_data", name: "Muat Ulang Data", icon: "refresh"},
            {id: "reload_page", name: "Muat Ulang Halaman", icon: "autorenew"},
            {id: "request_doc", name: "Dokumentasi", icon: "help"},
        ];

        $scope.openDialog = function ($event, item) {
            if (item.id === 'reload_data') {
                $scope.fabHidden = true;
                getDataSantri();
            } else if (item.id === 'reload_page') {
                reloadPage();
            } else if (item.id === 'request_doc') {
                $mdSidenav('right').toggle();
            } else if (item.id === 'add_data') {
                createDialog($event, 'form');
            } else if (item.id === 'print_data') {
                var mywindow = window.open('', 'PRINT', 'height=600,width=700');

                mywindow.document.write('<html><head><title>' + document.title + '</title><style type="text/css">body{font-family: "Roboto",Arial,sans-serif;overflow:visible;}.ng-table-filters,.ng-table-counts{display: none;} tr {border-top: 1px solid #f2f6f9;} .data-table{overflow: visible;} table{overflow:visible;}body, h1, h2, h3, ol, ul, div {     width: auto;     border: 0;     margin: 0 5%;     padding: 0;     float: none;     position: static;     overflow: visible; }</style>');
                mywindow.document.write('</head><body onload="window.print()">');
                mywindow.document.write('<h1>' + document.title + '</h1>');
                mywindow.document.write(document.getElementById('printable').innerHTML);
                mywindow.document.write('</body></html>');

                mywindow.document.close();
                mywindow.focus();

                return true;
            } else if (item.id === 'download_data') {
                if ($scope.dataOriginal === null)
                    notificationService.toastSimple('Data tidak ditemukan');
                else
                    alasql('SELECT * INTO XLSX("data_download.xlsx",{headers:true}) FROM ?', [$scope.dataOriginal]);
            }
        };

        function reloadPage() {
            var currentPageTemplate = $route.current.templateUrl;
            $templateCache.remove(currentPageTemplate);
            $route.reload();
        }

        function createDialog(event, mode) {
            $mdDialog
                    .show({
                        controller: DialogController,
                        clickOutsideToClose: false,
                        templateUrl: $scope.mainTemplate + '-' + mode + '.html',
                        targetEvent: event
                    })
                    .then(
                            function (text) {
                                notificationService.toastSimple(text);
                                getDataSantri();
                            },
                            function () {
                                // CANCEL DIALOG
                            }
                    );
        }

        function DialogController($scope, $mdDialog) {
            $scope.cancelSumbit = function () {
                dataScopeShared.addData('DATA_UPDATE', null);
                $mdDialog.cancel();
            };
        }
    });

    angular.module('mainApp').controller('perpusJenisController', function ($scope, formHelper, notificationService, $routeParams, $http, $mdDialog, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.ajaxRunning = true;
        $scope.dataUpdate = dataScopeShared.getData('DATA_UPDATE');
        $scope.addForm = true;

        $scope.formData = {
            ID_PJB: null,
            NAMA_PJB: null,
        };

        $http.get($scope.mainURI + '/form').then(callbackForm, notificationService.errorCallback);

        function callbackForm(response) {
            callbackFormData(response);
        }

        function callbackFormData(response) {
            if ($scope.dataUpdate === null || typeof $scope.dataUpdate === 'undefined')
                formReady();
            else
                getData();
        }

        function getData() {
            $http.post($scope.mainURI + '/view', $scope.dataUpdate).then(callbackSuccessData, notificationService.errorCallback);
        }

        function callbackSuccessData(response) {
            $scope.formData.ID_PJB = response.data.ID_PJB;
            $scope.formData.NAMA_PJB = response.data.NAMA_PJB;

            $scope.addForm = false;

            formReady();
        }

        function formReady() {
            $scope.ajaxRunning = false;
        }

        $scope.cancelSumbit = function () {
            dataScopeShared.addData('DATA_UPDATE', null);
            $mdDialog.cancel();
        };

        $scope.saveSubmit = function () {
            if ($scope.form.$valid) {
                $scope.ajaxRunning = true;

                $http.post($scope.mainURI + '/save', $scope.formData).then(callbackSuccessSaving, notificationService.errorCallback);
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function callbackSuccessSaving(response) {
            $scope.ajaxRunning = false;
            $mdDialog.hide(response.data.notification);
            dataScopeShared.addData('DATA_UPDATE', null);
        }
    });

    angular.module('mainApp').controller('perpusBukuController', function ($scope, formHelper, notificationService, $routeParams, $http, $mdDialog, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.ajaxRunning = true;
        $scope.dataUpdate = dataScopeShared.getData('DATA_UPDATE');
        $scope.addForm = true;
        $scope.flex = 80;
        $scope.flexOffset = 10;

        $scope.formData = {
            ID_BUKU: null,
            KODE_BUKU: null,
            JENIS_BUKU: null,
            NAMA_BUKU: null,
            PENGARANG_BUKU: null,
            PENERBIT_BUKU: null,
            STOK_BUKU: null,
            KETERANGAN_BUKU: null,
        };

        $http.get($scope.mainURI + '/form').then(callbackForm, notificationService.errorCallback);

        function callbackForm(response) {
            callbackFormData(response);
        }

        function callbackFormData(response) {
            $scope.dataJENIS_BUKU = response.data.jenis;

            if ($scope.dataUpdate === null || typeof $scope.dataUpdate === 'undefined')
                formReady();
            else
                getData();
        }

        function getData() {
            $http.post($scope.mainURI + '/view', $scope.dataUpdate).then(callbackSuccessData, notificationService.errorCallback);
        }

        function callbackSuccessData(response) {
            $scope.formData = response.data;

            $scope.addForm = false;

            formReady();
        }

        function formReady() {
            $scope.ajaxRunning = false;
        }

        $scope.cancelSumbit = function () {
            dataScopeShared.addData('DATA_UPDATE', null);
            $mdDialog.cancel();
        };

        $scope.saveSubmit = function () {
            if ($scope.form.$valid) {
                $scope.ajaxRunning = true;

                $http.post($scope.mainURI + '/save', $scope.formData).then(callbackSuccessSaving, notificationService.errorCallback);
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function callbackSuccessSaving(response) {
            $scope.ajaxRunning = false;
            $mdDialog.hide(response.data.notification);
            dataScopeShared.addData('DATA_UPDATE', null);
        }
    });

    angular.module('mainApp').controller('perpusPeminajamanController', function ($scope, $routeParams, $http, notificationService, NgTableParams, $mdDialog, url_template, $timeout, $mdSidenav, $route, $templateCache, dataScopeShared, formHelper) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.mainTemplate = url_template + $routeParams.template;
        $scope.appReady = false;
        $scope.dataOriginal = null;
        $scope.flex = 80;
        $scope.flexOffset = 10;

        $scope.fabHidden = true;
        $scope.fabIsOpen = false;
        $scope.fabHover = false;

        $scope.formReady = false;
        $scope.data_pinjaman = [];
        $scope.data_buku = [];

        $scope.formData = {
            ID_SANTRI: null,
            ID_BUKU: null,
        };

        $http.get($scope.mainURI + '/index').then(callbackSuccess, notificationService.errorCallback);

        function callbackSuccess(response) {
            $scope.title = response.data.title;
            $scope.breadcrumb = response.data.breadcrumb;
            $scope.table = response.data.table;

            $scope.ID_SANTRI = {
                dataAutocomplete: response.data.santri
            };
            $scope.ID_SANTRI = formHelper.autocomplete($scope.ID_SANTRI);

            $scope.ID_BUKU = {
                dataAutocomplete: response.data.buku
            };
            $scope.ID_BUKU = formHelper.autocomplete($scope.ID_BUKU);

            $scope.appReady = true;
        }

        $scope.$watch('ID_SANTRI.selectedItem', function (selectedItem) {
            if (typeof selectedItem === 'undefined' || selectedItem.key === null || selectedItem === null) {
                $scope.formData.ID_SANTRI = null;
            } else {
                $scope.formData.ID_SANTRI = selectedItem.key;

                $scope.formReady = true;
            }
        });

        $scope.$watch('ID_BUKU.selectedItem', function (selectedItem) {
            if (typeof selectedItem === 'undefined' || selectedItem.key === null || selectedItem === null) {
                $scope.formData.ID_BUKU = null;
            } else {
                $scope.formData.ID_BUKU = selectedItem.key;
            }
        });

        $scope.tambahPeminjaman = function (form) {
            if (form.ID_BUKU.$valid) {
                $scope.data_pinjaman.push($scope.formData.ID_BUKU);

                getBuku();
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function getBuku() {
            var formDataBuku = {
                ID_BUKU: $scope.formData.ID_BUKU
            };
            $http.post($scope.mainURI + '/get_buku', formDataBuku).then(callbackSuccessBuku, notificationService.errorCallback);
        }

        function callbackSuccessBuku(response) {
            $scope.data_buku.push(response.data);

            $scope.ID_BUKU.selectedItem = null;
        }

        $scope.hapusBuku = function (index, ev) {
            var confirm = $mdDialog.confirm()
                    .title('Apakan Anda akan menghapus buku tsb?')
                    .targetEvent(ev)
                    .ok('YA')
                    .cancel('TIDAK');

            $mdDialog.show(confirm).then(function () {
                $scope.data_buku.splice(index, 1);
                $scope.data_pinjaman.splice(index, 1);
            }, function () {

            });
        };

        $scope.prosesPeminjaman = function (ev) {
            if ($scope.data_pinjaman.length > 0) {
                var confirm = $mdDialog.confirm()
                        .title('Apakan Anda akan menyimpan pinjaman buku santri?')
                        .targetEvent(ev)
                        .ok('YA')
                        .cancel('TIDAK');

                $mdDialog.show(confirm).then(function () {
                    var dataSantri = {
                        DATA_PINJAMAN: $scope.data_pinjaman,
                        ID_SANTRI: $scope.formData.ID_SANTRI,
                    };

                    $http.post($scope.mainURI + '/proses_peminjaman', dataSantri).then(callbackSuccessProsesSantri, notificationService.errorCallback);
                }, function () {

                });
            } else {
                notificationService.toastSimple('Silahkan pilih buku yang akan dipinjam terlebih dahulu');
            }
        }

        function callbackSuccessProsesSantri(response) {
            reloadPage();

            notificationService.toastSimple(response.data.notification);
        }

        $scope.hapusInput = function () {
            $scope.data_pinjaman = [];
            $scope.data_buku = [];
            $scope.formData.ID_SANTRI = null;
            $scope.ID_SANTRI.selectedItem = null;
        };

        $scope.menuItems = [
            {id: "add_data", name: "Tambah Data", icon: "add"},
            {id: "download_data", name: "Unduh Data", icon: "file_download"},
            // {id: "print_data", name: "Catak Data", icon: "print"},
            {id: "reload_data", name: "Muat Ulang Data", icon: "refresh"},
            {id: "reload_page", name: "Muat Ulang Halaman", icon: "autorenew"},
            {id: "request_doc", name: "Dokumentasi", icon: "help"},
        ];

        $scope.openDialog = function ($event, item) {
            if (item.id === 'reload_data') {
                $scope.fabHidden = true;
                getDataSantri();
            } else if (item.id === 'reload_page') {
                reloadPage();
            } else if (item.id === 'request_doc') {
                $mdSidenav('right').toggle();
            } else if (item.id === 'add_data') {
                createDialog($event, 'form');
            } else if (item.id === 'print_data') {
                var mywindow = window.open('', 'PRINT', 'height=600,width=700');

                mywindow.document.write('<html><head><title>' + document.title + '</title><style type="text/css">body{font-family: "Roboto",Arial,sans-serif;overflow:visible;}.ng-table-filters,.ng-table-counts{display: none;} tr {border-top: 1px solid #f2f6f9;} .data-table{overflow: visible;} table{overflow:visible;}body, h1, h2, h3, ol, ul, div {     width: auto;     border: 0;     margin: 0 5%;     padding: 0;     float: none;     position: static;     overflow: visible; }</style>');
                mywindow.document.write('</head><body onload="window.print()">');
                mywindow.document.write('<h1>' + document.title + '</h1>');
                mywindow.document.write(document.getElementById('printable').innerHTML);
                mywindow.document.write('</body></html>');

                mywindow.document.close();
                mywindow.focus();

                return true;
            } else if (item.id === 'download_data') {
                if ($scope.dataOriginal === null)
                    notificationService.toastSimple('Data tidak ditemukan');
                else
                    alasql('SELECT * INTO XLSX("data_download.xlsx",{headers:true}) FROM ?', [$scope.dataOriginal]);
            }
        };

        function reloadPage() {
            var currentPageTemplate = $route.current.templateUrl;
            $templateCache.remove(currentPageTemplate);
            $route.reload();
        }

        function createDialog(event, mode) {
            $mdDialog
                    .show({
                        controller: DialogController,
                        clickOutsideToClose: false,
                        templateUrl: $scope.mainTemplate + '-' + mode + '.html',
                        targetEvent: event
                    })
                    .then(
                            function (text) {
                                notificationService.toastSimple(text);
                                getDataSantri();
                            },
                            function () {
                                // CANCEL DIALOG
                            }
                    );
        }

        function DialogController($scope, $mdDialog) {
            $scope.cancelSumbit = function () {
                dataScopeShared.addData('DATA_UPDATE', null);
                $mdDialog.cancel();
            };
        }
    });

    angular.module('mainApp').controller('perpusPengembalianController', function ($scope, $routeParams, $http, notificationService, NgTableParams, $mdDialog, url_template, $timeout, $mdSidenav, $route, $templateCache, dataScopeShared, formHelper) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.mainTemplate = url_template + $routeParams.template;
        $scope.appReady = false;
        $scope.dataOriginal = null;
        $scope.flex = 80;
        $scope.flexOffset = 10;

        $scope.fabHidden = true;
        $scope.fabIsOpen = false;
        $scope.fabHover = false;
        $scope.formReady = false;

        $scope.ID_PINJAM = null;

        $http.get($scope.mainURI + '/index').then(callbackSuccess, notificationService.errorCallback);

        function callbackSuccess(response) {
            $scope.title = response.data.title;
            $scope.breadcrumb = response.data.breadcrumb;
            $scope.ID_BUKU = {
                dataAutocomplete: response.data.buku
            };
            $scope.ID_BUKU = formHelper.autocomplete($scope.ID_BUKU);

            $scope.appReady = true;
        }

        $scope.$watch('ID_BUKU.selectedItem', function (selectedItem) {
            if (!(typeof selectedItem === 'undefined' || selectedItem.key === null || selectedItem === null)) {
                $scope.ID_PINJAM = selectedItem.key;
            } else {
                $scope.ID_PINJAM = null;
            }
        });

        $scope.prosesPengembalian = function (ev) {
            if ($scope.ID_PINJAM === null) {
                notificationService.toastSimple('Silahkan pilih buku terlebih dahulu');
            } else {
                var confirm = $mdDialog.confirm()
                        .title('Apakan Anda akan menyimpan pengembalian buku tsb?')
                        .targetEvent(ev)
                        .ok('YA')
                        .cancel('TIDAK');

                $mdDialog.show(confirm).then(function () {
                    var dataSantri = {
                        ID_PINJAM: $scope.ID_PINJAM,
                    };

                    $http.post($scope.mainURI + '/proses_pengembalian', dataSantri).then(callbackSuccessProsesSantri, notificationService.errorCallback);
                }, function () {

                });
            }
        }

        function callbackSuccessProsesSantri(response) {
            reloadPage();

            notificationService.toastSimple(response.data.notification);
        }

        $scope.menuItems = [
            {id: "add_data", name: "Tambah Data", icon: "add"},
            {id: "download_data", name: "Unduh Data", icon: "file_download"},
            // {id: "print_data", name: "Catak Data", icon: "print"},
            {id: "reload_data", name: "Muat Ulang Data", icon: "refresh"},
            {id: "reload_page", name: "Muat Ulang Halaman", icon: "autorenew"},
            {id: "request_doc", name: "Dokumentasi", icon: "help"},
        ];

        $scope.openDialog = function ($event, item) {
            if (item.id === 'reload_data') {
                $scope.fabHidden = true;
                getDataSantri();
            } else if (item.id === 'reload_page') {
                reloadPage();
            } else if (item.id === 'request_doc') {
                $mdSidenav('right').toggle();
            } else if (item.id === 'add_data') {
                createDialog($event, 'form');
            } else if (item.id === 'print_data') {
                var mywindow = window.open('', 'PRINT', 'height=600,width=700');

                mywindow.document.write('<html><head><title>' + document.title + '</title><style type="text/css">body{font-family: "Roboto",Arial,sans-serif;overflow:visible;}.ng-table-filters,.ng-table-counts{display: none;} tr {border-top: 1px solid #f2f6f9;} .data-table{overflow: visible;} table{overflow:visible;}body, h1, h2, h3, ol, ul, div {     width: auto;     border: 0;     margin: 0 5%;     padding: 0;     float: none;     position: static;     overflow: visible; }</style>');
                mywindow.document.write('</head><body onload="window.print()">');
                mywindow.document.write('<h1>' + document.title + '</h1>');
                mywindow.document.write(document.getElementById('printable').innerHTML);
                mywindow.document.write('</body></html>');

                mywindow.document.close();
                mywindow.focus();

                return true;
            } else if (item.id === 'download_data') {
                if ($scope.dataOriginal === null)
                    notificationService.toastSimple('Data tidak ditemukan');
                else
                    alasql('SELECT * INTO XLSX("data_download.xlsx",{headers:true}) FROM ?', [$scope.dataOriginal]);
            }
        };

        function reloadPage() {
            var currentPageTemplate = $route.current.templateUrl;
            $templateCache.remove(currentPageTemplate);
            $route.reload();
        }

        function createDialog(event, mode) {
            $mdDialog
                    .show({
                        controller: DialogController,
                        clickOutsideToClose: false,
                        templateUrl: $scope.mainTemplate + '-' + mode + '.html',
                        targetEvent: event
                    })
                    .then(
                            function (text) {
                                notificationService.toastSimple(text);
                                getDataSantri();
                            },
                            function () {
                                // CANCEL DIALOG
                            }
                    );
        }

        function DialogController($scope, $mdDialog) {
            $scope.cancelSumbit = function () {
                dataScopeShared.addData('DATA_UPDATE', null);
                $mdDialog.cancel();
            };
        }
    });

    angular.module('mainApp').controller('pelanggaranJenisController', function ($scope, formHelper, notificationService, $routeParams, $http, $mdDialog, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.ajaxRunning = true;
        $scope.dataUpdate = dataScopeShared.getData('DATA_UPDATE');
        $scope.addForm = true;
        $scope.flex = 80;
        $scope.flexOffset = 10;

        $scope.formData = {
            NAMA_PJS: null,
            POIN_PJS: null,
        };

        $http.get($scope.mainURI + '/form').then(callbackForm, notificationService.errorCallback);

        function callbackForm(response) {
            callbackFormData(response);
        }

        function callbackFormData(response) {
            $scope.dataJENIS_PJS = response.data.jenis;

            if ($scope.dataUpdate === null || typeof $scope.dataUpdate === 'undefined')
                formReady();
            else
                getData();
        }

        function getData() {
            $http.post($scope.mainURI + '/view', $scope.dataUpdate).then(callbackSuccessData, notificationService.errorCallback);
        }

        function callbackSuccessData(response) {
            $scope.formData = response.data;

            $scope.addForm = false;

            formReady();
        }

        function formReady() {
            $scope.ajaxRunning = false;
        }

        $scope.cancelSumbit = function () {
            dataScopeShared.addData('DATA_UPDATE', null);
            $mdDialog.cancel();
        };

        $scope.saveSubmit = function () {
            if ($scope.form.$valid) {
                $scope.ajaxRunning = true;

                $http.post($scope.mainURI + '/save', $scope.formData).then(callbackSuccessSaving, notificationService.errorCallback);
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function callbackSuccessSaving(response) {
            $scope.ajaxRunning = false;
            $mdDialog.hide(response.data.notification);
            dataScopeShared.addData('DATA_UPDATE', null);
        }
    });

    angular.module('mainApp').controller('pelanggaranDetailController', function ($scope, formHelper, notificationService, $routeParams, $http, $mdDialog, dataScopeShared, $q) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.ajaxRunning = true;
        $scope.dataUpdate = dataScopeShared.getData('DATA_UPDATE');
        $scope.addForm = true;
        $scope.dataForm = [];

        $scope.formData = {
            ID_PSN: null,
            TANGGAL_PSN: null,
            JENIS_PSN: null,
            SANTRI_PSN: null,
            KETERANGAN_PSN: null,
        };

        $http.get($scope.mainURI + '/form').then(callbackForm, notificationService.errorCallback);

        function callbackForm(response) {
            var urlGetDataForm = [];

            urlGetDataForm.push($http.get(response.data.uri.santri));
            urlGetDataForm.push($http.get(response.data.uri.jenis));

            $q.all(urlGetDataForm)
                    .then(
                            function (result) {
                                callbackFormData(result);
                            },
                            function (error) {
                                $scope.cancelSumbit();
                            }
                    );
        }

        function callbackFormData(response) {
            $scope.SANTRI_PSN = {
                dataAutocomplete: response[0].data
            };
            $scope.SANTRI_PSN = formHelper.autocomplete($scope.SANTRI_PSN);

            $scope.JENIS_PSN = {
                dataAutocomplete: response[1].data
            };
            $scope.JENIS_PSN = formHelper.autocomplete($scope.JENIS_PSN);

            if ($scope.dataUpdate === null || typeof $scope.dataUpdate === 'undefined')
                formReady();
            else
                getData();
        }

        function getData() {
            var urlDataForm = [];

            urlDataForm.push($http.post($scope.mainURI + '/view', $scope.dataUpdate));

            $q.all(urlDataForm)
                    .then(
                            function (result) {
                                callbackFormDataView(result);
                            },
                            function (error) {
                                $scope.cancelSumbit();
                            }
                    );
        }

        function callbackFormDataView(response) {
            angular.forEach($scope.SANTRI_PSN.dataAll, function (value, key) {
                if (parseInt(response[0].data.SANTRI_PSN) === parseInt(value.key)) {
                    $scope.SANTRI_PSN.selectedItem = value;
                }
            });

            angular.forEach($scope.JENIS_PSN.dataAll, function (value, key) {
                if (parseInt(response[0].data.JENIS_PSN) === parseInt(value.key)) {
                    $scope.JENIS_PSN.selectedItem = value;
                }
            });

            $scope.formData = response[0].data;

            $scope.addForm = false;

            formReady();
        }

        function formReady() {
            $scope.ajaxRunning = false;
        }

        $scope.cancelSumbit = function () {
            dataScopeShared.addData('DATA_UPDATE', null);
            $mdDialog.cancel();
        };

        $scope.saveSubmit = function () {
            if ($scope.form.JENIS_PSN.$valid
                    && $scope.form.SANTRI_PSN.$valid
                    && $scope.form.TANGGAL_PSN.$valid
                    && $scope.form.KETERANGAN_PSN.$valid
                    ) {
                $scope.ajaxRunning = true;

                $http.post($scope.mainURI + '/save', $scope.formData).then(callbackSuccessSaving, notificationService.errorCallback);
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function callbackSuccessSaving(response) {
            $scope.ajaxRunning = false;
            $mdDialog.hide(response.data.notification);
            dataScopeShared.addData('DATA_UPDATE', null);
        }

        $scope.$watch('JENIS_PSN.selectedItem', function (selectedItem) {
            if (typeof selectedItem === 'undefined' || selectedItem.key === null)
                $scope.formData.JENIS_PSN = null;
            else
                $scope.formData.JENIS_PSN = selectedItem.key;
        });

        $scope.$watch('SANTRI_PSN.selectedItem', function (selectedItem) {
            if (typeof selectedItem === 'undefined' || selectedItem.key === null)
                $scope.formData.SANTRI_PSN = null;
            else
                $scope.formData.SANTRI_PSN = selectedItem.key;
        });
    });

    angular.module('mainApp').controller('akadNaikController', function ($scope, $routeParams, $http, notificationService, NgTableParams, $mdDialog, url_template, $timeout, $mdSidenav, $route, $templateCache, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.mainTemplate = url_template + $routeParams.template;
        $scope.appReady = false;
        $scope.dataOriginal = null;
        $scope.flex = 90;
        $scope.flexOffset = 5;
        $scope.fieldTable = [];

        $scope.fabHidden = true;
        $scope.fabIsOpen = false;
        $scope.fabHover = false;

        $scope.formReady = false;

        $scope.formData = {
            ROMBEL_LAMA: null,
            ROMBEL_BARU: null,
            TA_BARU: null,
        };

        $http.get($scope.mainURI + '/index').then(callbackSuccess, notificationService.errorCallback);

        function callbackSuccess(response) {
            $scope.title = response.data.title;
            $scope.breadcrumb = response.data.breadcrumb;
            $scope.table = response.data.table;
            $scope.dataROMBEL_LAMA = response.data.rombel;
            $scope.dataROMBEL_BARU = response.data.rombel;
            $scope.dataTA_BARU = response.data.ta;

            $scope.fieldTable = [];
            angular.forEach($scope.table, function (item, key) {
                $scope.fieldTable.push(item.field);
            });

            $scope.appReady = true;
        }

        $scope.$watch('formData.ROMBEL_LAMA', function (ROMBEL_LAMA) {
            $scope.formReady = false;

            if (($scope.formData.ROMBEL_BARU !== null) && ($scope.formData.TA_BARU !== null) && ($scope.formData.ROMBEL_LAMA !== null))
                getData();
        });

        $scope.$watch('formData.TA_BARU', function (TA_BARU) {
            $scope.formReady = false;

            if (($scope.formData.ROMBEL_BARU !== null) && ($scope.formData.TA_BARU !== null) && ($scope.formData.ROMBEL_LAMA !== null))
                getData();
        });

        $scope.$watch('formData.ROMBEL_BARU', function (ROMBEL_BARU) {
            $scope.formReady = false;

            if (($scope.formData.ROMBEL_BARU !== null) && ($scope.formData.TA_BARU !== null) && ($scope.formData.ROMBEL_LAMA !== null))
                getData();
        });

        function getData() {
            var dataRombel = {
                select: $scope.fieldTable,
                where: {
                    TA_BARU: $scope.formData.TA_BARU,
                    ROMBEL_BARU: $scope.formData.ROMBEL_BARU,
                    ROMBEL_LAMA: $scope.formData.ROMBEL_LAMA
                }
            };

            $http.post($scope.mainURI + '/datatable', dataRombel).then(callbackDatatable, notificationService.errorCallback);
        }

        function callbackDatatable(response) {
            $scope.dataOriginal = response.data.data;

            var initialParams = {
                count: 15
            };

            var initialSettingsLama = {
                counts: [],
                dataset: response.data.lama
            };
            $scope.dataTablesSantriLama = new NgTableParams(initialParams, initialSettingsLama);

            var initialSettingsBaru = {
                counts: [],
                dataset: response.data.baru
            };
            $scope.dataTablesSantriBaru = new NgTableParams(initialParams, initialSettingsBaru);

            $scope.fabHidden = false;
            $scope.formReady = true;
        }

        $scope.naikSemua = function (ev) {
            var confirm = $mdDialog.confirm()
                    .title('Apakan Anda akan menaikan semua santri pada rombel tsb?')
                    .targetEvent(ev)
                    .ok('YA')
                    .cancel('TIDAK');

            $mdDialog.show(confirm).then(function () {
                var dataSantri = {
                    TA_AS: $scope.formData.TA_BARU,
                    ROMBEL_AS: $scope.formData.ROMBEL_BARU,
                    ROMBEL_LAMA: $scope.formData.ROMBEL_LAMA,
                };
                $http.post($scope.mainURI + '/proses_kenaikan', dataSantri).then(callbackSuccessProsesSantri, notificationService.errorCallback);
            }, function () {

            });
        };

        $scope.prosesNaik = function (row, status) {
            var dataSantri = {
                naik: status,
                ID_AS: row.ID_AS,
                TA_AS: $scope.formData.TA_BARU,
                ROMBEL_AS: $scope.formData.ROMBEL_BARU,
                SANTRI_AS: row.ID_SANTRI,
            };
            $http.post($scope.mainURI + '/proses_kenaikan', dataSantri).then(callbackSuccessProsesSantri, notificationService.errorCallback);
        }

        function callbackSuccessProsesSantri(response) {
            notificationService.toastSimple(response.data.notification);

            getData();
        }

        $scope.menuItems = [
            {id: "add_data", name: "Tambah Data", icon: "add"},
            {id: "download_data", name: "Unduh Data", icon: "file_download"},
            // {id: "print_data", name: "Catak Data", icon: "print"},
            {id: "reload_data", name: "Muat Ulang Data", icon: "refresh"},
            {id: "reload_page", name: "Muat Ulang Halaman", icon: "autorenew"},
            {id: "request_doc", name: "Dokumentasi", icon: "help"},
        ];

        $scope.openDialog = function ($event, item) {
            if (item.id === 'reload_data') {
                $scope.fabHidden = true;
                getDataSantri();
            } else if (item.id === 'reload_page') {
                reloadPage();
            } else if (item.id === 'request_doc') {
                $mdSidenav('right').toggle();
            } else if (item.id === 'add_data') {
                createDialog($event, 'form');
            } else if (item.id === 'print_data') {
                var mywindow = window.open('', 'PRINT', 'height=600,width=700');

                mywindow.document.write('<html><head><title>' + document.title + '</title><style type="text/css">body{font-family: "Roboto",Arial,sans-serif;overflow:visible;}.ng-table-filters,.ng-table-counts{display: none;} tr {border-top: 1px solid #f2f6f9;} .data-table{overflow: visible;} table{overflow:visible;}body, h1, h2, h3, ol, ul, div {     width: auto;     border: 0;     margin: 0 5%;     padding: 0;     float: none;     position: static;     overflow: visible; }</style>');
                mywindow.document.write('</head><body onload="window.print()">');
                mywindow.document.write('<h1>' + document.title + '</h1>');
                mywindow.document.write(document.getElementById('printable').innerHTML);
                mywindow.document.write('</body></html>');

                mywindow.document.close();
                mywindow.focus();

                return true;
            } else if (item.id === 'download_data') {
                if ($scope.dataOriginal === null)
                    notificationService.toastSimple('Data tidak ditemukan');
                else
                    alasql('SELECT * INTO XLSX("data_download.xlsx",{headers:true}) FROM ?', [$scope.dataOriginal]);
            }
        };

        function reloadPage() {
            var currentPageTemplate = $route.current.templateUrl;
            $templateCache.remove(currentPageTemplate);
            $route.reload();
        }

        function createDialog(event, mode) {
            $mdDialog
                    .show({
                        controller: DialogController,
                        clickOutsideToClose: false,
                        templateUrl: $scope.mainTemplate + '-' + mode + '.html',
                        targetEvent: event
                    })
                    .then(
                            function (text) {
                                notificationService.toastSimple(text);
                                getDataSantri();
                            },
                            function () {
                                // CANCEL DIALOG
                            }
                    );
        }

        function DialogController($scope, $mdDialog) {
            $scope.cancelSumbit = function () {
                dataScopeShared.addData('DATA_UPDATE', null);
                $mdDialog.cancel();
            };
        }
    });

    angular.module('mainApp').controller('akadMutasiController', function ($scope, $routeParams, $http, notificationService, NgTableParams, $mdDialog, url_template, $timeout, $mdSidenav, $route, $templateCache, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.mainTemplate = url_template + $routeParams.template;
        $scope.appReady = false;
        $scope.dataOriginal = null;
        $scope.flex = 90;
        $scope.flexOffset = 5;

        $scope.fabHidden = true;
        $scope.fabIsOpen = false;
        $scope.fabHover = false;

        $scope.formReady = false;

        $scope.formData = {
            ROMBEL_AS: null,
            STATUS_MUTASI_SANTRI: null,
        };

        $scope.$watch('formData.ROMBEL_AS', function (ROMBEL_AS) {
            $scope.formReady = false;

            if (($scope.formData.ROMBEL_AS !== null) && ($scope.formData.STATUS_MUTASI_SANTRI !== null))
                getDataSantri();
        });

        $scope.$watch('formData.STATUS_MUTASI_SANTRI', function (STATUS_MUTASI_SANTRI) {
            $scope.formReady = false;

            if (($scope.formData.ROMBEL_AS !== null) && ($scope.formData.STATUS_MUTASI_SANTRI !== null))
                getDataSantri();
        });

        $http.get($scope.mainURI + '/index').then(callbackSuccess, notificationService.errorCallback);

        function callbackSuccess(response) {
            $scope.title = response.data.title;
            $scope.breadcrumb = response.data.breadcrumb;
            $scope.table = response.data.table;
            $scope.dataROMBEL_AS = response.data.rombel;
            $scope.dataSTATUS_MUTASI_SANTRI = response.data.status_mutasi;

            $scope.appReady = true;
        }

        function getDataSantri() {
            $http.post($scope.mainURI + '/datatable', $scope.formData).then(callbackDatatablesSantri, notificationService.errorCallback);
        }

        function callbackDatatablesSantri(response) {
            $scope.dataOriginal = response.data.data;

            var initialParams = {
                count: 15
            };
            var initialSettings = {
                counts: [],
                dataset: response.data.data
            };

            $scope.dataTablesSantri = new NgTableParams(initialParams, initialSettings);
            $scope.fabHidden = false;
            $scope.formReady = true;
        }

        $scope.prosesSemua = function (ev) {
            var confirm = $mdDialog.confirm()
                    .title('Apakan Anda akan meluluskan/memutasikan semua santri pada rombel tsb?')
                    .targetEvent(ev)
                    .ok('YA')
                    .cancel('TIDAK');

            $mdDialog.show(confirm).then(function () {
                $http.post($scope.mainURI + '/proses_mutasi', $scope.formData).then(callbackSuccessProsesSantri, notificationService.errorCallback);
            }, function () {

            });
        }

        $scope.prosesMutasi = function (row, ev) {
            var confirm = $mdDialog.confirm()
                    .title('Apakan Anda akan meluluskan/memutasikan santri tsb? ')
                    .targetEvent(ev)
                    .ok('YA')
                    .cancel('TIDAK');

            $mdDialog.show(confirm).then(function () {
                var dataSantri = {
                    ID_SANTRI: row.ID_SANTRI,
                    STATUS_MUTASI_SANTRI: $scope.formData.STATUS_MUTASI_SANTRI,
                };
                $http.post($scope.mainURI + '/proses_mutasi', dataSantri).then(callbackSuccessProsesSantri, notificationService.errorCallback);
            }, function () {

            });
        }

        function callbackSuccessProsesSantri(response) {
            notificationService.toastSimple(response.data.notification);

            getDataSantri();
        }

        $scope.menuItems = [
            {id: "add_data", name: "Tambah Data", icon: "add"},
            {id: "download_data", name: "Unduh Data", icon: "file_download"},
            // {id: "print_data", name: "Catak Data", icon: "print"},
            {id: "reload_data", name: "Muat Ulang Data", icon: "refresh"},
            {id: "reload_page", name: "Muat Ulang Halaman", icon: "autorenew"},
            {id: "request_doc", name: "Dokumentasi", icon: "help"},
        ];

        $scope.openDialog = function ($event, item) {
            if (item.id === 'reload_data') {
                $scope.fabHidden = true;
                getDataSantri();
            } else if (item.id === 'reload_page') {
                reloadPage();
            } else if (item.id === 'request_doc') {
                $mdSidenav('right').toggle();
            } else if (item.id === 'add_data') {
                createDialog($event, 'form');
            } else if (item.id === 'print_data') {
                var mywindow = window.open('', 'PRINT', 'height=600,width=700');

                mywindow.document.write('<html><head><title>' + document.title + '</title><style type="text/css">body{font-family: "Roboto",Arial,sans-serif;overflow:visible;}.ng-table-filters,.ng-table-counts{display: none;} tr {border-top: 1px solid #f2f6f9;} .data-table{overflow: visible;} table{overflow:visible;}body, h1, h2, h3, ol, ul, div {     width: auto;     border: 0;     margin: 0 5%;     padding: 0;     float: none;     position: static;     overflow: visible; }</style>');
                mywindow.document.write('</head><body onload="window.print()">');
                mywindow.document.write('<h1>' + document.title + '</h1>');
                mywindow.document.write(document.getElementById('printable').innerHTML);
                mywindow.document.write('</body></html>');

                mywindow.document.close();
                mywindow.focus();

                return true;
            } else if (item.id === 'download_data') {
                if ($scope.dataOriginal === null)
                    notificationService.toastSimple('Data tidak ditemukan');
                else
                    alasql('SELECT * INTO XLSX("data_download.xlsx",{headers:true}) FROM ?', [$scope.dataOriginal]);
            }
        };

        function reloadPage() {
            var currentPageTemplate = $route.current.templateUrl;
            $templateCache.remove(currentPageTemplate);
            $route.reload();
        }

        function createDialog(event, mode) {
            $mdDialog
                    .show({
                        controller: DialogController,
                        clickOutsideToClose: false,
                        templateUrl: $scope.mainTemplate + '-' + mode + '.html',
                        targetEvent: event
                    })
                    .then(
                            function (text) {
                                notificationService.toastSimple(text);
                                getDataSantri();
                            },
                            function () {
                                // CANCEL DIALOG
                            }
                    );
        }

        function DialogController($scope, $mdDialog) {
            $scope.cancelSumbit = function () {
                dataScopeShared.addData('DATA_UPDATE', null);
                $mdDialog.cancel();
            };
        }
    });

    angular.module('mainApp').controller('keuSaldoController', function ($scope, $routeParams, $http, notificationService, NgTableParams, $mdDialog, url_template, $timeout, $mdSidenav, $route, $templateCache, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.mainTemplate = url_template + $routeParams.template;
        $scope.appReady = false;
        $scope.dataOriginal = null;
        $scope.fieldTable = [];
        $scope.flex = 80;
        $scope.flexOffset = 10;
        $scope.total = 0;

        $scope.fabHidden = true;
        $scope.fabIsOpen = false;
        $scope.fabHover = false;

        $scope.formData = {
            START_DATE: null,
            END_DATE: null,
            USER_BAYAR: null
        };

        $http.get($scope.mainURI + '/index').then(callbackSuccess, notificationService.errorCallback);

        function callbackSuccess(response) {
            $scope.title = response.data.title;
            $scope.breadcrumb = response.data.breadcrumb;
            $scope.table = response.data.table;
            $scope.dataUSER_BAYAR = response.data.ustadz;

            $scope.fieldTable = [];
            angular.forEach($scope.table, function (item, key) {
                $scope.fieldTable.push(item.field);
            });

            if (response.data.wide) {
                $scope.flex = 90;
                $scope.flexOffset = 5;
            }

            $scope.appReady = true;
        }

        $scope.pilihFilter = function (form) {
            $scope.formReady = false;

            if (form.$valid) {
                getData();
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function getData() {
            var dataTagihan = {
                select: $scope.fieldTable,
                where: {
                    START_DATE: $scope.formData.START_DATE,
                    END_DATE: $scope.formData.END_DATE,
                    USER_BAYAR: $scope.formData.USER_BAYAR,
                }
            };

            $http.post($scope.mainURI + '/datatable', dataTagihan).then(callbackDatatables, notificationService.errorCallback);
        }

        function callbackDatatables(response) {
            $scope.total = response.data.total;
            $scope.dataOriginal = response.data.data;

            var initialParams = {
                count: 15
            };
            var initialSettings = {
                counts: [],
                dataset: response.data.data
            };

            $scope.dataTables = new NgTableParams(initialParams, initialSettings);
            $scope.fabHidden = false;
            $scope.formReady = true;
        }

        $scope.menuItems = [
            {id: "add_data", name: "Tambah Data", icon: "add"},
            {id: "download_data", name: "Unduh Data", icon: "file_download"},
            // {id: "print_data", name: "Catak Data", icon: "print"},
            {id: "reload_data", name: "Muat Ulang Data", icon: "refresh"},
            {id: "reload_page", name: "Muat Ulang Halaman", icon: "autorenew"},
            {id: "request_doc", name: "Dokumentasi", icon: "help"},
        ];

        $scope.openDialog = function ($event, item) {
            if (item.id === 'reload_data') {
                $scope.fabHidden = true;
                getData();
            } else if (item.id === 'reload_page') {
                reloadPage();
            } else if (item.id === 'request_doc') {
                $mdSidenav('right').toggle();
            } else if (item.id === 'add_data') {
                createDialog($event, 'form');
            } else if (item.id === 'print_data') {
                var mywindow = window.open('', 'PRINT', 'height=600,width=700');

                mywindow.document.write('<html><head><title>' + document.title + '</title><style type="text/css">body{font-family: "Roboto",Arial,sans-serif;overflow:visible;}.ng-table-filters,.ng-table-counts{display: none;} tr {border-top: 1px solid #f2f6f9;} .data-table{overflow: visible;} table{overflow:visible;}body, h1, h2, h3, ol, ul, div {     width: auto;     border: 0;     margin: 0 5%;     padding: 0;     float: none;     position: static;     overflow: visible; }</style>');
                mywindow.document.write('</head><body onload="window.print()">');
                mywindow.document.write('<h1>' + document.title + '</h1>');
                mywindow.document.write(document.getElementById('printable').innerHTML);
                mywindow.document.write('</body></html>');

                mywindow.document.close();
                mywindow.focus();

                return true;
            } else if (item.id === 'download_data') {
                if ($scope.dataOriginal === null)
                    notificationService.toastSimple('Data tidak ditemukan');
                else
                    alasql('SELECT * INTO XLSX("data_download.xlsx",{headers:true}) FROM ?', [$scope.dataOriginal]);
            }
        };

        function reloadPage() {
            var currentPageTemplate = $route.current.templateUrl;
            $templateCache.remove(currentPageTemplate);
            $route.reload();
        }

        function createDialog(event, mode) {
            $mdDialog
                    .show({
                        controller: DialogController,
                        clickOutsideToClose: false,
                        templateUrl: $scope.mainTemplate + '-' + mode + '.html',
                        targetEvent: event
                    })
                    .then(
                            function (notification) {
                                notificationService.toastSimple(notification);
                                getData();
                            },
                            function () {
                                // CANCEL DIALOG
                            }
                    );
        }

        function DialogController($scope, $mdDialog) {
            $scope.cancelSumbit = function () {
                dataScopeShared.addData('DATA_UPDATE', null);
                $mdDialog.cancel();
            };
        }

        $scope.actionRow = function ($event, action, data) {
            if (action.update)
                updateRow($event, data);
            else if (action.delete)
                deleteRow($event, data);
            else if (action.form)
                formRow($event, data, action.form);
        };

        function formRow(event, data, form) {
            dataScopeShared.addData('DATA_UPDATE', data);
            createDialog(event, form);
        }

        function updateRow(event, data) {
            dataScopeShared.addData('DATA_UPDATE', data);
            createDialog(event, 'form');
        }

        function deleteRow(event, data) {
            var confirm = $mdDialog.confirm()
                    .title('Apakah Anda yakin melanjutkan?')
                    .textContent('Data yang telah dihapus tidak dapat dikembalikan.')
                    .ariaLabel('Hapus data')
                    .targetEvent(event)
                    .ok('Ya')
                    .cancel('Tidak');

            $mdDialog.show(confirm).then(function () {
                $http.post($scope.mainURI + '/delete', data).then(callbackSuccessDelete, notificationService.errorCallback);
            }, function () {
                // cancel
            });
        }

        function callbackSuccessDelete(response) {
            notificationService.toastSimple(response.data.notification);
            getData();
        }
    });

    angular.module('mainApp').controller('settingAppsController', function ($scope, $routeParams, $http, notificationService, NgTableParams, $mdDialog, url_template, $timeout, $mdSidenav, $route, $templateCache, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.mainTemplate = url_template + $routeParams.template;
        $scope.appReady = false;
        $scope.flex = 80;
        $scope.flexOffset = 10;

        $scope.fabHidden = true;
        $scope.fabIsOpen = false;
        $scope.fabHover = false;

        $scope.formData = {
            nama_aplikasi: null,
        };

        $http.get($scope.mainURI + '/index').then(callbackSuccess, notificationService.errorCallback);

        function callbackSuccess(response) {
            $scope.title = response.data.title;
            $scope.breadcrumb = response.data.breadcrumb;
            $scope.formData = response.data.data;

            if (response.data.wide) {
                $scope.flex = 90;
                $scope.flexOffset = 5;
            }

            $scope.appReady = true;
        }

        $scope.simpan = function (form) {
            if (form.$valid) {
                simpanData();
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };

        function simpanData() {
            $http.post($scope.mainURI + '/save', $scope.formData).then(callbackSave, notificationService.errorCallback);
        }

        function callbackSave(response) {
            notificationService.toastSimple(response.data.notification);
            reloadPage();
        }

        $scope.menuItems = [
            {id: "add_data", name: "Tambah Data", icon: "add"},
            {id: "download_data", name: "Unduh Data", icon: "file_download"},
            // {id: "print_data", name: "Catak Data", icon: "print"},
            {id: "reload_data", name: "Muat Ulang Data", icon: "refresh"},
            {id: "reload_page", name: "Muat Ulang Halaman", icon: "autorenew"},
            {id: "request_doc", name: "Dokumentasi", icon: "help"},
        ];

        $scope.openDialog = function ($event, item) {
            if (item.id === 'reload_data') {
                $scope.fabHidden = true;
                getData();
            } else if (item.id === 'reload_page') {
                reloadPage();
            } else if (item.id === 'request_doc') {
                $mdSidenav('right').toggle();
            } else if (item.id === 'add_data') {
                createDialog($event, 'form');
            } else if (item.id === 'print_data') {
                var mywindow = window.open('', 'PRINT', 'height=600,width=700');

                mywindow.document.write('<html><head><title>' + document.title + '</title><style type="text/css">body{font-family: "Roboto",Arial,sans-serif;overflow:visible;}.ng-table-filters,.ng-table-counts{display: none;} tr {border-top: 1px solid #f2f6f9;} .data-table{overflow: visible;} table{overflow:visible;}body, h1, h2, h3, ol, ul, div {     width: auto;     border: 0;     margin: 0 5%;     padding: 0;     float: none;     position: static;     overflow: visible; }</style>');
                mywindow.document.write('</head><body onload="window.print()">');
                mywindow.document.write('<h1>' + document.title + '</h1>');
                mywindow.document.write(document.getElementById('printable').innerHTML);
                mywindow.document.write('</body></html>');

                mywindow.document.close();
                mywindow.focus();

                return true;
            } else if (item.id === 'download_data') {
                if ($scope.dataOriginal === null)
                    notificationService.toastSimple('Data tidak ditemukan');
                else
                    alasql('SELECT * INTO XLSX("data_download.xlsx",{headers:true}) FROM ?', [$scope.dataOriginal]);
            }
        };

        function reloadPage() {
            var currentPageTemplate = $route.current.templateUrl;
            $templateCache.remove(currentPageTemplate);
            $route.reload();
        }

        function createDialog(event, mode) {
            $mdDialog
                    .show({
                        controller: DialogController,
                        clickOutsideToClose: false,
                        templateUrl: $scope.mainTemplate + '-' + mode + '.html',
                        targetEvent: event
                    })
                    .then(
                            function (notification) {
                                notificationService.toastSimple(notification);
                                getData();
                            },
                            function () {
                                // CANCEL DIALOG
                            }
                    );
        }

        function DialogController($scope, $mdDialog) {
            $scope.cancelSumbit = function () {
                dataScopeShared.addData('DATA_UPDATE', null);
                $mdDialog.cancel();
            };
        }

        $scope.actionRow = function ($event, action, data) {
            if (action.update)
                updateRow($event, data);
            else if (action.delete)
                deleteRow($event, data);
            else if (action.form)
                formRow($event, data, action.form);
        };

        function formRow(event, data, form) {
            dataScopeShared.addData('DATA_UPDATE', data);
            createDialog(event, form);
        }

        function updateRow(event, data) {
            dataScopeShared.addData('DATA_UPDATE', data);
            createDialog(event, 'form');
        }

        function deleteRow(event, data) {
            var confirm = $mdDialog.confirm()
                    .title('Apakah Anda yakin melanjutkan?')
                    .textContent('Data yang telah dihapus tidak dapat dikembalikan.')
                    .ariaLabel('Hapus data')
                    .targetEvent(event)
                    .ok('Ya')
                    .cancel('Tidak');

            $mdDialog.show(confirm).then(function () {
                $http.post($scope.mainURI + '/delete', data).then(callbackSuccessDelete, notificationService.errorCallback);
            }, function () {
                // cancel
            });
        }

        function callbackSuccessDelete(response) {
            notificationService.toastSimple(response.data.notification);
            getData();
        }
    });

    angular.module('mainApp').controller('settingAkunController', function ($scope, $routeParams, $http, notificationService, NgTableParams, $mdDialog, url_template, $timeout, $mdSidenav, $route, $templateCache, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.mainTemplate = url_template + $routeParams.template;
        $scope.appReady = false;
        $scope.dataOriginal = null;
        $scope.flex = 90;
        $scope.flexOffset = 5;

        $scope.flexAkun = 60;
        $scope.flexHakakses = 35;

        $scope.fabHidden = true;
        $scope.fieldTable = [];

        $scope.NAMA_UST = null;
        $scope.ID_USER = null;
        $scope.formData = {
            NEW_PASSWORD: ''
        };

        $scope.tableHakaksesShow = false;
        $scope.tablePasswordShow = false;

        $http.get($scope.mainURI + '/index').then(callbackSuccess, notificationService.errorCallback);

        function callbackSuccess(response) {
            $scope.title = response.data.title;
            $scope.breadcrumb = response.data.breadcrumb;
            $scope.tableAkun = response.data.table.akun;
            $scope.tableHakakses = response.data.table.hakakses;

            $scope.fieldTable = [];
            angular.forEach($scope.tableAkun, function (item, key) {
                $scope.fieldTable.push(item.field);
            });

            $scope.appReady = true;

            getDataAkun();
        }

        function getDataAkun() {
            $scope.tableHakaksesShow = false;
            $scope.tablePasswordShow = false;

            $http.post($scope.mainURI + '/get_datatable_akun', $scope.fieldTable).then(callbackDatatablesAkun, notificationService.errorCallback);
        }

        function callbackDatatablesAkun(response) {
            $scope.dataOriginal = response.data.data;

            var initialParams = {
                count: 15
            };
            var initialSettings = {
                counts: [],
                dataset: response.data.data
            };

            $scope.dataTablesAkun = new NgTableParams(initialParams, initialSettings);
        }

        $scope.datatableHakakses = function (row) {
            $scope.tableHakaksesShow = false;
            $scope.tablePasswordShow = false;

            $scope.NAMA_UST = row.NAMA_UST;
            $scope.ID_USER = row.ID_USER;

            getDatatablesHakakses();
        }

        function getDatatablesHakakses() {
            var dataPost = {
                ID_USER: $scope.ID_USER
            };
            $http.post($scope.mainURI + '/get_datatable_hakakses', dataPost).then(callbackDatatablesHakakses, notificationService.errorCallback);
        }

        function callbackDatatablesHakakses(response) {
            $scope.dataOriginal = response.data.data;

            var initialParams = {
                count: 15
            };
            var initialSettings = {
                counts: [],
                dataset: response.data.data
            };

            $scope.dataTablesHakakses = new NgTableParams(initialParams, initialSettings);

            $scope.tableHakaksesShow = true;
        }

        $scope.prosesHakakses = function (row, status) {
            var dataPost = {
                set: status,
                USER_HU: $scope.ID_USER,
                HAKAKSES_HU: row.ID_HAKAKSES
            };

            $http.post($scope.mainURI + '/proses_hakakses', dataPost).then(callbackProses, notificationService.errorCallback);
        };

        function callbackProses(response) {
            notificationService.toastSimple(response.data.notification);

            if (response.data.extra.akun)
                getDataAkun();

            if (response.data.extra.hakakses)
                getDatatablesHakakses();

            $scope.formData.NEW_PASSWORD = '';
            $scope.tablePasswordShow = false;
        }

        $scope.activatedUser = function (row) {
            var dataPost = {
                ID_USER: $scope.ID_USER,
            };

            $http.post($scope.mainURI + '/proses_status', dataPost).then(callbackProses, notificationService.errorCallback);
        };

        $scope.changePassword = function (row) {
            $scope.tableHakaksesShow = false;
            $scope.tablePasswordShow = true;

            $scope.NAMA_UST = row.NAMA_UST;
            $scope.ID_USER = row.ID_USER;
        };

        $scope.simpanPassword = function (form) {
            if (form.$valid) {
                var dataPost = {
                    ID_USER: $scope.ID_USER,
                    NEW_PASSWORD: $scope.formData.NEW_PASSWORD,
                };

                $http.post($scope.mainURI + '/change_password', dataPost).then(callbackProses, notificationService.errorCallback);
            } else {
                notificationService.toastSimple('Silahkan periksa kembali masukan Anda');
            }
        };
    });

    angular.module('mainApp').controller('settingsEmisController', function ($scope, $routeParams, $http, notificationService, NgTableParams, $mdDialog, url_template, $timeout, $mdSidenav, $route, $templateCache, dataScopeShared) {
        $scope.mainURI = $routeParams.ci_dir + '/' + $routeParams.ci_class;
        $scope.mainTemplate = url_template + $routeParams.template;
        $scope.appReady = false;
        $scope.dataOriginal = null;
        $scope.flex = 90;
        $scope.flexOffset = 5;

        $scope.fabHidden = true;

        $scope.formData = {
            ID_KEGIATAN: null
        };

        $http.get($scope.mainURI + '/index').then(callbackSuccess, notificationService.errorCallback);

        function callbackSuccess(response) {
            $scope.title = response.data.title;
            $scope.breadcrumb = response.data.breadcrumb;
            $scope.dataID_KEGIATAN = response.data.KEGIATAN;

            $scope.appReady = true;
        }

        $scope.downloadTemplate = function () {
            window.open('assets/dist/template_emis.xls');
            notificationService.toastSimple('Jangan merubah format template agar proses import berhasil');
        };

        $scope.downloadEMIS = function () {
            window.open($scope.mainURI + '/download_emis');
        };

        $scope.importEMIS = function () {
            var formData = new FormData();
            formData.append('file', document.getElementById("file").files[0]);
            formData.append('ID_KEGIATAN', $scope.formData.ID_KEGIATAN);
            var config = {
                headers: {
                    'Content-Type': undefined
                }
            }
            $http.post($scope.mainURI + '/upload_emis', formData, config).then(callbackProses, notificationService.errorCallback);
        };

        function callbackProses(response) {
            notificationService.toastSimple(response.data.notification);
        }
    });

})();
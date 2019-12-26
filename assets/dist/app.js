(function () {
    "use strict";

    angular.module('mainApp', ['ngMaterial', 'ngRoute', 'ngTable', 'ngMessages', 'ngLocale', 'ngSanitize']);

    angular.module('mainApp').run(function ($rootScope) {
        $rootScope.$on('loading:progress', function () {
            $rootScope.ajaxRunning = true;
        });

        $rootScope.$on('loading:finish', function () {
            $rootScope.ajaxRunning = false;
        });

        $rootScope.showMenu = true;
    });

// STATIC VARIABLES
    angular.module('mainApp').value('url_menu', 'template/menu');
    angular.module('mainApp').value('url_info', 'template/info');
    angular.module('mainApp').value('url_template', 'template/show/');
    angular.module('mainApp').value('url_home', '/template/home/welcome/home');
    angular.module('mainApp').value('url_login', '/user/login/user/login');

// CONFIGURATION 
    angular.module('mainApp').config(
            function ($routeProvider, $locationProvider, $httpProvider, $mdThemingProvider, $mdDateLocaleProvider) {
                $locationProvider.hashPrefix('');
                $routeProvider
                        .when('/:template/:index/:ci_dir/:ci_class', {
                            templateUrl: function (urlattr) {
                                return 'template/show/' + urlattr.template + '-' + urlattr.index + '.html';
                            }
                        })
                        .otherwise({
                            redirectTo: '/template-content/index/user/home'
                        });
                $httpProvider.interceptors.push('httpInterceptor');
                $mdThemingProvider.theme('default')
                        .primaryPalette('green')
                        .accentPalette('lime');
                $mdDateLocaleProvider.formatDate = function (date) {
                    return moment(date).format('DD-MM-YYYY');
                };
            }
    );

// FACTORY
    angular.module('mainApp').factory('httpInterceptor', function ($q, $rootScope) {
        var loadingCount = 0;

        return {
            request: function (config) {
                if (++loadingCount === 1)
                    $rootScope.$broadcast('loading:progress');
                return config || $q.when(config);
            },

            response: function (response) {
                if (--loadingCount === 0)
                    $rootScope.$broadcast('loading:finish');
                return response || $q.when(response);
            },

            responseError: function (response) {
                if (--loadingCount === 0)
                    $rootScope.$broadcast('loading:finish');
                return $q.reject(response);
            }
        };
    }
    );

// SERVICES APP
    angular.module('mainApp').service('notificationService', function ($location, $mdDialog, $mdToast, url_login) {
        this.errorCallback = function (error) {
            if (error.status === 403) {
                $location.path(url_login);
            }

            $mdDialog.show(
                    $mdDialog.alert()
                    .clickOutsideToClose(true)
                    .title('Error Response: ' + error.status + ' - ' + error.statusText)
                    .htmlContent(error.data)
                    .ariaLabel('Error')
                    .ok('OK')
                    );
        };
        this.toastSimple = function (notification) {
            var text = '';
            var type = '';

            if (typeof notification === 'string') {
                text = notification;
                type = 'info';
            } else {
                text = notification.text;
                type = notification.type;
            }

            $mdToast.show($mdToast.simple().textContent(text).position('top right').hideDelay(3000).theme(type + "-toast"));
        };
    });

    angular.module('mainApp').service("dataScopeShared", function () {
        var dataList = {};

        var addData = function (key, value) {
            dataList[key] = value;
        };

        var getData = function (key) {
            return dataList[key];
        };

        return {
            addData: addData,
            getData: getData
        };
    });

    angular.module('mainApp').service("formHelper", function ($timeout, $q) {
        this.autocomplete = function (scope) {
            var dataAC = scope.dataAutocomplete;

            scope = {
                noCache: false,
                selectedItem: null,
                searchText: null,
                dataAll: null,
                querySearch: null,
            };

            scope.dataAll = loadAll();

            function loadAll() {
                return dataAC.map(function (detail) {
                    return {
                        key: detail.id,
                        value: detail.title.toLowerCase(),
                        display: detail.title
                    };
                });
            }

            scope.querySearch = function (query) {
                if (typeof query === 'undefined')
                    query = scope.searchText;

                var results = query ? scope.dataAll.filter(createFilterFor(query)) : scope.dataAll;
                var deferred = $q.defer();

                $timeout(function () {
                    deferred.resolve(results);
                }, Math.random() * 1000, false);

                return deferred.promise;
            };

            function createFilterFor(query) {
                var lowercaseQuery = angular.lowercase(query);

                return function filterFn(detail) {
                    return (detail.value.indexOf(lowercaseQuery) >= 0);
                };
            }

            return scope;
        };
    });

})();
/**
 * Created by benjamin on 22.03.2016.
 */
angular.module('imageShopAdm.preferences', [])

    .config(['$stateProvider', '$urlRouterProvider',  function ($stateProvider, $urlRouterProvider) {
        $stateProvider
            .state('preferences', {
                url: '/preferences',
                templateUrl: "views/adm/preferences/preferences.html",
                controller: 'PreferenceCtrl as ctrl',
                data: {
                    permissions: {
                        except: ['anonymous'],
                        redirectTo: 'auth'
                    }
                }
            })
    }])

    .controller('PreferenceCtrl', ['$state', '$http', '$rootScope', '$uibModal', '$location', function ($state, $http, $rootScope, $uibModal, $location) {
        var vm = this;

        this.getPreferences = function () {
            $http.get('api/preferences').then(function (response) {
                if (response.data.error) {
                    alert("Error");
                } else {
                    vm.preferences = response.data.data;
                }
            }, function (response) {
                alert("Failure");
            });
        };

        var init = function () {
            vm.getPreferences();
        };

        init();

        this.updatePreference = function (key, data) {
            return $http.put('api/preferences/' + key, {value: data}).then(function (response) {
                if (response.status == 200) {
                    return true;
                } else {
                    if (response.data.error.message) {
                        return response.data.error.message;
                    }
                    return "Error";
                }
            }, function (response) {
                if (response.data.error.message) {
                    return response.data.error.message;
                }
                return "Error: " + response.status;
            })
        };

    }]).directive('preferencesElement', function () {
        return {
            restrict: 'A',
            templateUrl: 'views/adm/preferences/listelement.html',
            scope: {
                preferences: '=preferences',
                controller: '=controller'
            }
        };
    });

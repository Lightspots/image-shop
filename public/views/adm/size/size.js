/**
 * Created by benjamin on 22.03.2016.
 */
angular.module('imageShopAdm.size', [])

    .config(['$stateProvider', '$urlRouterProvider', function ($stateProvider, $urlRouterProvider) {
        $stateProvider
            .state('sizes', {
                url: '/sizes',
                templateUrl: "views/adm/size/size.html",
                controller: 'SizeCtrl as ctrl',
                data: {
                    permissions: {
                        except: ['anonymous'],
                        redirectTo: 'auth'
                    }
                }
            })
    }])

    .controller('SizeCtrl', ['$state', '$http', '$rootScope', function ($state, $http, $rootScope) {
        var vm = this;

        this.getSizes = function () {
            $http.get('api/sizes').then(function (response) {
                if (response.data.error) {
                    alert("Error");
                } else {
                    vm.sizes = response.data.data;
                }
            }, function (response) {
                alert("Failure");
            });
        };

        var init = function () {
            vm.getSizes();
        };

        init();
    }]).directive('sizeElement', function () {
        return {
            restrict: 'A',
            templateUrl: 'views/adm/size/listelement.html',
            scope: {
                size: '=size',
                controller: '=controller'
            }
        };
    });

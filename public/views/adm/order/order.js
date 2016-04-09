/**
 * Created by benjamin on 22.03.2016.
 */
angular.module('imageShopAdm.order', [])

    .config(['$stateProvider', '$urlRouterProvider', function ($stateProvider, $urlRouterProvider) {
        $stateProvider
            .state('orders', {
                url: '/orders',
                templateUrl: "views/adm/order/order.html",
                controller: 'OrderCtrl as ctrl',
                data: {
                    permissions: {
                        except: ['anonymous'],
                        redirectTo: 'auth'
                    }
                }
            })
    }])

    .controller('OrderCtrl', ['$state', '$http', '$rootScope', '$location', function ($state, $http, $rootScope, $location) {
        var vm = this;

        this.getOrders = function () {
            $http.get('api/orders').then(function (response) {
                if (response.data.error) {
                    alert("Error");
                } else {
                    response.data.data.forEach(function (entry) {
                        entry.count = entry.photos.length;
                        entry.customer = entry.firstname + " " + entry.lastname + ", " + entry.village;
                    });
                    vm.orders = response.data.data;
                }
            }, function (response) {
                alert("Failure");
            });
        };

        var init = function () {
            vm.getOrders();
        };

        init();

        this.show = function (id) {
            $location.path('/orders/' + id);
        };

        this.delete = function (id) {
            if (confirm('Delete Order ' + id + '?')) {
                $http.delete('api/orders/' + id).then(function (response) {
                    vm.getOrders();
                }, function (response) {
                    console.log(response);
                });
            }
        };

    }]).directive('orderElement', function () {
    return {
        restrict: 'A',
        templateUrl: 'views/adm/order/listelement.html',
        scope: {
            order: '=order',
            controller: '=controller'
        }
    };
});

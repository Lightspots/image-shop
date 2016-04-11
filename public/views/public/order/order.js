/**
 * Created by benjamin on 22.03.2016.
 */
angular.module('imageShop.order', [])

    .config(['$stateProvider', '$urlRouterProvider', function ($stateProvider, $urlRouterProvider) {
        $stateProvider
            .state('order', {
                url: '/order',
                templateUrl: "views/public/order/order.html",
                controller: 'OrderCtrl as ctrl'
            })
    }])

    .controller('OrderCtrl', ['$state', '$http', '$rootScope', 'orderService', '$location', '$uibModal', function ($state, $http, $rootScope, orderService, $location, $uibModal) {
        var vm = this;

        vm.all = {
            size: -1,
            piece : 0,
            price : 0
        };
        
        vm.pieces = 0;
        vm.price = 0;

        vm.orders = {};

        var init = function () {
            getSizes();
            vm.album = orderService.album;
            vm.photos = orderService.photos;
            if (!vm.album || !vm.photos) {
                $location.path('/');
                return;
            }
            vm.photos.forEach(function (entry) {
                vm.orders[entry] = [];
                vm.orders[entry][0] = {
                    num: 0,
                    size : -1,
                    piece: 0,
                    price: 0
                };
            });
        };

        var getSizes = function () {
            $http.get('api/sizes').then(function (response) {
                vm.sizes = response.data.data;
            });
        };



        var getSize = function (id) {
            for (var i = 0; i < vm.sizes.length; i++) {
                if (vm.sizes[i].id == id) {
                    return vm.sizes[i];
                }
            }
        };

        this.onChange = function (entry, number) {
            var size = getSize(vm.orders[entry][number].size);
            if (size) {
                if (vm.orders[entry][number].piece < 1) {
                    vm.orders[entry][number].piece = 1;
                }
                vm.orders[entry][number].price = size.price * vm.orders[entry][number].piece;
            } else {
                vm.orders[entry][number].piece = 0;
                vm.orders[entry][number].price = 0;
            }
            calcPrice();
        };
        
        this.addInputLine = function (entry) {
            if (vm.orders[entry].length >= vm.sizes.length) {
                return;
            }
            vm.orders[entry].push({
                num: vm.orders[entry].length,
                size : -1,
                piece: 0,
                price: 0
            });  
        };

        this.setOptions = function () {
            for (var key in vm.orders) {
                vm.orders[key][0].size = vm.all.size;
                vm.orders[key][0].piece = vm.all.piece;
                vm.orders[key][0].price = vm.all.price;
            }
            calcPrice();
        };

        this.selectAllChanged = function () {
            var size = getSize(vm.all.size);
            if (size) {
                if (vm.all.piece < 1) {
                    vm.all.piece = 1;
                }
                vm.all.price = size.price * vm.all.piece;
            } else {
                vm.all.piece = 0;
                vm.all.price = 0;
            }
        };

        var calcPrice = function () {
            var count = 0;
            var price = 0;
            for (var key in vm.orders) {
                for (var i = 0; i < vm.orders[key].length; i++) {
                    count += vm.orders[key][i].piece;
                    price += vm.orders[key][i].price;
                }
            }
            vm.price = Math.round(price * 100) / 100;
            vm.pieces = count;
        };

        init();

    }]).directive('orderPhotoElement', function () {
    return {
        restrict: 'A',
        templateUrl: 'views/public/order/photoElement.html',
        scope: {
            photo: '=photo',
            controller: '=controller'
        }
    };
    }).directive('orderInputElement', function () {
    return {
        restrict: 'A',
        templateUrl: 'views/public/order/inputElement.html',
        scope: {
            photo: '=photo',
            number: '=number',
            controller: '=controller'
        }
    };
    }).service('orderService', function () {
    });
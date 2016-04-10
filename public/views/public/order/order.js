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
                vm.orders[entry] = {
                    size : -1,
                    piece: 0,
                    price: 0
                };
            });

            createOnChangeFunctions();
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

        var createOnChangeFunctions = function () {
            vm.onChange = {};
            vm.photos.forEach(function (entry) {
                vm.onChange[entry] = function () {
                    var size = getSize(vm.orders[entry].size);
                    if (vm.orders[entry].piece < 1) {
                        vm.orders[entry].piece = 1;
                    }
                    vm.orders[entry].price = size.price * vm.orders[entry].piece;
                }
            })
        };

        this.setOptions = function () {
            for (var key in vm.orders) {
                vm.orders[key].size = vm.all.size;
                vm.orders[key].piece = vm.all.piece;
                vm.orders[key].price = vm.all.price;
            }
        };

        this.selectAllChanged = function () {
            var size = getSize(vm.all.size);
            if (vm.all.piece < 1) {
                vm.all.piece = 1;
            }
            vm.all.price = size.price * vm.all.piece;
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
}).service('orderService', function () {
    this.album = {
        'id': null,
        'key': null
    };
});

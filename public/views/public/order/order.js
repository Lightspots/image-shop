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

    .controller('OrderCtrl', ['$http', 'orderService', '$location', '$uibModal', 'notifyService', function ($http, orderService, $location, $uibModal, notifyService) {
        var vm = this;

        vm.all = {
            size: '-1',
            piece : 0,
            price : 0,
            finish: 'GLOSSY'
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
                    size : '-1',
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
                size : '-1',
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
            if (price > 0) {
                price += orderService.shippingCosts;
            }
            vm.price = Math.round(price * 100) / 100;
            vm.pieces = count;
        };

        this.order = function () {
            var orders = [];
            for (var key in vm.orders) {
                for (var i = 0; i<vm.orders[key].length; i++) {
                    if (vm.orders[key][i].size < 0) {
                        break;
                    }
                    orders.push({
                        photo: key,
                        size: getSize(vm.orders[key][i].size).text,
                        piece: vm.orders[key][i].piece,
                        price: vm.orders[key][i].price
                    });
                }

            }
            if (orders.length < 1) {
                notifyService.warn('ORDER_WARNING_SELECT_MIN');
                return;
            }

            var modalInstance = $uibModal.open({
                templateUrl: 'views/public/order/orderDialog.html',
                controller: 'OrderDialogController as ctrl',
                size: 'lg'
            });

            modalInstance.album = vm.album;
            modalInstance.price = vm.price;
            modalInstance.finish = vm.all.finish;
            modalInstance.orders = orders;
            
            modalInstance.result.then(function (person) {
                var config = {
                    headers: {
                        'Content-Type': 'application/json;'
                    }
                };

                var order = person;
                order.photos = orders;
                order.album = vm.album;
                order.finish = vm.all.finish;
                order.price = vm.price;

                $http.post('api/orders', order, config).then(function (response) {
                    if (response.status == 201) {//Updated
                        orderService.orderDone = true;
                        $location.path('/');
                    } else {
                        notifyService.warn('HTTP_ERROR', response.status, response.data.error);
                        console.log(response);
                    }
                }, function (response) {
                    notifyService.warn('HTTP_ERROR', response.status, response.data.error);
                    console.log(response);
                });
            });
        };

        init();

    }]).controller('OrderDialogController',
    ['$uibModalInstance', '$translate' , '$scope', 'notifyService','orderService', function ($uibModalInstance, $translate, $scope, notifyService, orderService) {
        var vm = this;

        this.album = $uibModalInstance.album;
        this.orders = $uibModalInstance.orders;
        this.price = $uibModalInstance.price;
        this.shippingCosts = orderService.shippingCosts;
        $translate($uibModalInstance.finish).then(function (text) {
            vm.finish = text;
        });

        this.ok = function () {
            if ($scope.orderForm.$valid) {
                $uibModalInstance.close(this.person);
            } else {
                notifyService.warn('ORDER_WARNING_FILL_FIELDS');
            }
        };

        this.cancel = function () {
            $uibModalInstance.dismiss();
        };
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
        this.orderDone = false;

        this.shippingCosts = 5.0
    });

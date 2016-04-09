/**
 * Created by benjamin on 22.03.2016.
 */
angular.module('imageShopAdm.size', [])

    .config(['$stateProvider', '$urlRouterProvider',  function ($stateProvider, $urlRouterProvider) {
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

    .controller('SizeCtrl', ['$state', '$http', '$rootScope', '$uibModal', '$location', function ($state, $http, $rootScope, $uibModal, $location) {
        var vm = this;

        this.getOrders = function () {
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
            vm.getOrders();
        };

        init();

        this.delete = function (id) {
            if (confirm('Delete Size ' + id + '?')) {
                $http.delete('api/sizes/' + id).then(function (response) {
                    vm.getOrders();
                }, function (response) {
                    console.log(response);
                });
            }
        };

        this.openCreateSizeModal = function () {
            var modalInstance = $uibModal.open({
                templateUrl: 'views/adm/size/createSizeDialog.html',
                controller: 'SizeDetailDialogController as ctrl'
            });

            modalInstance.size = {
                text: null,
                price: ''
            };

            modalInstance.result.then(function (size) {
                var config = {
                    headers: {
                        'Content-Type': 'application/json;'
                    }
                };

                $http.post('api/sizes', size, config).then(function (response) {
                    if (response.status == 201) {//Created
                        vm.getOrders();
                    }
                }, function (response) {
                    console.log(response);
                });
            });
        };

        this.openEditSizeModal = function (id) {

            $http.get('api/sizes/' + id).success(function (data) {
                var modalInstance = $uibModal.open({
                    templateUrl: 'views/adm/size/createSizeDialog.html',
                    controller: 'SizeDetailDialogController as ctrl'
                });
                data.data.price = parseFloat(data.data.price);
                modalInstance.size = data.data;

                modalInstance.result.then(function (size) {
                    var config = {
                        headers: {
                            'Content-Type': 'application/json;'
                        }
                    };

                    $http.put('api/sizes/' + id, size, config).then(function (response) {
                        if (response.status == 200) {//Updated
                            vm.getOrders();
                        }
                    }, function (response) {
                        console.log(response);
                    });
                });
            });
        };

    }]).controller('SizeDetailDialogController',
    ['$uibModalInstance', function ($uibModalInstance) {


        this.size = $uibModalInstance.size;

        this.ok = function () {
            $uibModalInstance.close(this.size);
        };

        this.cancel = function () {
            $uibModalInstance.dismiss();
        };
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

/**
 * Created by benjamin on 22.03.2016.
 */
angular.module('imageShopAdm.orderDetail', [])

    .config(['$stateProvider', '$urlRouterProvider', function ($stateProvider, $urlRouterProvider) {
        $stateProvider
            .state('orderDetail', {
                url: '/orders/:id',
                templateUrl: "views/adm/orderDetail/orderDetail.html",
                controller: 'OrderDetailCtrl as ctrl',
                data: {
                    permissions: {
                        except: ['anonymous'],
                        redirectTo: 'auth'
                    }
                }
            })
    }])

    .controller('OrderDetailCtrl', ['$state', '$http', '$rootScope', '$uibModal', '$location', '$stateParams', function ($state, $http, $rootScope, $uibModal, $location, $stateParams) {
        var vm = this;

        this.getOrder = function () {
            $http.get('api/orders/' + $stateParams.id).then(function (response) {
                if (response.data.error) {
                    alert(response.data.error);
                } else {
                    response.data.data.photos.forEach(function (entry) {
                        var path = entry.path.split('/');
                        path[path.length-1] = "t_" + path[path.length-1];
                        entry.image = path.join('/');
                    });
                    vm.order = response.data.data;
                }
            }, function (response) {
                alert("Failure");
            });
        };

        var init = function () {
            vm.getOrder();
        };

        init();

    }]).directive('photoElement', function () {
    return {
        restrict: 'A',
        templateUrl: 'views/adm/orderDetail/listelement.html',
        scope: {
            photo: '=photo',
            controller: '=controller'
        }
    };
});

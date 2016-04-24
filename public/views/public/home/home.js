/**
 * Created by benjamin on 22.03.2016.
 */
angular.module('imageShop.home', [])

    .config(['$stateProvider', '$urlRouterProvider', function($stateProvider, $urlRouterProvider) {
        $stateProvider
            .state('home', {
                url: '/',
                templateUrl: "views/public/home/home.html",
                controller: 'HomeCtrl as home'
            })
    }])

    .controller('HomeCtrl', ['$state', '$http', 'notifyService', 'albumService', 'orderService', '$location', function($state, $http, notifyService, albumService, orderService, $location) {
        var vm = this;
        if (albumService.invalidAlbum) {
            notifyService.error('LOGIN_INVALID_ERROR');
            albumService.invalidAlbum = false;
        }
        
        if (orderService.orderDone) {
            orderService.orderDone = false;
            notifyService.success('ORDER_SUCCESSFULL');
        }

        this.show = function () {
            if (vm.key == null || vm.key == '') {
                notifyService.warn('LOGIN_EMPTY_WARNING');
                return;
            }

            if (jQuery.isNumeric(vm.key)) {
                vm.key = null;
                notifyService.error('LOGIN_INVALID_ERROR');
                return;
            }

            albumService.album.id = null;
            albumService.album.key = vm.key;
            $location.path('/album');
        }
    }]);

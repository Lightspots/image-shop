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

    .controller('HomeCtrl', ['$state', '$http', '$rootScope', 'albumService', '$location', function($state, $http, $rootScope, albumService, $location) {
        var vm = this;

        this.show = function () {
            if (vm.key == null || vm.key == '') {
                return;
            }

            if (jQuery.isNumeric(vm.key)) {
                vm.key = null;
                return;
            }

            albumService.album.id = null;
            albumService.album.key = vm.key;
            $location.path('/album');
        }
    }]);

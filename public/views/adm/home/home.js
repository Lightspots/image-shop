/**
 * Created by benjamin on 22.03.2016.
 */
angular.module('imageShopAdm.home', [])

    .config(['$stateProvider', '$urlRouterProvider', function($stateProvider, $urlRouterProvider) {
        $stateProvider
            .state('home', {
                url: '/',
                templateUrl: "views/adm/home/home.html",
                controller: 'HomeCtrl as home'
            })
    }])

    .controller('HomeCtrl', ['$state', '$http', '$rootScope', function($state, $http, $rootScope) {
        var vm = this;


    }]);

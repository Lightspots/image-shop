/**
 * Created by benjamin on 22.03.2016.
 */
angular.module('imageShop.home', [])

    .config(['$stateProvider', '$urlRouterProvider', function($stateProvider, $urlRouterProvider) {
        $stateProvider
            .state('home', {
                url: '/',
                templateUrl: "views/public/home/home.html",
                controller: 'HomeCtrl as home',
            })
    }])

    .controller('HomeCtrl', ['$state', '$http', '$rootScope', function($state, $http, $rootScope) {
        var vm = this;


    }]);

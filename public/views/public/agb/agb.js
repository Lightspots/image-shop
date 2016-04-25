/**
 * Created by benjamin on 25.04.2016.
 */
angular.module('imageShop.agb', [])

    .config(['$stateProvider', '$urlRouterProvider', function($stateProvider, $urlRouterProvider) {
        $stateProvider
            .state('agb', {
                url: '/agb',
                templateUrl: "views/public/agb/agb.html",
                controller: 'AgbCtrl as ctrl'
            })
            .state('impressum', {
            url: '/impressum',
            templateUrl: "views/public/agb/impressum.html",
            controller: 'AgbCtrl as ctrl'
        })

    }])
    .controller('AgbCtrl', ['$state', '$http', 'notifyService', 'albumService', 'orderService', '$location', function($state, $http, notifyService, albumService, orderService, $location) {
        var vm = this;
    }]);
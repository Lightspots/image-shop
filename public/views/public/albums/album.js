/**
 * Created by benjamin on 22.03.2016.
 */
angular.module('imageShop.albums', [])

    .config(['$stateProvider', '$urlRouterProvider', function($stateProvider, $urlRouterProvider) {
        $stateProvider
            .state('albums', {
                url: '/albums',
                templateUrl: "views/public/albums/album.html",
                controller: 'AlbumsCtrl as ctrl'
            })
    }])

    .controller('AlbumsCtrl', ['$state', '$http', '$rootScope', function($state, $http, $rootScope) {
        var vm = this;

        var init = function () {
            vm.getAlbums();
        };

        vm.getAlbums = function () {
            $http.get('api/publicalbums').then(function (response) {
                vm.albums = response.data.data;
            }, function (response) {
                //TODO
            });
        };

        init();


    }]).directive('albumElement', function () {
    return {
        restrict: 'A',
        templateUrl: 'views/public/albums/listelement.html',
        scope: {
            album: '=album',
            controller: '=controller'
        }
    };
});

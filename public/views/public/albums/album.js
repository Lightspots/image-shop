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

    .controller('AlbumsCtrl', ['$state', '$http', '$rootScope', 'albumService', '$location', function($state, $http, $rootScope, albumService, $location) {
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

        this.show = function (id) {
            albumService.album.id = id;
            albumService.album.key = null;
            $location.path('/album');
        }


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

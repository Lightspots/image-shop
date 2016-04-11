/**
 * Created by benjamin on 22.03.2016.
 */
angular.module('imageShop.album', [])

    .config(['$stateProvider', '$urlRouterProvider', function($stateProvider, $urlRouterProvider) {
        $stateProvider
            .state('album', {
                url: '/album',
                templateUrl: "views/public/album/album.html",
                controller: 'AlbumCtrl as ctrl'
            })
    }])

    .controller('AlbumCtrl', ['$state', '$http', '$rootScope', 'albumService', 'orderService', '$location', '$uibModal', function($state, $http, $rootScope, albumService, orderService, $location, $uibModal) {
        var vm = this;

        this.currentPage = 1;
        this.totalPhotos = 0;
        this.checked = [];

        var init = function () {
            if (albumService.album.id) {
                var id = albumService.album.id;
            } else if (albumService.album.key) {
                id = albumService.album.key;
            } else {
                $location.path('/');
                return;
            }

            vm.getAlbum(id);
        };

        this.getAlbum = function (id) {

            $http.get('api/publicalbums/' + id).then(function (response) {
                vm.album = response.data.data;
                vm.totalPhotos = vm.album.photos.length;
                vm.pageChanged();
            }, function (response) {
                alert(response.data.error.message); //TODO
                $location.path('/');
            });
        };

        this.pageChanged = function () {

            var startIndex = (vm.currentPage - 1) * 6;
            var end = startIndex + 6 > vm.totalPhotos ? vm.totalPhotos : startIndex + 6;

            var a = [];
            vm.currentPhotos = [];
            for (var i = startIndex; i < end; i++) {
                a.push(vm.album.photos[i]);
                if (i == startIndex + 2) {
                    vm.currentPhotos.push(a);
                    a = [];
                }
            }
            if (a.length > 0) {
                vm.currentPhotos.push(a);
            }
        };
        init();

        this.show = function (photo) {
            
            var img = new Image();
            img.onload = function () {
                var modalInstance = $uibModal.open({
                    templateUrl: 'views/public/album/photoDialog.html',
                    controller: 'PhotoDialogController as ctrl',
                    size: 'lg'
                });
                modalInstance.photo = photo;
                modalInstance.width = this.width;
                modalInstance.height = this.height;
                modalInstance.album = vm.album;
            };
            img.src = 'albums/' + vm.album.path + '/c_' + photo;
        };
        
        this.order = function () {
            orderService.album = vm.album;
            orderService.photos = [];
            for (var key in vm.checked) {
                if (vm.checked[key] == true) {
                    orderService.photos.push(key);
                }
            }
            if (orderService.photos.length < 1) {
                return;
            }
            $location.path('/order');
        };

    }]).controller('PhotoDialogController',
    ['$uibModalInstance', function ($uibModalInstance) {


        this.photo = $uibModalInstance.photo;
        this.width = $uibModalInstance.width;
        this.height = $uibModalInstance.height;
        this.album = $uibModalInstance.album;

        this.close = function () {
            $uibModalInstance.close();
        };
    }]).directive('photoElement', function () {
    return {
        restrict: 'A',
        templateUrl: 'views/public/album/photoElement.html',
        scope: {
            photo: '=photo',
            controller: '=controller'
        }
    };
    }).directive('photoRowElement', function () {
    return {
        restrict: 'A',
        templateUrl: 'views/public/album/photoRowElement.html',
        scope: {
            photos: '=photos',
            controller: '=controller'
        }
    };
    }).service('albumService', function() {
        this.album = {
            'id': null,
            'key': null
        };
    });

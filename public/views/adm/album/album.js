/**
 * Created by benjamin on 22.03.2016.
 */
angular.module('imageShopAdm.album', [])

    .config(['$stateProvider', '$urlRouterProvider',  function ($stateProvider, $urlRouterProvider) {
        $stateProvider
            .state('albums', {
                url: '/albums',
                templateUrl: "views/adm/album/album.html",
                controller: 'AlbumCtrl as ctrl',
                data: {
                    permissions: {
                        except: ['anonymous'],
                        redirectTo: 'auth'
                    }
                }
            })
    }])

    .controller('AlbumCtrl', ['$state', '$http', '$rootScope', '$uibModal', '$location', function ($state, $http, $rootScope, $uibModal, $location) {
        var vm = this;

        this.getAlbums = function () {
            $http.get('api/albums').then(function (response) {
                if (response.data.error) {
                    alert("Error");
                } else {
                    vm.albums = response.data.data;
                }
            }, function (response) {
                alert("Failure");
            });
        };

        var init = function () {
            vm.getAlbums();
        };

        init();

        this.delete = function (id) {
            if (confirm('Delete Album ' + id + '?')) {
                $http.delete('api/albums/' + id).then(function (response) {
                    vm.getAlbums();
                }, function (response) {
                    console.log(response);
                });
            }
        };

        this.openCreateAlbumModal = function () {
            var modalInstance = $uibModal.open({
                templateUrl: 'views/adm/album/createAlbumDialog.html',
                controller: 'AlbumDetailDialogController as ctrl'
            });

            modalInstance.album = {
                name: null,
                path: null,
                public: false
            };

            modalInstance.result.then(function (album) {
                var config = {
                    headers: {
                        'Content-Type': 'application/json;'
                    }
                };

                $http.post('api/albums', album, config).then(function (response) {
                    if (response.status == 201) {//Created
                        vm.getAlbums();
                    }
                }, function (response) {
                    console.log(response);
                });
            });
        };

        this.openEditAlbumModal = function (id) {

            $http.get('api/albums/' + id).success(function (data) {
                var modalInstance = $uibModal.open({
                    templateUrl: 'views/adm/album/createAlbumDialog.html',
                    controller: 'AlbumDetailDialogController as ctrl'
                });
                
                data.data.public = data.data.public == 1;

                modalInstance.album = data.data;

                modalInstance.result.then(function (album) {
                    var config = {
                        headers: {
                            'Content-Type': 'application/json;'
                        }
                    };

                    $http.put('api/albums/' + id, album, config).then(function (response) {
                        if (response.status == 200) {//Updated
                            vm.getAlbums();
                        }
                    }, function (response) {
                        console.log(response);
                    });
                });
            });
        };

        this.process = function (id) {
            $http.post('api/albums/process/' + id).then(function (response) {
                if (response.status == 200) {
                    alert('Done');
                } else {
                    alert('Failure');
                }
            }, function (response) {
                alert("Error");
            });
        }

    }]).controller('AlbumDetailDialogController',
    ['$uibModalInstance', function ($uibModalInstance) {


        this.album = $uibModalInstance.album;

        this.ok = function () {
            $uibModalInstance.close(this.album);
        };

        this.cancel = function () {
            $uibModalInstance.dismiss();
        };
    }]).directive('albumElement', function () {
        return {
            restrict: 'A',
            templateUrl: 'views/adm/album/listelement.html',
            scope: {
                album: '=album',
                controller: '=controller'
            }
        };
    });

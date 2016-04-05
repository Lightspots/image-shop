/**
 * Created by benjamin on 20.03.2016.
 */
angular.module('imageShopAdm.auth', [])

    .config(['$stateProvider', '$urlRouterProvider', function($stateProvider, $urlRouterProvider) {
        $stateProvider
            .state('auth', {
                url: '/auth',
                templateUrl: "views/adm/auth/auth.html",
                controller: 'AuthCtrl as auth',
                data: {
                    permissions: {
                        except: ['isloggedin'],
                        redirectTo: 'home'
                    }
                }
            })
    }])

    .controller('AuthCtrl', ['$auth', '$state', '$http', '$rootScope', function($auth, $state, $http, $rootScope) {
        var vm = this;

        vm.loginError = false;
        vm.loginErrorText = null;

        vm.login = function() {

            var credentials = {
                email: vm.email,
                password: vm.password
            };

            $auth.login(credentials).then(function() {
                vm.loginError = false;
                vm.loginErrorText = null;
                $http.get('api/authenticate/user').success(function(response){
                        var user = JSON.stringify(response.user);
                        localStorage.setItem('user', user);
                        $rootScope.currentUser = response.user;
                        $state.go('home');
                    })
                    .error(function(){
                        vm.loginError = true;
                        vm.loginErrorText = error.data.error;
                        console.log(vm.loginErrorText);
                    })
            }, function (response) {
                vm.loginError = true;
                vm.loginErrorText = response.data.error;
            });
        };

        vm.reset = function () {
            vm.loginError = false;
            vm.loginErrorText = null;
        };
    }]);
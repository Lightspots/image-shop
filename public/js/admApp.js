/**
 * Created by benjamin on 20.03.2016.
 */
angular.module('imageShopAdm', [
    'ui.router',
    'satellizer',
    'permission',
    'permission.ui',
    'ui.bootstrap',
    'ngAnimate',
    'xeditable',
    'imageShopAdm.auth',
    'imageShopAdm.home',
    'imageShopAdm.size',
    'imageShopAdm.album',
    'imageShopAdm.order',
    'imageShopAdm.preferences'
]).config(['$stateProvider', '$urlRouterProvider', '$authProvider', function($stateProvider, $urlRouterProvider, $authProvider) {

    // $authProvider.loginUrl = 'image-shop/public/api/authenticate';  //local
    $authProvider.loginUrl = 'api/authenticate';  //server
    // $urlRouterProvider.otherwise('/view1');
    $urlRouterProvider.otherwise('/auth');
}]).run(['$rootScope', '$state', '$auth', 'PermPermissionStore', 'editableOptions', function ($rootScope, $state, $auth, PermPermissionStore, editableOptions) {

    editableOptions.theme = 'bs3';

    $rootScope.logout = function() {
        $auth.logout().then(function() {
            localStorage.removeItem('user');
            $rootScope.currentUser = null;
            $state.go('auth');
        });
    };
    $rootScope.currentUser = JSON.parse(localStorage.getItem('user'));
    $rootScope.isAuthenticated = $auth.isAuthenticated;

    PermPermissionStore
        .definePermission('anonymous', function (stateParams) {
            // If the returned value is *truthy* then the user has the role, otherwise they don't
            // var User = JSON.parse(localStorage.getItem('user'));
            // console.log("anonymous ", $auth.isAuthenticated());
            if (!$auth.isAuthenticated()) {
                return true; // Is anonymous
            }
            return false;
        });
    PermPermissionStore
        .definePermission('isloggedin', function (stateParams) {
            // If the returned value is *truthy* then the user has the role, otherwise they don't
            // console.log("isloggedin ", $auth.isAuthenticated());
            if ($auth.isAuthenticated()) {
                return true; // Is loggedin
            }
            return false;
        })
}]);

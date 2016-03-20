/**
 * Created by benjamin on 20.03.2016.
 */
angular.module('imageShopAdm', [
    'ui.router',
    'satellizer',
    'imageShopAdm.auth'
]).config(['$stateProvider', '$urlRouterProvider', '$authProvider', function($stateProvider, $urlRouterProvider, $authProvider) {

    $authProvider.loginUrl = 'image-shop/public/api/authenticate';
    // $urlRouterProvider.otherwise('/view1');
    $urlRouterProvider.otherwise('/auth');
}]);
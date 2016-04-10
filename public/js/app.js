/**
 * Created by benjamin on 20.03.2016.
 */
angular.module('imageShop', [
    'ui.router',
    'ui.bootstrap',
    'ngAnimate',
    'ngSanitize',
    'pascalprecht.translate',
    'imageShop.home',
    'imageShop.albums',
    'imageShop.album'
]).config(['$stateProvider', '$urlRouterProvider', '$translateProvider', function($stateProvider, $urlRouterProvider, $translateProvider) {
    $urlRouterProvider.otherwise('/');

    //Translation
    $translateProvider.useStaticFilesLoader({
        prefix: 'lang/',
        suffix: '.json'
    });

    $translateProvider.useSanitizeValueStrategy('sanitize');

    $translateProvider.preferredLanguage('de_CH');
}]).run(['$rootScope', '$state', function ($rootScope, $state) {

}]);

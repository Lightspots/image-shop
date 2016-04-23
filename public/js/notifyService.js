/**
 * Created by benjamin on 23.04.2016.
 */
angular.module('imageShop.notifyService', [])
    .service('notifyService', ['$translate', 'ngNotify', function ($translate, ngNotify) {
        var vm = this;

        this.warn = function (key) {
            $translate(key).then(function (txt) {
                ngNotify.set(txt, {
                    type: 'warn',
                    duration: 5000,
                    html: true
                });
            });
        };

        this.error = function (key) {
            $translate(key).then(function (txt) {
                ngNotify.set(txt, {
                    type: 'error',
                    duration: 5000,
                    html: true
                });
            });
        };

        this.success = function (key) {
            $translate(key).then(function (txt) {
                ngNotify.set(txt, {
                    type: 'success',
                    duration: 5000,
                    html: true
                });
            });
        }
    }]);

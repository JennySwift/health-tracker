//var $page = window.location.pathname;
var app = angular.module('tracker', [
    'ngSanitize',
    'ngAnimate',
    'checklist-model'
]);

app.config(function ($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});

app.run(runBlock);

function runBlock ($rootScope, ErrorsFactory) {

    $rootScope.show = {
        popups: {}
    };

    $rootScope.responseError = function (response) {
        $rootScope.$broadcast('provideFeedback', ErrorsFactory.responseError(response), 'error');
        $rootScope.hideLoading();
    };

    $rootScope.closePopup = function ($event, $popup) {
        var $target = $event.target;
        if ($target.className === 'popup-outer') {
            $rootScope.show.popups[$popup] = false;
        }
    };

    $rootScope.showLoading = function () {
        $rootScope.loading = true;
    };

    $rootScope.hideLoading = function () {
        $rootScope.loading = false;
    };

    /**
     * old code
     */

    /**
     * media queries
     * $scope.apply works how I want it but it keeps causing a firebug error
     */

    //enquire.register("screen and (min-width: 600px", {
    //    match: function () {
    //        if ($scope.tab.food_entries || $scope.tab.exercise_entries) {
    //            $scope.changeTab('entries');
    //            // $scope.$apply();
    //        }
    //    },
    //    unmatch: function () {
    //        if ($scope.tab.entries) {
    //            $scope.changeTab('food_entries');
    //            // $scope.$apply();
    //        }
    //    }
    //});


    new Vue({
        el: 'body',
        events: {

        }
    });
}

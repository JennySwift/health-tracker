var app = angular.module('tracker');

(function () {
    app.controller('payer', function ($scope, $http, $interval, $timeout, ProjectsFactory) {

        /**
         * scope properties
         */

        $scope.projects = payer_projects;

        $scope.payees = payees;
        $scope.new_project = {};
        $scope.show = {
            popups: {}
        };
        $scope.project_popup = {
            is_timing: false
        };
        $scope.selected = {};

        /**
         * watches
         */

        /**
         * select
         */

        /**
         * insert
         */

        $scope.insertProject = function () {
            ProjectsFactory.insertProject($scope.new_project.email, $scope.new_project.description, $scope.new_project.rate).then(function (response) {
                //$scope.projects = response.data;
            });
        };

        $scope.addPayer = function ($keycode) {
            if ($keycode !== 13) {
                return false;
            }
            ProjectsFactory.addPayer().then(function (response) {
                $scope.payers = response.data;
            });
        };

        /**
         * update
         */

        /**
         * delete
         */

        /**
         * other
         */

        $scope.showProjectPopup = function ($project) {
            $scope.selected.project = $project;
            $scope.show.popups.project = true;
        };

        $scope.closePopup = function ($event, $popup) {
            var $target = $event.target;
            if ($target.className === 'popup-outer') {
                $scope.show.popups[$popup] = false;
            }
        };

        $scope.resetTimer = function () {
            $scope.project_popup.timer_time = {
                hours: 0,
                minutes: 0,
                seconds: 0
            };
        };

        $scope.newMinute = function () {
            $scope.project_popup.timer_time.seconds = 0;
            $scope.project_popup.timer_time.minutes+= 1;
        };

        $scope.newHour = function () {
            $scope.project_popup.timer_time.seconds = 0;
            $scope.project_popup.timer_time.minutes = 0;
            $scope.project_popup.timer_time.hours+= 1;
        };

        /**
         * page load
         */

        $scope.resetTimer();

    }); //end controller

})();
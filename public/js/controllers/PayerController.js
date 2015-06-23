var app = angular.module('tracker');

(function () {
    app.controller('payer', function ($scope, $http, $interval, $timeout, ProjectsFactory) {

        /**
         * scope properties
         */

        $scope.projects = payer_projects;
        $scope.me = me;
        $scope.payees = payees;
        $scope.new_project = {};
        $scope.show = {
            popups: {}
        };
        $scope.project_popup = {
            is_timing: false
        };
        $scope.selected = {};
        $scope.flash_messages = [];

        /**
         * Pusher
         * @type {string}
         */
        var $public_key = '0559aebf9ae96524872b';
        var pusher = new Pusher($public_key);

        var channel = pusher.subscribe('testChannel');

        channel.bind('testEvent', function(data) {
            if ($scope.me.id === data.payer_id) {
                $scope.flash_messages.push(data.message);
                $scope.$apply();
                //alert(data.message);
            }
        });

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
            ProjectsFactory.showProject($project).then(function (response) {
                $scope.selected.project = response.data;
                $scope.show.popups.project = true;
            });
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
                seconds: 0,
                formatted_seconds: '00',
                formatted_minutes: '00',
                formatted_hours: '00'
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
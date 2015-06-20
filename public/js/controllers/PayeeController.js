var app = angular.module('tracker');

(function () {
    app.controller('payee', function ($scope, $http, $interval, $timeout, ProjectsFactory) {

        /**
         * scope properties
         */

        $scope.projects = payee_projects;

        $scope.payers = payers;
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

        $scope.$watch('project_popup.timer_time.seconds', function (newValue, oldValue) {
            if (newValue < 10) {
                $scope.project_popup.timer_time.formatted_seconds = '0' + newValue;
            }
            else {
                $scope.project_popup.timer_time.formatted_seconds = newValue;
            }
        });

        $scope.$watch('project_popup.timer_time.minutes', function (newValue, oldValue) {
            if (newValue < 10) {
                $scope.project_popup.timer_time.formatted_minutes = '0' + newValue;
            }
            else {
                $scope.project_popup.timer_time.formatted_minutes = newValue;
            }
        });

        $scope.$watch('project_popup.timer_time.hours', function (newValue, oldValue) {
            if (newValue < 10) {
                $scope.project_popup.timer_time.formatted_hours = '0' + newValue;
            }
            else {
                $scope.project_popup.timer_time.formatted_hours = newValue;
            }
        });

        /**
         * select
         */

        /**
         * insert
         */

        $scope.insertProject = function ($keycode) {
            if ($keycode !== 13)
            {
                return false;
            }
            ProjectsFactory.insertProject($scope.new_project.email, $scope.new_project.description, $scope.new_project.rate).then(function (response) {
                $scope.projects = response.data;
            });
        };

        $scope.startProjectTimer = function () {
            ProjectsFactory.startProjectTimer($scope.selected.project.id).then(function (response) {
                $scope.projects = response.data.projects;
                $scope.selected.project = response.data.project;
                $scope.resetTimer();
                $scope.project_popup.is_timing = true;

                $scope.counter = $interval(function () {
                    if ($scope.project_popup.timer_time.seconds < 59) {
                        $scope.project_popup.timer_time.seconds+= 1;
                    }
                    else if ($scope.project_popup.timer_time.minutes < 59) {
                        $scope.newMinute();
                    }
                    else {
                        $scope.newHour();
                    }

                }, 1000);
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

        $scope.stopProjectTimer = function () {
            ProjectsFactory.stopProjectTimer($scope.selected.project.id).then(function (response) {
                $scope.projects = response.data.projects;
                $scope.selected.project = response.data.project;
                $interval.cancel($scope.counter);
                $scope.project_popup.is_timing = false;
            });
        };

        $scope.markAsPaid = function ($payer) {
            if (confirm("Are you sure? " + $payer.name + " will no longer owe you any money.")) {
                ProjectsFactory.markAsPaid($payer.id).then(function (response) {
                    //$scope. = response.data;
                });
            }
        };

        /**
         * delete
         */

        $scope.removePayer = function ($payer) {
            if (confirm("Are you sure? This will delete all data associated with this payer.")) {
                ProjectsFactory.removePayer($payer.id).then(function (response) {
                    //Remove the payer from the payers
                    $scope.payers = _.without($scope.payers, $payer);
                    //Remove all the projects that were with the payer
                    var $projects_with_payer = _.where($scope.projects, {payer_id: $payer.id});
                    $scope.projects = _.difference($scope.projects, $projects_with_payer);
                });
            }
        };

        $scope.deleteProject = function ($project) {
            ProjectsFactory.deleteProject($project).then(function (response) {
                //$scope.projects = response.data;
                $scope.projects.payee = _.without($scope.projects.payee, $project);
            });
        };

        $scope.deleteTimer = function ($timer) {
            ProjectsFactory.deleteTimer($timer).then(function (response) {
                //$scope.projects = response.data;
                //$scope.projects[$context] = _.without($scope.projects[$context], $project);
                $scope.selected.project.timers = _.without($scope.selected.project.timers, $timer);
            });
        };

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
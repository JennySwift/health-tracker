var app = angular.module('tracker');

(function () {
	app.controller('projects', function ($scope, $http, $interval, $timeout, ProjectsFactory) {

		/**
		 * scope properties
		 */

        $scope.projects = {
            payer: payer_projects,
            payee: payee_projects
        };

        $scope.payers = payers;
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

        //$scope.$watch('project_popup.timer_time.seconds', function (newValue, oldValue) {
        //
        //});

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
                    if ($scope.project_popup.timer_time.seconds < 2) {
                        $scope.project_popup.timer_time.seconds+= 1;
                    }
                    else if ($scope.project_popup.timer_time.minutes < 2) {
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

		/**
		 * delete
		 */
		
		$scope.deleteProject = function ($project, $context) {
			ProjectsFactory.deleteProject($project, $context).then(function (response) {
				//$scope.projects = response.data;
                $scope.projects[$context] = _.without($scope.projects[$context], $project);
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
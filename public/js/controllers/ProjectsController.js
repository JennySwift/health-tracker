var app = angular.module('tracker');

(function () {
	app.controller('projects', function ($scope, $http, $interval, $timeout, ProjectsFactory) {

		/**
		 * scope properties
		 */
		
		$scope.projects = projects;
		$scope.new_project = {};
        $scope.show = {
            popups: {}
        };
        $scope.project_popup = {};
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
		
		 $scope.insertProject = function () {
		 	ProjectsFactory.insertProject($scope.new_project.email, $scope.new_project.description, $scope.new_project.rate).then(function (response) {
		 		$scope.projects = response.data;
		 	});
		 };

        $scope.startProjectTimer = function () {
            ProjectsFactory.startProjectTimer($scope.selected.project.id).then(function (response) {
                $scope.projects = response.data;
                $scope.resetTimer();

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

		/**
		 * update
		 */

        $scope.stopProjectTimer = function () {
            ProjectsFactory.stopProjectTimer($scope.selected.project.id).then(function (response) {
                $scope.projects = response.data;
                $interval.cancel($scope.counter);
                //clearInterval($scope.counter);
                //console.log($scope.project_popup.timer_time);
            });
        };

		/**
		 * delete
		 */
		
		$scope.deleteProject = function ($project, $context) {
			ProjectsFactory.deleteProject($scope, $project, $context).then(function (response) {
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
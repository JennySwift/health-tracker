var app = angular.module('tracker');

(function () {
	app.controller('projects', function ($scope, $http, ProjectsFactory) {

		/**
		 * scope properties
		 */
		
		$scope.projects = projects;
		$scope.new_project = {};
        $scope.show = {
            popups: {}
        };
        $scope.selected = {};

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
            });
        };

		/**
		 * update
		 */

        $scope.stopProjectTimer = function () {
            ProjectsFactory.stopProjectTimer($scope.selected.project.id).then(function (response) {
                $scope.projects = response.data;
            });
        };

		/**
		 * delete
		 */
		
		$scope.deleteProject = function ($project, $context) {
			ProjectsFactory.deleteProject($scope, $project, $context).then(function (response) {
				//$scope.projects = response.data;
                console.log($scope.projects);
			});
            // The then method should disapear or you could show a confirmation message
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
		
	}); //end controller

})();
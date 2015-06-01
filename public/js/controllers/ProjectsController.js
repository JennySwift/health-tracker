var app = angular.module('tracker');

(function () {
	app.controller('projects', function ($scope, $http, ProjectsFactory) {

		/**
		 * scope properties
		 */
		
		$scope.projects = projects;
		$scope.new_project = {};

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

		/**
		 * update
		 */
		
		/**
		 * delete
		 */
		
		
	}); //end controller

})();
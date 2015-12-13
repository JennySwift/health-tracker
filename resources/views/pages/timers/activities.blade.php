<!DOCTYPE html>
<html lang="en" ng-app="tracker">
<head>
	<meta charset="UTF-8">
	<title>tracker</title>
	@include('templates.head-links')
</head>
<body ng-controller="ActivitiesController">

	@include('templates.header')

	<div class="container">

        {{--@include('templates.date-navigation')--}}

        <div id="activities">

            <h3>New activity</h3>
            
            <form id="new-activity">

                <div>
                    <label for="new-activity-name">Name</label>
                    <input ng-model="newActivity.name" type="text" id="new-activity-name" name="new-activity-name" placeholder="name" class="form-control"/>
                </div>

                <div>
                    <label for="new-activity-color">Color</label>
                    <input ng-model="newActivity.color" type="text" id="new-activity-color" name="new-activity-color" placeholder="color" class="form-control"/>
                </div>

                <div>
                    <button ng-click="insertActivity()" class="btn btn-success">Save</button>
                </div>

            </form>

            <h3>Activities</h3>

            <table class="table table-bordered">
                <tr>
                    <th>Name</th>
                    <th>Colour</th>
                    <th>Total duration</th>
                </tr>

                <tr ng-repeat="activity in activities" ng-click="showEditActivity(activity)" class="activity">
                    <td><span ng-style="{'background': activity.color}" class="label label-default">[[activity.name]]</span></td>
                    <td>[[activity.color]]</td>
                    <td>[[activity.totalMinutes | formatDurationFilter]]</td>
                </tr>

            </table>

            <form ng-show="editingActivity" id="selected-activity">
            
                <div>
                    <label for="selected-activity-name">Name</label>
                    <input ng-model="selectedActivity.name" type="text" id="selected-activity-name" name="selected-activity-name" placeholder="name" class="form-control"/>
                </div>
                
                <div>
                    <label for="selected-activity-color">Color</label>
                    <input ng-model="selectedActivity.color" type="text" id="selected-activity-color" name="selected-activity-color" placeholder="color" class="form-control"/>
                </div>
                
                <div>
                    <button ng-click="updateActivity(selectedActivity)" class="btn btn-success">Save</button>
                    <button ng-click="selectedActivity = false" class="btn btn-default">Cancel</button>
                    <button ng-click="deleteActivity(selectedActivity)" class="btn btn-danger">Delete</button>
                </div>
            
            </form>

        </div>

	</div>

	@include('templates.footer')

</body>
</html>

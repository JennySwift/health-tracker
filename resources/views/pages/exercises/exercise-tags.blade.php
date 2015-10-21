<!DOCTYPE html>
<html lang="en" ng-app="tracker">
<head>
    <meta charset="UTF-8">
    <title>tracker</title>
    @include('templates.head-links')
</head>
<body>

@include('templates.header')

<div ng-controller="exerciseTags" id="exercises" class="container">

    <div>
        <div class="margin-bottom">
            <h2 class="center">Tags</h2>
            <input ng-keyup="insertExerciseTag($event.keyCode)" type="text" placeholder="Add a new tag"  id="create-exercise-tag" class="form-control">
        </div>

        <table class="table table-bordered">
            <tr>
                <th>tag</th>
                <th>x</th>
            </tr>
            <tr ng-repeat="tag in exercise_tags">
                <td>[[tag.name]]</td>
                <td><i ng-click="deleteExerciseTag(tag.id)" class="delete-item fa fa-times"></i></td>
            </tr>
        </table>
    </div>

</div>

@include('templates.footer')

</body>
</html>
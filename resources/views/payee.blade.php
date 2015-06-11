<!DOCTYPE html>
<html lang="en" ng-app="tracker">
<head>
    <meta charset="UTF-8">
    <title>tracker</title>
    <?php
    include(base_path() . '/resources/views/templates/config.php');
    include($head_links);
    ?>
</head>
<body>

@include('templates.header')

<div ng-controller="payee" id="projects" class="container">


    {{--    @include($templates . '/popups/project/index.php')--}}
    @include('templates.popups.project.index')


    <div class="flex margin-bottom">
        <input ng-keyup="addPayer($event.keyCode)" type="text" placeholder="enter payer's email" id="new-payer-email"/>
        <button ng-click="addPayer(13)" class="btn btn-default">Add payer</button>
    </div>


    <div class="flex margin-bottom">
        <select ng-model="new_project.email" title="something">
            <option ng-repeat="payer in payers" ng-value="payer.email">[[payer.name]]</option>
        </select>
        <input ng-model="new_project.description" type="text" placeholder="description">
        <input ng-model="new_project.rate" ng-keyup="insertProject($event.keyCode)" type="text" placeholder="rate">
        <button ng-click="insertProject(13)">Create project</button>
    </div>

    <div class="flex">
        <div>
            <table class="table table-bordered margin-bottom">
                <caption>Your payers</caption>
                <tr>
                    <th></th>
                    <th>name</th>
                    <th>owed</th>
                </tr>

                <tr ng-repeat="payer in payers">
                    <td><img ng-src="[[payer.gravatar]]" alt=""></td>
                    <td>[[payer.name]]</td>
                    <td>[[payer.owed]]</td>
                </tr>
            </table>
        </div>

    </div>


    <h1>User is payee</h1>

    <table class="table table-bordered margin-bottom-lg">
        <tr>
            <th>Payer</th>
            <th>Description</th>
            <th>Rate/hour</th>
            <th>Time</th>
            <th>$</th>
            {{--<th>Paid</th>--}}
            <th></th>
        </tr>
        <tr ng-repeat="project in projects.payee">
            <td ng-click="showProjectPopup(project)" class="pointer">[[project.payer.name]]
                <img ng-src="[[project.payer.gravatar]]" alt="">
            </td>
            <td ng-click="showProjectPopup(project)" class="pointer">[[project.description]]</td>
            <td ng-click="showProjectPopup(project)" class="pointer">$[[project.rate_per_hour]]</td>
            <td ng-click="showProjectPopup(project)" class="pointer">
                [[project.total_time_formatted.hours]]:[[project.total_time_formatted.minutes]]:[[project.total_time_formatted.seconds]]
            </td>
            <td ng-click="showProjectPopup(project)" class="pointer">[[project.formatted_price]]</td>
            {{--<td>--}}
            {{--<span ng-if="!project.paid" class="label label-danger">unpaid</span>--}}
            {{--<span ng-if="project.paid" class="label label-success">paid</span>--}}
            {{--</td>--}}
            <td>
                <button ng-click="deleteProject(project, 'payee')" class="btn btn-xs">delete</button>
            </td>
        </tr>

    </table>

</div>

<?php include($footer); ?>

@include('footer')

</body>
</html>
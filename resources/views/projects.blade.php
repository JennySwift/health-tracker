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

<div ng-controller="projects" id="projects" class="container">

    <?php include($templates . '/popups/project/index.php'); ?>

    <div class="flex margin-bottom">
        <input ng-keyup="addPayer($event.keyCode)" type="text" placeholder="enter payer's email" id="new-payer-email"/>
        <button ng-click="addPayer(13)" class="btn btn-default">Add payer</button>
    </div>

    <div class="flex margin-bottom">
        <select ng-model="new_project.email">
            <option ng-repeat="payer in payers" ng-value="payer.email">[[payer.name]]</option>
        </select>
        <input ng-model="new_project.description" type="text" placeholder="description">
        <input ng-model="new_project.rate" type="text" placeholder="rate">
        <button ng-click="insertProject()">Create project</button>
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
                    [[project.total_time_user_formatted.hours]]:[[project.total_time_user_formatted.minutes]]:[[project.total_time_user_formatted.seconds]]
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

        <h1>User is payer</h1>

        <table class="table table-bordered">
            <tr>
                <th>Payee</th>
                <th>Description</th>
                <th>Rate/hour</th>
                <th>Time</th>
                <th>$</th>
                {{--<th>Paid</th>--}}
                <th></th>
            </tr>
            <tr ng-repeat="project in projects.payer">
                <td ng-click="showProjectPopup(project)" class="pointer">[[project.payee.name]]
                    <img ng-src="[[project.payee.gravatar]]">
                </td>
                <td ng-click="showProjectPopup(project)" class="pointer">[[project.description]]</td>
                <td ng-click="showProjectPopup(project)" class="pointer">$[[project.rate_per_hour]]</td>
                <td ng-click="showProjectPopup(project)" class="pointer">
                    [[project.total_time_user_formatted.hours]]:[[project.total_time_user_formatted.minutes]]:[[project.total_time_user_formatted.seconds]]
                </td>
                <td ng-click="showProjectPopup(project)" class="pointer">[[project.formatted_price]]</td>
                {{--<td>--}}
                {{--<span ng-if="!project.paid" class="label label-danger">unpaid</span>--}}
                {{--<span ng-if="project.paid" class="label label-success">paid</span>--}}
                {{--</td>--}}
                <td>
                    <button ng-click="deleteProject(project, 'payer')" class="btn btn-xs">delete</button>
                </td>
            </tr>

        </table>

    </div>

    <?php include($footer); ?>

    @include('footer')

</body>
</html>
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

<div ng-controller="payer" id="projects" class="container">


    {{--    @include($templates . '/popups/project/index.php')--}}
    @include('templates.popups.project.index')

    <div class="flex">

        <div>
            <table class="table table-bordered margin-bottom">
                <caption>Your payees</caption>
                <tr>
                    <th></th>
                    <th>name</th>
                    <th>owed</th>
                </tr>

                <tr ng-repeat="payee in payees">
                    <td><img ng-src="[[payee.gravatar]]" alt=""></td>
                    <td>[[payee.name]]</td>
                    <td>[[payee.owed]]</td>
                </tr>
            </table>
        </div>

    </div>

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
                [[project.total_time_formatted.hours]]:[[project.total_time_formatted.minutes]]:[[project.total_time_formatted.seconds]]
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
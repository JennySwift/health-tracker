<div ng-show="show.popups.project" ng-click="closePopup($event, 'project')" class="popup-outer">

	<div class="popup-inner">

        <h1>Project</h1>

        <table class="table table-bordered">
            <tr>
                <th>Payer</th>
                <th>Description</th>
                <th>Rate/hour</th>
                <th>Time</th>
                <th>$</th>
            </tr>

            <tr>
                <td>[[selected.project.payer.name]] <img ng-src="[[selected.project.payer.gravatar]]" alt=""></td>
                <td>[[selected.project.description]]</td>
                <td>$[[selected.project.rate_per_hour]]</td>
                <td>[[selected.project.total_time_user_formatted]]</td>
                <td>[[selected.project.price]]</td>
            </tr>
        </table>
        <h1>Timers</h1>

        <table class="table table-bordered">
            <tr>
                <th>Start</th>
                <th>Finish</th>
                <th>Paid</th>
                <th>Paid At</th>
            </tr>
            <tr ng-repeat="timer in selected.project.timers">
                <td>[[timer.start]]</td>
                <td>[[timer.finish]]</td>
                <td>
                    <span ng-if="!timer.paid" class="label label-danger">unpaid</span>
                    <span ng-if="timer.paid" class="label label-success">paid</span>
                </td>
                <td>[[timer.time_of_payment]]</td>
            </tr>
        </table>
	</div>

</div>
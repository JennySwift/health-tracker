<div ng-show="show.popups.project" ng-click="closePopup($event, 'project')" class="popup-outer">

	<div id="project-popup" class="popup-inner">

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

        <div class="flex">
            <button ng-click="startProjectTimer()" class="btn btn-success">Start</button>
            <button ng-click="stopProjectTimer()" class="btn btn-danger">Stop</button>
        </div>

        <div class="flex">
            <h1>[[project_popup.timer_time.hours]]:[[project_popup.timer_time.minutes]]:[[project_popup.timer_time.seconds]]</h1>
        </div>

        <h1>Timers</h1>

        <table class="table table-bordered">
            <tr>
                <th>Start</th>
                <th>Finish</th>
                <th>Paid</th>
                <th>Paid At</th>
                <th></th>
            </tr>
            <tr ng-repeat="timer in selected.project.timers">
                <td>[[timer.start]]</td>
                <td>[[timer.finish]]</td>
                <td>
                    <span ng-if="!timer.paid" class="label label-danger">unpaid</span>
                    <span ng-if="timer.paid" class="label label-success">paid</span>
                </td>
                <td>[[timer.time_of_payment]]</td>
                <td>
                    <button ng-click="deleteTimer(timer)" class="btn btn-default btn-xs">delete</button>
                </td>
            </tr>
        </table>
	</div>

</div>
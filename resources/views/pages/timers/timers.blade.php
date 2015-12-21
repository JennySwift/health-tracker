<ul id="timers" class="list-group">
    <li ng-repeat="timer in timers | filter: activitiesFilter | orderBy: 'start':true" class="list-group-item timer">
        <div>
            <i ng-click="deleteTimer(timer)" class="fa fa-times .delete"></i>
            <span ng-style="{'background': timer.activity.data.color}" class="label">[[timer.activity.data.name]]</span>
        </div>

        <div class="duration">
            <span>[[timer.hours]]:[[timer.formattedMinutes]]</span>
        </div>

        <div>
            <span>[[timer.formattedStart]]</span>
            <span> - </span>
            <span>[[timer.formattedFinish]]</span>
        </div>

    </li>
</ul>
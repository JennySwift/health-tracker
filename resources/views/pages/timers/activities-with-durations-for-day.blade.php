<div id="activities-with-durations-for-day">
    <span
        v-for="activity in activitiesWithDurationsForTheDay | filter: activitiesFilter"
        v-bind:style="{'background': activity.color}"
        class="label label-default">
        @{{ activity.name }} @{{ activity.totalMinutesForDay | formatDurationFilter }}</span>
</div>
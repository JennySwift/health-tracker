<div id="activities-with-durations-for-day">
    <span
        v-for="activity in activitiesWithDurationsForTheDay | filterBy activitiesFilter in 'name'"
        v-bind:style="{'background': activity.color}"
        class="label label-default">
        @{{ activity.name }} @{{ activity.totalMinutesForDay | formatDuration }}</span>
</div>
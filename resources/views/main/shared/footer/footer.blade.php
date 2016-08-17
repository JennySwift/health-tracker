@include('main.shared.footer.real-footer')

{{--Entries--}}
@include('main.entries.entries-page-component')
@include('main.menu.entries.menu-entries-component')
@include('main.entries.weight-component')

{{--Journal--}}
@include('main.journal.journal-page-component')
@include('main.new-sleep-entry')

{{--Timers--}}
@include('main.timers.timers-page-component')
@include('main.timers.graphs-page-component')
@include('main.timers.activities-page-component')
@include('main.timers.activity-popup-component')
@include('main.timers.timer-popup-component')
@include('main.timers.new-timer-component')
@include('main.timers.new-manual-timer-component')

{{--Exercises--}}
@include('main.exercises.exercises-page-component')
@include('main.exercises.new-exercise-component')
@include('main.exercises.new-series-component')
@include('main.exercises.exercise-units-page-component')
@include('main.exercises.exercise-series-history-popup-component')
@include('main.exercises.exercise-series-popup-component')
@include('main.exercises.exercise-popup-component')
@include('main.exercises.exercise-entries-component')
@include('main.exercises.new-exercise-entry-component')
@include('main.exercises.entries-for-specific-exercise-and-date-and-unit-popup-component')

{{--Menu--}}
@include('main.menu.recipes.recipes-page-component')
@include('main.menu.recipes.new-quick-recipe-component')
@include('main.menu.recipes.recipes-component')
@include('main.menu.recipes.recipe-tags-component')
@include('main.menu.recipes.recipe-popup.recipe-popup-component')
@include('main.menu.foods.foods-page-component')
@include('main.menu.foods.food-units-page-component')
@include('main.menu.foods.food-popup-component')
@include('main.menu.entries.temporary-recipe-popup-component')

{{--Shared--}}
@include('main.menu.entries.new-menu-entry-component')
@include('main.menu.entries.new-food-entry-component')
@include('main.shared.feedback-component')
@include('main.shared.loading-component')
@include('main.shared.autocomplete-component')
@include('main.shared.date-navigation-component')
@include('main.shared.header.navbar-component')

{{--Google Analytics--}}
@include('main.shared.analytics-tracking')

{{--<script type="text/javascript" src="{{ elixir("js/all.js") }}"></script>--}}
<script type="text/javascript" src="/build/js/bundle.js"></script>
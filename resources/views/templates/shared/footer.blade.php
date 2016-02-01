@include('templates.shared.real-footer')

@include('pages.exercises.exercise-units-page-component')
@include('pages.entries.entries-page-component')
@include('pages.entries.menu-entries-component')
@include('pages.entries.exercise-entries-component')
@include('pages.journal.journal-page-component')
@include('pages.menu.recipes.recipes-page-component')
@include('pages.menu.recipes.new-quick-recipe-component')
@include('pages.menu.recipes.recipes-component')
@include('pages.menu.recipes.recipe-tags-component')
@include('pages.menu.recipes.popups.recipe-popup.recipe-popup-component')
@include('pages.menu.recipes.popups.recipe-popup.new-item-with-autocomplete-component')
@include('templates.shared.feedback-component')
@include('templates.shared.loading-component')
@include('templates.shared.date-navigation-component')

<script type="text/javascript" src="{{ elixir("js/all.js") }}"></script>

{{--<script type="text/javascript" src="/jasmine/lib/jasmine-2.3.4/jasmine.js"></script>--}}
{{--<script type="text/javascript" src="/jasmine/lib/jasmine-2.3.4/jasmine-html.js"></script>--}}
{{--<script type="text/javascript" src="/jasmine/lib/jasmine-2.3.4/boot.js"></script>--}}

@include('directive-templates.feedback')
@include('directive-templates.date-navigation')
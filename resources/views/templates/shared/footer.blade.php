@include('templates.shared.real-footer')

@include('pages.exercises.exercise-units-page-component')
@include('pages.menu.recipes.recipes-page-component')
@include('pages.menu.recipes.recipes-component')
@include('pages.menu.recipes.recipe-tags-component')
@include('templates.shared.feedback-component')
@include('templates.shared.loading-component')

<script type="text/javascript" src="{{ elixir("js/all.js") }}"></script>

{{--<script type="text/javascript" src="/jasmine/lib/jasmine-2.3.4/jasmine.js"></script>--}}
{{--<script type="text/javascript" src="/jasmine/lib/jasmine-2.3.4/jasmine-html.js"></script>--}}
{{--<script type="text/javascript" src="/jasmine/lib/jasmine-2.3.4/boot.js"></script>--}}

@include('directive-templates.feedback')
@include('directive-templates.date-navigation')
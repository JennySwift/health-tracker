@include('templates.shared.real-footer')

{{--<script type="text/javascript" src="/js/all.js"></script>--}}
<script type="text/javascript" src="{{ elixir("js/all.js") }}"></script>

{{--<script type="text/javascript" src="/jasmine/lib/jasmine-2.3.4/jasmine.js"></script>--}}
{{--<script type="text/javascript" src="/jasmine/lib/jasmine-2.3.4/jasmine-html.js"></script>--}}
{{--<script type="text/javascript" src="/jasmine/lib/jasmine-2.3.4/boot.js"></script>--}}

@include('directive-templates.feedback')
@include('directive-templates.date-navigation')
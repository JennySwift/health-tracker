<script id="entries-page-template" type="x-template">

<div>

{{--    @include('pages.entries.index')--}}

    <date-navigation
        :date.sync="date"
    >
    </date-navigation>

    <div id="info-entries-wrapper">
        @include('pages.entries.info')
    </div>

    <div id="entries">
        <menu-entries
            :date="date"
        >
        </menu-entries>

        <div>
            <new-exercise-entry>

            </new-exercise-entry>

            <exercise-entries
                    :date="date"
            >
            </exercise-entries>
        </div>

</div>

</script>
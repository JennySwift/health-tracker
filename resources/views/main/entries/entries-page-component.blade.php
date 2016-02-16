<script id="entries-page-template" type="x-template">

<div>

    <date-navigation
        :date.sync="date"
    >
    </date-navigation>

    <div id="info-entries-wrapper">
        @include('main.entries.info')
    </div>

    <div id="entries">

        <div>
            <new-menu-entry
                :date="date"
            >
            </new-menu-entry>

            <menu-entries
                :date="date"
            >
            </menu-entries>
        </div>

        <div>
            <new-exercise-entry
                :date="date"
            >
            </new-exercise-entry>

            <exercise-entries
                    :date="date"
            >
            </exercise-entries>
        </div>

</div>

</script>
<script id="journal-page-template" type="x-template">

    <div id="journal">

        <date-navigation
            :date.sync="date"
        >
        </date-navigation>

        <input
                v-on:keyup.13="filterJournalEntries()"
                type="text"
                placeholder="search entries"
                id="filter-journal"
                class="margin-bottom"
        />

        <new-sleep-entry></new-sleep-entry>

        <div
                v-show="filterResults.length > 0"
                id="filter-results-container"
        >
            <i v-on:click="clearFilterResults()" class="fa fa-close"></i>

            <div id="filter-results">
                <div
                        v-for="entry in filterResults"
                        v-on:click="changeDate(entry.date)"
                        class="hover pointer"
                >
                    <span class="badge">@{{ entry.date }}</span>
                    <div>@{{{ entry.text }}}</div>
                </div>
            </div>
        </div>

        <div class="btn-toolbar" data-role="editor-toolbar" data-target="#wysiwyg">
            <a data-edit="bold" class="fa fa-bold"></a>
        </div>

        <div
            v-html="journalEntry.text"
            id="journal-entry"
            class="wysiwyg margin-bottom"
        >
        </div>

        <button v-on:click="insertOrUpdateJournalEntry()" class="btn btn-success save">Save entry</button>

    </div>

</script>
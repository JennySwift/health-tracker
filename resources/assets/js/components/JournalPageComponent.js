var JournalPage = Vue.component('journal-page', {
    template: '#journal-page-template',
    data: function () {
        return {
            date: DatesRepository.setDate(this.date),
            filterResults: [],
            journalEntry: {},
        };
    },
    components: {},
    methods: {
        /**
        *
        */
        getJournalEntry: function () {
            $.event.trigger('show-loading');
            this.$http.get('api/journal/' + this.date.sql, function (response) {
                this.journalEntry = response.data;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
        *
        */
        filterJournalEntries: function () {
            var typing = $("#filter-journal").val();

            $.event.trigger('show-loading');
            this.$http.get('/api/journal?typing=' + typing, function (response) {
                this.filterResults = response.data;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        clearFilterResults: function () {
            this.filterResults = [];
            $("#filter-journal").val("");
        },

        /**
         * If the id of the journal entry exists, update the entry.
         * If not, insert the entry.
         */
        insertOrUpdateJournalEntry: function () {
            if (this.journalEntry.id) {
                this.updateEntry();
            }
            else {
                this.insertEntry();
            }
        },


        /**
        *
        */
        updateEntry: function () {
            $.event.trigger('show-loading');

            var data = {
                text: $("#journal-entry").html()
            };

            this.$http.put('/api/journal/' + this.journalEntry.id, data, function (response) {
                this.journalEntry = response.data;
                $.event.trigger('provide-feedback', ['Entry updated', 'success']);
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
        *
        */
        insertEntry: function () {
            $.event.trigger('show-loading');
            var data = {
                date: this.date.sql,
                text: $("#journal-entry").html()
            };

            this.$http.post('/api/journal', data, function (response) {
                this.journalEntry = response.data;
                $.event.trigger('provide-feedback', ['Entry created', 'success']);
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('date-changed', function (event) {
                that.getJournalEntry();
            });
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            $.event.trigger('response-error', [response]);
            this.showLoading = false;
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        $(".wysiwyg").wysiwyg();
        this.listen();
        this.getJournalEntry();
    }
});


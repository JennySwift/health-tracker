require('bootstrap');
//This didn't work
// require('bootstrap-wysiwyg');
var MediumEditor = require('medium-editor');
// require('summernote');

module.exports = {
    template: '#journal-page-template',
    data: function () {
        return {
            date: store.state.date,
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
            store.showLoading();
            this.$http.get('api/journal/' + this.date.sql).then(function (response) {
                this.journalEntry = response.data.data;
                this.$nextTick(function () {
                    var editor = new MediumEditor('.wysiwyg', {
                        // placeholder: false
                    });
                });

                store.hideLoading();
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         * @param entry
         */
        selectJournalEntryFromFilterResults: function (entry) {
            this.date = {
                typed: entry.date,
                sql: moment(entry.date, 'DD/MM/YY').format('YYYY-MM-DD HH:mm:ss')
            }
            this.getJournalEntry();
        },

        /**
         *
         */
        filterJournalEntries: function () {
            var typing = $("#filter-journal").val();

            store.showLoading();
            this.$http.get('/api/journal?typing=' + typing, function (response) {
                this.filterResults = response.data;
                store.hideLoading();
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
        showNewSleepEntryPopup: function () {
            $.event.trigger('show-new-sleep-entry-popup');
        },

        /**
         *
         */
        updateEntry: function () {
            store.showLoading();

            var data = {
                text: $("#journal-entry").html()
            };

            this.$http.put('/api/journal/' + this.journalEntry.id, data).then(function (response) {
                this.journalEntry = response.data.data;
                $.event.trigger('provide-feedback', ['Entry updated', 'success']);
                store.hideLoading();
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        insertEntry: function () {
            store.showLoading();
            var data = {
                date: this.date.sql,
                text: $("#journal-entry").html()
            };

            this.$http.post('/api/journal', data).then(function (response) {
                this.journalEntry = response.data.data;
                $.event.trigger('provide-feedback', ['Entry created', 'success']);
                store.hideLoading();
            }, function (response) {
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
        // $(".wysiwyg").wysiwyg();

        // new MediumEditor('.editable');
        // $('.wysiwyg').summernote();
        this.listen();
        this.getJournalEntry();
    }
};


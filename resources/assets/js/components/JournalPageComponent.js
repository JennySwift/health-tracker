var MediumEditor = require('medium-editor');

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
            HelpersRepository.get('api/journal/' + this.date.sql, function (response) {
                this.journalEntry = response.data.data;
                this.$nextTick(function () {
                    var editor = new MediumEditor('.wysiwyg', {
                        // placeholder: false
                    });
                });
            }.bind(this));
        },

        /**
         *
         * @param entry
         */
        selectJournalEntryFromFilterResults: function (entry) {
            this.date = {
                typed: entry.date,
                sql: HelpersRepository.formatToDateTime(entry.date)
            };
            this.getJournalEntry();
        },

        /**
         *
         */
        filterJournalEntries: function () {
            var typing = $("#filter-journal").val();

            HelpersRepository.get('/api/journal?typing=' + typing, function (response) {
                this.filterResults = response.data.data;
            }.bind(this));
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
            var data = {
                text: $("#journal-entry").html()
            };

            HelpersRepository.put('/api/journal/' + this.journalEntry.id, data, 'Entry updated', function (response) {
                this.journalEntry = response.data.data;
            }.bind(this));
        },

        /**
         *
         */
        insertEntry: function () {
            var data = {
                date: this.date.sql,
                text: $("#journal-entry").html()
            };

            HelpersRepository.post('/api/journal', data, 'Entry created', function (response) {
                this.journalEntry = response.data.data;
            }.bind(this));
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('date-changed', function (event) {
                that.getJournalEntry();
            });
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.listen();
        this.getJournalEntry();
    }
};


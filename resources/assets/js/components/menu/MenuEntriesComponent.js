var MenuEntriesComponent = Vue.component('menu-entries', {
    template: '#menu-entries-template',
    data: function () {
        return {
            menuEntries: menuEntries,
            temporaryRecipePopup: {},
            selected: {
                dropdown_item: {},
                food: {},
                unit: {}
            }
        };
    },
    components: {},
    methods: {

        /**
        *
        */
        deleteMenuEntry: function (entry) {
            if (confirm("Are you sure?")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/menuEntries/' + entry.id, function (response) {
                    this.menuEntries = _.without(this.menuEntries, entry);
                    $.event.trigger('provide-feedback', ['MenuEntry deleted', 'success']);
                    $.event.trigger('menu-entry-deleted');
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    HelpersRepository.handleResponseError(response);
                });
            }
        },

        /**
        *
        */
        getEntriesForTheDay: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/menuEntries/' + this.date.sql, function (response) {
                this.menuEntries = response;
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
            $(document).on('menu-entry-added', function (event, entry) {
                $.event.trigger('show-loading');
                if (entry.date === that.date.sql) {
                    that.menuEntries.push(entry)
                }
            });
            $(document).on('date-changed', function (event) {
                that.getEntriesForTheDay();
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
        'date'
    ],
    ready: function () {
        this.listen();
    }
});



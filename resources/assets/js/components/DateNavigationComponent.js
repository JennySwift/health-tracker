var DateNavigation = Vue.component('date-navigation', {
    template: '#date-navigation-template',
    data: function () {
        return {

        };
    },
    components: {},
    watch: {
        'date': function (newValue, oldValue) {
            this.date.sql = Date.parse(this.date.typed).toString('yyyy-MM-dd');
            this.date.long = Date.parse(this.date.typed).toString('ddd dd MMM yyyy');
            $("#date").val(this.date.typed);
            $.event.trigger('change-date');

            if (newValue === oldValue) {
                // this.pageLoad();
            }
            else {
                this.getEntries();
            }
        }
    },
    methods: {
        /**
         *
         * @param $number
         */
        goToDate: function ($number) {
            this.date.typed = DatesRepository.goToDate(this.date.typed, $number);
        },

        /**
         *
         */
        today: function () {
            this.date.typed = DatesRepository.today();
        },

        /**
         *
         * @param $keycode
         * @param $date
         * @returns {boolean}
         */
        changeDate: function ($keycode, $date) {
            if ($keycode !== 13) {
                return false;
            }
            var $date = $date || $("#date").val();
            this.date.typed = DatesRepository.changeDate($keycode, $date);
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            this.$broadcast('response-error', response);
            this.showLoading = false;
        }

    },
    props: [
        'date'
    ],
    ready: function () {

    }

});

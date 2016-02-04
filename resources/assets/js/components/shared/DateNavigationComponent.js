var DateNavigation = Vue.component('date-navigation', {
    template: '#date-navigation-template',
    data: function () {
        return {

        };
    },
    components: {},
    watch: {
        'date.typed': function (newValue, oldValue) {
            this.date.sql = Date.parse(this.date.typed).toString('yyyy-MM-dd');
            this.date.long = Date.parse(this.date.typed).toString('ddd dd MMM yyyy');
            $("#date").val(this.date.typed);
            $.event.trigger('date-changed');
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
        goToToday: function () {
            this.date.typed = DatesRepository.today();
        },

        /**
         *
         * @param date
         * @returns {boolean}
         */
        changeDate: function (date) {
            var date = date || $("#date").val();
            this.date.typed = DatesRepository.changeDate(date);
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

var DatesRepository = require('../../repositories/DatesRepository');
// require('sugar');

module.exports = {
    template: '#date-navigation-template',
    data: function () {
        return {
            date: store.state.date
        };
    },
    components: {},
    watch: {
        'date.typed': function (newValue, oldValue) {
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
            DatesRepository.goToDate($number);
        },

        /**
         *
         */
        goToToday: function () {
            DatesRepository.today();
        },

        /**
         *
         * @param date
         * @returns {boolean}
         */
        changeDate: function (date) {
            var date = date || $("#date").val();
            DatesRepository.changeDate(date);
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

    ],
    ready: function () {

    }

};

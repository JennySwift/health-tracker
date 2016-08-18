var Vue = require('vue');
require('sugar');
var moment = require('moment');

module.exports = {

    /**
     *
     * @param response
     */
    handleResponseError: function (response) {
        $.event.trigger('response-error', [response]);
        store.hideLoading();
    },

    /**
     *
     */
    closePopup: function ($event, that) {
        if ($event.target.className === 'popup-outer') {
            that.showPopup = false;
        }
    },

    /**
     *
     */
    get: function (url, callback) {
        store.showLoading();
        Vue.http.get(url).then(function (response) {
            callback(response);
            store.hideLoading();
        }, function (response) {
            HelpersRepository.handleResponseError(response);
        });
    },

    /**
     *
     */
    post: function (url, data, feedbackMessage, callback) {
        store.showLoading();
        Vue.http.post(url, data).then(function (response) {
            callback(response);
            store.hideLoading();

            if (feedbackMessage) {
                $.event.trigger('provide-feedback', [feedbackMessage, 'success']);
            }
        }, function (response) {
            HelpersRepository.handleResponseError(response);
        });
    },

    /**
     *
     * @param array
     * @param id
     * @returns {*}
     */
    findIndexById: function (array, id) {
        return _.indexOf(array, _.findWhere(array, {id: id}));
    },

    /**
     *
     * @param array
     * @param id
     */
    deleteById: function (array, id) {
        var index = HelpersRepository.findIndexById(array, id);
        array = _.without(array, array[index]);

        return array;
    },

    /**
     *
     * @param boolean
     * @returns {number}
     */
    convertBooleanToInteger: function (boolean) {
        if (boolean) {
            return 1;
        }
        return 0;
    },

    formatDateToSql: function (date) {
        return Date.create(date).format('{yyyy}-{MM}-{dd}');
    },

    formatDateToLong: function (date) {
        return Date.create(date).format('{Weekday} {dd} {Month} {yyyy}');
    },

    formatTime: function (time) {
        return Date.create(time).format('{HH}:{mm}:{ss}');
    },

    formatToDateTime: function (time) {
        return Date.create(time).format('{yyyy}-{MM}-{dd} {HH}:{mm}:{ss}');
    },

    momentFormatToDateTime: function (time) {
        return moment(time).format('YYYY-MM-DD HH:mm:ss');
    },

    /**
     *
     * @param seconds
     * @returns {string}
     */
    formatDurationFromSeconds: function (seconds) {
        var hours = Math.floor(seconds / 3600);
        var minutes = Math.floor(seconds / 60) % 60;
        seconds = seconds % 60;

        return this.addZeros(hours) + ':' + this.addZeros(minutes) + ':' + this.addZeros(seconds);
    },

    /**
     *
     * @param number
     * @returns {*}
     */
    addZeros: function (number) {
        if (number < 10) {
            return '0' + number;
        }

        return number;
    },
};
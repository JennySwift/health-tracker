module.exports = {

    /**
     *
     * @param minutes
     * @returns {*}
     */
    formatDuration: function (minutes) {
        if (!minutes && minutes != 0) {
            return '-';
        }

        var hours = Math.floor(minutes / 60);
        if (hours < 10) {
            hours = '0' + hours;
        }

        minutes = minutes % 60;
        if (minutes < 10) {
            minutes = '0' + minutes;
        }

        return hours + ':' + minutes;
    },

    /**
     *
     * @param number
     * @param howManyDecimals
     * @returns {number}
     */
    roundNumber: function (number, howManyDecimals) {
        if (!howManyDecimals) {
            return Math.round(number);
        }
        else if (howManyDecimals === 'one') {
            var multiplyAndDivideBy = 10;
            return Math.round(number * multiplyAndDivideBy) / multiplyAndDivideBy;
        }
    }
};
var TimersRepository = {

    /**
     *
     * @param entry
     * @param date
     * @returns {{start: *}}
     */
    setData: function (entry, date) {
        var data = {
            start: this.calculateStartDateTime(entry, date)
        };

        if (entry.finish) {
            data.finish = this.calculateFinishTime(entry, date);
        }

        if (entry.activity) {
            data.activity_id = entry.activity.id;
        }

        return data;
    },

    /**
     *
     * @param byDate
     * @param date
     * @returns {string}
     */
    calculateUrl: function (byDate, date) {
        var url = '/api/timers';
        if (byDate) {
            url+= '?byDate=true';
        }
        else if (date) {
            url+= '?date=' + date;
        }

        return url;
    },

    /**
     *
     * @param entry
     * @param date
     * @returns {*}
     */
    calculateStartDateTime: function (entry, date) {
        if (date) {
            return this.calculateStartDate(entry, date) + ' ' + this.calculateStartTime(entry);
        }
        else {
            //The 'start' timer button has been clicked rather than entering the time manually, so make the start now
            return moment().format('YYYY-MM-DD HH:mm:ss');
        }

    },

    /**
     *
     * @param entry
     * @param date
     * @returns {*}
     */
    calculateStartDate: function (entry, date) {
        if (entry.startedYesterday) {
            return moment(date, 'YYYY-MM-DD').subtract(1, 'days').format('YYYY-MM-DD');
        }
        else {
            return date;
        }
    },

    /**
     *
     * @param entry
     * @param date
     * @returns {*}
     */
    calculateFinishTime: function (entry, date) {
        if (entry.finish) {
            return date + ' ' + Date.parse(entry.finish).toString('HH:mm:ss');
        }
        else {
            //The stop timer button has been pressed. Make the finish time now.
            return moment().format('YYYY-MM-DD HH:mm:ss');
        }

    },

    /**
     *
     * @param entry
     * @returns {string}
     */
    calculateStartTime: function (entry) {
        return Date.parse(entry.start).toString('HH:mm:ss');
    }
};
var TimersRepository = {
    store: function (entry, date) {
        var data = {
            start: this.calculateStartDateTime(entry, date)
        };

        if (entry.finish) {
            data.finish = this.calculateFinishTime(entry, date);
        }

        if (entry.activity) {
            data.activity_id = entry.activity.id;
        }

        return $http.post('/api/timers', data);
    }
};
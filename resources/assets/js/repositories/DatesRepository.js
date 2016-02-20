var DatesRepository = {
    setDate: function (date) {
        if (date === undefined) {
            var date = {};
        }
        if (date.typed === undefined) {
            date.typed = Date.parse('today').toString('dd/MM/yyyy');
        }
        date.long = Date.parse(date.typed).toString('ddd dd MMM yyyy');
        date.sql = Date.parse(date.typed).toString('yyyy-MM-dd');

        return date;
    },
    changeDate: function (date) {
        return Date.parseExact(date, ['d MMM yyyy', 'd/M/yyyy', 'd MMM yy', 'd/M/yy']).toString('dd/MM/yyyy');
    },
    today: function () {
        return Date.parse('today').toString('dd/MM/yyyy');
    },
    goToDate: function (previousDate, number) {
        return Date.parse(previousDate).addDays(number).toString('dd/MM/yyyy');
    }
};

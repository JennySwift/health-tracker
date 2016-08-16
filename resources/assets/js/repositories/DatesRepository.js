require('sugar');

module.exports = {
    changeDate: function (date) {
        // return Date.parseExact(date, ['d MMM yyyy', 'd/M/yyyy', 'd MMM yy', 'd/M/yy']).toString('dd/MM/yyyy');
        var date = Date.create(date).format('{yyyy}-{MM}-{dd}');
        store.setDate(date);
    },
    today: function () {
        // return Date.parse('today').toString('dd/MM/yyyy');
        var date = Date.create('today').format('{yyyy}-{MM}-{dd}');
        store.setDate(date);
    },
    goToDate: function (number) {
        // return Date.parse(previousDate).addDays(number).toString('dd/MM/yyyy');
        var date = Date.create(store.state.date.typed).addDays(number).format('{yyyy}-{MM}-{dd}');
        store.setDate(date);
    }
};

var expect = require('chai').expect;
var Vue = require('vue');
global.store = require('../resources/assets/js/repositories/SharedRepository.js');
global.HelpersRepository = require('../resources/assets/js/repositories/HelpersRepository');
Date.setLocale('en-AU');


describe('date navigation component', function () {
    var vm;

    it("is today's date initially", function () {
        vm = new Vue(require('../resources/assets/js/components/shared/DateNavigationComponent'));

        expect(vm.date).to.eql({
            typed: Date.create('today').format('{dd}/{MM}/{yyyy}'),
            long: HelpersRepository.formatDateToLong('today'),
            sql: HelpersRepository.formatDateToSql('today')
        });
    });

    it("can go to the previous day", function () {
        vm = new Vue(require('../resources/assets/js/components/shared/DateNavigationComponent'));

        vm.goToDate(-1);

        expect(vm.date).to.eql({
            typed: Date.create('yesterday').format('{dd}/{MM}/{yyyy}'),
            long: HelpersRepository.formatDateToLong('yesterday'),
            sql: HelpersRepository.formatDateToSql('yesterday')
        });
    });

    it("can go to back to today", function () {
        vm = new Vue(require('../resources/assets/js/components/shared/DateNavigationComponent'));

        //Check the date is still on yesterday's date
        expect(vm.date).to.eql({
            typed: Date.create('yesterday').format('{dd}/{MM}/{yyyy}'),
            long: HelpersRepository.formatDateToLong('yesterday'),
            sql: HelpersRepository.formatDateToSql('yesterday')
        });

        vm.goToToday();

        expect(vm.date).to.eql({
            typed: Date.create('today').format('{dd}/{MM}/{yyyy}'),
            long: HelpersRepository.formatDateToLong('today'),
            sql: HelpersRepository.formatDateToSql('today')
        });
    });

    it("can go to a specific date", function () {
        vm = new Vue(require('../resources/assets/js/components/shared/DateNavigationComponent'));

        vm.changeDate('1/4/16');

        expect(vm.date).to.eql({
            typed: '01/04/2016',
            long: 'Friday 01 April 2016',
            sql: '2016-04-01'
        });
    });
});
var expect = require('chai').expect;
var Vue = require('vue');
var moment = require('moment');
// global.store = require('../resources/assets/js/repositories/SharedRepository.js');
// global.FiltersRepository = require('../resources/assets/js/repositories/FiltersRepository.js');
// global.$ = require('jquery');
global.chalk = require('chalk');
global.obvious = chalk.green.bgBlack.bold.underline;

describe('new timer component', function () {
    var vm = new Vue(require('../resources/assets/js/components/timers/NewTimerComponent'));

    beforeEach(function () {

    });

    it('can resume a timer', function (done) {
        //Check the initial data
        expect(vm.timerInProgress).to.be.false;
        expect(vm.showTimerInProgress).to.be.true;
        expect(vm.newTimer).to.eql({
            activity: {}
        });
        expect(vm.time).to.equal('');

        //Todo: check it keeps on counting?
        vm.timerInProgress = {
            start: moment().subtract(4, 'seconds').format('YYYY-MM-DD HH:mm:ss')
        };

        vm.setTimerInProgress();

        setTimeout(function () {
            expect(vm.time).to.equal(5);
            done();
        }, 1000);
    });

    it('can add a manual timer', function () {
        var timer = {
            start: '2016-08-01 13:00:00',
            finish: '2016-08-01 14:00:00',
            activity: {
                id: 1
            }
        };

        store.addTimer(timer, true);
        expect(store.state.timers).to.include(timer);

        //Todo: test route goes back to /timers
    });
});
var expect = require('chai').expect;
var Vue = require('vue');
var moment = require('moment');
// global.store = require('../resources/assets/js/repositories/SharedRepository.js');
// global.FiltersRepository = require('../resources/assets/js/repositories/FiltersRepository.js');
// global.$ = require('jquery');

describe('new timer component', function () {
    var vm = new Vue(require('../resources/assets/js/components/timers/NewTimerComponent'));

    beforeEach(function () {
        //Check the initial data
        expect(vm.timerInProgress).to.be.false;
        expect(vm.showTimerInProgress).to.be.true;
        expect(vm.newTimer).to.eql({
            activity: {}
        });
        expect(vm.time).to.equal('');
    });

    it('can resume a timer', function (done) {
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
});
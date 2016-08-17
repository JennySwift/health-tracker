var expect = require('chai').expect;
var Vue = require('vue');
global.store = require('../resources/assets/js/repositories/SharedRepository.js');
global.FiltersRepository = require('../resources/assets/js/repositories/FiltersRepository.js');

describe('filters', function () {
    it('can round a number to one decimal place', function () {
        var number = FiltersRepository.roundNumber(10.246, 1);
        expect(number).to.equal(10.2);
    });

    it('can round a number to two decimal places', function () {
        var number = FiltersRepository.roundNumber(10.246, 2);
        expect(number).to.equal(10.25);
    });

    it('can round a number to three decimal places', function () {
        var number = FiltersRepository.roundNumber(10.2469, 3);
        expect(number).to.equal(10.247);
    });

    it('can format the seconds', function () {
        var result = HelpersRepository.formatDurationFromSeconds(30);
        expect(result).to.equal('00:00:30');

        result = HelpersRepository.formatDurationFromSeconds(60);
        expect(result).to.equal('00:01:00');

        result = HelpersRepository.formatDurationFromSeconds(65);
        expect(result).to.equal('00:01:05');

        result = HelpersRepository.formatDurationFromSeconds(3600);
        expect(result).to.equal('01:00:00');

        result = HelpersRepository.formatDurationFromSeconds(3665);
        expect(result).to.equal('01:01:05');
    });

    it('can format a time', function () {
        var result = HelpersRepository.formatTime('4:30pm');
        expect(result).to.equal('16:30:00');
    });
});
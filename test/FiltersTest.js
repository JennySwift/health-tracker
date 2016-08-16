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
});
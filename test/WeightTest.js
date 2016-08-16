var expect = require('chai').expect;
var Vue = require('vue');
global.store = require('../resources/assets/js/repositories/SharedRepository.js');
global.FiltersRepository = require('../resources/assets/js/repositories/FiltersRepository.js');
// require('../resources/assets/js/app.js');

describe('weight component', function () {
    var Weight;

    beforeEach(function () {
        Weight = new Vue(require('../resources/assets/js/components/WeightComponent'));

        //Check the initial data
        expect(Weight.editingWeight).to.be.false;
        expect(Weight.addingNewWeight).to.be.false;
        expect(Weight.weight).to.eql({});
        expect(Weight.newWeight).to.eql({});
    });

    it('can show the new weight fields', function () {
        Weight.showNewWeightFields();
        expect(Weight.editingWeight).to.be.false;
        expect(Weight.addingNewWeight).to.be.true;
    });

    it('can show the edit weight fields', function () {
        Weight.showEditWeightFields();
        expect(Weight.editingWeight).to.be.true;
        expect(Weight.addingNewWeight).to.be.false;
    });

    it('can round a number', function () {
        var number = Weight.$options.filters.roundNumber(10.246, 2);
        expect(number).to.equal(10.25);
    });
});
var expect = require('chai').expect;
var Vue = require('vue');
global.store = require('../resources/assets/js/repositories/SharedRepository.js');
global.FiltersRepository = require('../resources/assets/js/repositories/FiltersRepository.js');

describe('filters', function () {
    it('can show and hide the loading symbol', function () {
        expect(store.state.loading).to.be.false;
        store.showLoading();
        expect(store.state.loading).to.be.true;
        store.hideLoading();
        expect(store.state.loading).to.be.false;
    });
});
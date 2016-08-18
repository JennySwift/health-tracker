var expect = require('chai').expect;
var Vue = require('vue');
global.store = require('../resources/assets/js/repositories/SharedRepository.js');
global.FiltersRepository = require('../resources/assets/js/repositories/FiltersRepository.js');
global._ = require('underscore');

describe('filters', function () {
    it('can show and hide the loading symbol', function () {
        expect(store.state.loading).to.be.false;
        store.showLoading();
        expect(store.state.loading).to.be.true;
        store.hideLoading();
        expect(store.state.loading).to.be.false;
    });

    it('can update an item in an array', function () {
        store.state.exercises = [
            {name: 'pushup', id: 1},
            {name: 'squat', id: 2},
            {name: 'pullup', id: 3}
        ];

        var exercise = {name: 'squats', id: 2};

        // store.updateExercise(exercise);
        store.update(exercise, 'exercises');

        expect(store.state.exercises).to.eql([
            {name: 'pushup', id: 1},
            {name: 'squats', id: 2},
            {name: 'pullup', id: 3}
        ]);
    });

    it('can add an item to an array', function () {
        store.state.exercises = [
            {name: 'pushup', id: 1},
            {name: 'squat', id: 2}
        ];

        var exercise = {name: 'pullup', id: 3};

        store.add(exercise, 'exercises');

        expect(store.state.exercises).to.eql([
            {name: 'pushup', id: 1},
            {name: 'squat', id: 2},
            {name: 'pullup', id: 3}
        ]);
    });

    it('can remove an item from an array', function () {
        store.state.exercises = [
            {name: 'pushup', id: 1},
            {name: 'squat', id: 2},
            {name: 'pullup', id: 3}
        ];

        var exercise = {name: 'squat', id: 2};

        store.delete(exercise, 'exercises');

        expect(store.state.exercises).to.eql([
            {name: 'pushup', id: 1},
            {name: 'pullup', id: 3}
        ]);
    });

    it('can set a store property', function () {
        var exercises = [
            {name: 'pushup', id: 1},
            {name: 'squat', id: 2},
            {name: 'pullup', id: 3}
        ];

        store.set(exercises, 'exercises');

        expect(store.state.exercises).to.eql(exercises);
    });
});
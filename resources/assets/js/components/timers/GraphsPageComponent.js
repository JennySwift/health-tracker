var DatesRepository = require('../../repositories/DatesRepository');

module.exports = {
    template: '#graphs-page-template',
    data: function () {
        return {
            date: store.state.date,
            timers: store.state.timers
        };
    },
    components: {},
    methods: {
        
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {

    }
};


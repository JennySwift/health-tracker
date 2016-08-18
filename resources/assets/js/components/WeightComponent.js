var Vue = require('vue');

module.exports = {
    template: '#weight-template',
    data: function () {
        return {
            weight: {},
            editingWeight: false,
            addingNewWeight: false,
            newWeight: {},
            shared: store.state
        };
    },
    computed: {
        date: function () {
          return this.shared.date;
        }
    },
    components: {},
    filters: {
        roundNumber: function (number, howManyDecimals) {
            return FiltersRepository.roundNumber(number, howManyDecimals);
        }
    },
    methods: {

        /**
         *
         */
        showNewWeightOrEditWeightFields: function () {
            if (this.weight.id) {
                this.showEditWeightFields();
            }
            else {
                this.showNewWeightFields();
            }
        },
        
        /**
        *
        */
        insertWeight: function () {
            var data = {
                date: this.date.sql,
                weight: this.newWeight.weight
            };

            HelpersRepository.post('/api/weights', data, 'Weight entered', function (response) {
                this.weight = response.data;
                this.addingNewWeight = false;
            }.bind(this));
        },

        /**
         *
         */
        updateWeight: function () {
            var data = {
                weight: this.weight.weight
            };

            HelpersRepository.put('/api/weights/' + this.weight.id, data, 'Weight entered', function (response) {
                this.weight = response.data;
                this.editingWeight = false;
            }.bind(this));
        },

        /**
         *
         */
        showNewWeightFields: function () {
            this.addingNewWeight = true;
            this.editingWeight = false;
        },

        /**
         *
         */
        showEditWeightFields: function () {
            this.editingWeight = true;
            this.addingNewWeight = false;
            setTimeout(function () {
                $("#weight").focus();
            }, 500);
        },

        /**
        * @param weight
        */
        getWeightForTheDay: function (weight) {
            HelpersRepository.get('api/weights/' + this.date.sql, function (response) {
                this.weight = response.data;
            }.bind(this));
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('get-entries', function (event) {
                that.getWeightForTheDay();
            });
            $(document).on('date-changed', function (event) {
                that.getWeightForTheDay();
            });
        },
    },
    props: [

    ],
    ready: function () {
        $("#weight").val("");
        this.getWeightForTheDay();
        this.listen();
    }
};

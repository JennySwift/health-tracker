var RecipeTags = Vue.component('recipe-tags', {
    template: '#recipe-tags-template',
    data: function () {
        return {
            newTag: {}
        };
    },
    components: {},

    methods: {

        /**
        *
        */
        insertTag: function () {
            store.showLoading();
            var data = {
                name: this.newTag.name
            };

            this.$http.post('/api/recipeTags', data).then(function (response) {
                this.tags.push(response.data);
                $.event.trigger('provide-feedback', ['Tag created', 'success']);
                store.hideLoading();
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
        *
        */
        deleteTag: function (tag) {
            if (confirm("Are you sure?")) {
                store.showLoading();
                this.$http.delete('/api/recipeTags/' + tag.id).then(function (response) {
                    this.tags = _.without(this.tags, tag);
                    $.event.trigger('provide-feedback', ['Tag deleted', 'success']);
                    //this.$broadcast('provide-feedback', 'Tag deleted', 'success');
                    store.hideLoading();
                }, function (response) {
                    HelpersRepository.handleResponseError(response);
                });
            }
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            $.event.trigger('response-error', [response]);
            this.showLoading = false;
        }
    },
    props: [
        'tags',
        'recipesTagFilter'
    ],
    ready: function () {

    }
});

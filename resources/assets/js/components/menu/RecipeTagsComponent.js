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
            $.event.trigger('show-loading');
            var data = {
                name: this.newTag.name
            };

            this.$http.post('/api/recipeTags', data, function (response) {
                this.tags.push(response.data);
                $.event.trigger('provide-feedback', ['Tag created', 'success']);
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
        *
        */
        deleteTag: function (tag) {
            if (confirm("Are you sure?")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/recipeTags/' + tag.id, function (response) {
                    this.tags = _.without(this.tags, tag);
                    $.event.trigger('provide-feedback', ['Tag deleted', 'success']);
                    //this.$broadcast('provide-feedback', 'Tag deleted', 'success');
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    this.handleResponseError(response);
                });
            }
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            this.$broadcast('response-error', response);
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

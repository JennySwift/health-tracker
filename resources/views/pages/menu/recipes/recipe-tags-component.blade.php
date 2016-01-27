<script id="recipe-tags-template" type="x-template">

    <div>

        <div class="form-group">
            <label for="new-tag-name">Name</label>
            <input
                v-model="newTag.name"
                v-on:keyup.13="insertTag()"
                type="text"
                id="new-tag-name"
                name="new-tag-name"
                placeholder="name"
                class="form-control"
            >
        </div>

        <div class="form-group">
            <label for="tagsFilter">Filter</label>
            <input
                v-model="tagsFilter"
                type="text"
                id="tagsFilter"
                name="tagsFilter"
                placeholder="Filter tags"
                class="form-control"
            >
        </div>

        <hr>

        <div>

            <table class="table table-bordered">
                <tr>
                    <th>name</th>
                    <th class="tooltipster" title="check to filter recipes by the tag">filter</th>
                    <th></th>
                </tr>
                <tr v-for="tag in tags | filterBy tagsFilter in 'name' | orderBy: 'name'">
                    <td>@{{ tag.name }}</td>

                    <td>
                        <input
                                v-model="recipesTagFilter"
                                :value="tag.id"
                                type="checkbox">

                    </td>

                    <td><i v-on:click="deleteTag(tag)" class="delete-item fa fa-times"></i></td>
                </tr>
            </table>

        </div>
    </div>

</script>
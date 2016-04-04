<h3 class="center">Tags</h3>

<div class="flex">

    <ul class="list-group">
        <li v-for="tag in tags" class="list-group-item">
            <span>@{{ tag.name }}</span>
            <input
                v-model="selectedRecipe.tag_ids"
                :value="tag.id"
                v-on:click="selectedRecipe.notification = 'Tags need saving.'"
                type="checkbox">
        </li>
    </ul>

    <div>@{{ selectedRecipe.notification }}</div>

</div>
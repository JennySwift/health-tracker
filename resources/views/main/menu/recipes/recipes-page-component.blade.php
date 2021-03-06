<script id="recipes-page-template" type="x-template">

    <div class="container">

        <recipe-popup
            :tags="tags"
            :recipes.sync="recipes"
        >
        </recipe-popup>

        <div id="foods">

            <new-quick-recipe
                :recipes.sync="recipes"
            >
            </new-quick-recipe>

            <hr>

            <div class="flex">

                <recipes
                    :tags="tags"
                    :recipes-tag-filter="recipesTagFilter"
                    :recipes.sync="recipes"
                >
                </recipes>
                <recipe-tags
                    :tags.sync="tags"
                    :recipes-tag-filter="recipesTagFilter"
                >
                </recipe-tags>

            </div>
        </div>

    </div>

</script>
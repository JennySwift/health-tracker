<script id="recipes-page-template" type="x-template">

    <div class="container">

        <feedback></feedback>
        <loading></loading>

        {{--@include('pages.menu.recipes.popups.recipe-popup.recipe')--}}

        <div id="foods">

            {{--<new-quick-recipe></new-quick-recipe>--}}

            <hr>

            <div class="flex">

                <recipes
                    :tags="tags"
                    :recipes-tag-filter="recipesTagFilter"
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
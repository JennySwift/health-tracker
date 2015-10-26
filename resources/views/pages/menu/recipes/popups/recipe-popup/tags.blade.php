<h3 class="center">Tags</h3>

<div class="flex">

    <ul class="list-group">
        <li ng-repeat="tag in recipe_tags" class="list-group-item">
            <span>[[tag.name]]</span>
            <input checklist-model="recipe_popup.tags" checklist-value="tag.id" ng-click="recipe_popup.notification = 'Tags need saving.'" type="checkbox">
        </li>
    </ul>

    <button ng-click="insertTagsIntoRecipe()" class="margin-bottom">save tags</button>
    <div>[[recipe_popup.notification]]</div>

</div>
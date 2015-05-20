<!-- ==========================recipe quick entry========================== -->

<div class="margin-bottom">
	<ul>
		<li>start a new line for each item in your recipe</li>
		<li>there must be a space after the comma</li>
		<li>indicate the steps for your recipe with either 'method,' 'preparation,' or 'directions.' Colons are acceptable after these words.</li>
		<li>foods in your recipe that you have not entered in your foods will be added for you</li>
		<li>if a similar food is found, you will be prompted before it is added, so you don't end up with, for example, both 'apple' and 'apples' in your foods</li>
	</ul>
	<p>example format:</p>
	<p>1 large apple, red</p>
	<p>1 cup tomatoes</p>
	<div class="btn-toolbar" data-role="editor-toolbar" data-target="#wysiwyg">
		<a data-edit="bold" class="fa fa-bold"></a>
		<a data-edit="italic" class="fa fa-italic"></a>
		<a data-edit="underline" class="fa fa-underline"></a>
	</div>

	<div id="quick-recipe" class="wysiwyg"></div>

	<button ng-click="quickRecipe()"class="btn">go</button>

	<div>
		<div ng-repeat="error in errors.quick_recipe">[[error]]</div>
	</div>

	<!-- <div>
		<div ng-repeat="item in quick_recipe.contents">[[item]]</div>
	</div>

	<div>
		<div ng-repeat="step in quick_recipe.steps">[[step]]</div>
	</div> -->
</div>
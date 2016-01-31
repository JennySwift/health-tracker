<div id="quick-recipe-div" class="margin-bottom">

	@include('pages.menu.recipes.quick-recipe-help')

	<!-- wysiwyg -->

	<div>
		<div class="wysiwyg-toolbar" data-role="editor-toolbar" data-target="#wysiwyg">
			<a data-edit="bold" class="fa fa-bold"></a>
			<a data-edit="italic" class="fa fa-italic"></a>
			<a data-edit="underline" class="fa fa-underline"></a>
		</div>
		
		<div id="quick-recipe" class="wysiwyg margin-bottom"></div>
		
		<button v-on:click="respondToEnterRecipeBtnClick()" class="btn btn-success">Enter recipe</button>
	</div>

	<!-- errors -->

	<div id="quick-recipe-errors">
		<div v-for="error in errors">@{{ error }}</div>
	</div>

</div>
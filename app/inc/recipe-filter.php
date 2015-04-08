<?php

use App\Recipe;

function filterRecipes ($name, $tag_ids) {
	$recipes = Recipe::where('recipes.user_id', Auth::user()->id);

	//filter by name
	if ($name !== '') {
		$name = '%' . $name . '%';

		$recipes = $recipes
			->where('name', 'LIKE', $name);
	}
	
	//filter by tags
	if (count($tag_ids) > 0) {
		foreach ($tag_ids as $tag_id) {
		    $recipes = $recipes->whereHas('recipeTags', function ($q) use ($tag_id) {
		        $q->where('recipe_tags.id', $tag_id); 
		    });
		}
	}
	
	$recipes = $recipes
		->select('id', 'name')
		->orderBy('name', 'asc')
		->get();

	$array = array();
	foreach ($recipes as $recipe) {
		$recipe_id = $recipe->id;
		$recipe_name = $recipe->name;
		$tags = getTagsForRecipe($recipe_id);
		
		$array[] = array(
			"id" => $recipe_id,
			"name" => $recipe_name,
			"tags" => $tags
		);
	}

	return $array;
}

?>
<?php

use App\Recipe;

function filterRecipes ($typing) {
	$typing = '%' . $typing . '%';
	
	$rows = DB::table('recipes')
		->where('name', 'LIKE', $typing)
		->where('user_id', Auth::user()->id)
		->select('id', 'name')
		->orderBy('name', 'asc')
		->get();
	

	$recipes = array();
	foreach ($rows as $row) {
		$recipe_id = $row->id;
		$recipe_name = $row->name;
		$tags = getTagsForRecipe($recipe_id);
		
		$recipes[] = array(
			"id" => $recipe_id,
			"name" => $recipe_name,
			"tags" => $tags
		);
	}
	return $recipes;
}

function filterRecipesbyTags ($tag_ids) {
	// $recipe_ids = Recipe::where('recipes.user_id', Auth::user()->id);

	$recipe_ids = Recipe::has('recipeTags', '>=', 2)->get();

	// foreach ($tag_ids as $tag_id) {
	//     $recipe_ids = $recipe_ids->whereHas('recipe_tag', function ($q) use ($tag_id) {
	//         $q->where('tags.id', $tag_id); 
	//     });
	// }

	// $recipe_ids = $recipe_ids
	// 	->get();

	return $recipe_ids;
}



?>
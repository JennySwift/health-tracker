<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model {

	public function recipeTags () {
		return $this->belongsToMany('App\Recipe_tag', 'recipe_tag', 'recipe_id', 'tag_id');
	}
}

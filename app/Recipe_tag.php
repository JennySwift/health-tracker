<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipe_tag extends Model {

	public function recipes () {
		return $this->belongsToMany('App\Recipe', 'recipe_tag', 'recipe_id', 'tag_id');
	}
}

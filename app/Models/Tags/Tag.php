<?php namespace App\Models\Tags;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Models\Relationships\OwnedByUser;
use Auth;

/**
 * Class Tag
 * @package App\Models\Tags
 */
class Tag extends Model {

	use OwnedByUser;

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
	{
		return $this->belongsTo('App\User');
	}

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function recipes()
	{
		return $this->belongsToMany('App\Models\Foods\Recipe', 'recipe_tag', 'tag_id', 'taggable_id');
	}

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function exercises()
	{
		return $this->belongsToMany('App\Models\Exercises\Exercise', 'taggables', 'tag_id', 'taggable_id');
	}

    /**
     *
     * @return mixed
     */
    public static function getExerciseTags()
	{
		return static::forCurrentUser()->where('for', 'exercise')->get();
	}

	/**
	 * Get all the user's tags for recipes
	 * @return [type] [description]
	 */
	public static function getRecipeTags()
	{
		return static::forCurrentUser()->where('for', 'recipe')->get();
	}

    /**
     * Insert a new tag (of type recipe) into the tags table
     * @param $name
     */
	public static function insertRecipeTag($name)
	{
		static
			::insert([
				'name' => $name,
				'for' => 'recipe',
				'user_id' => Auth::user()->id
			]);
	}

    /**
     * Delete a recipe tag from the tags table
     * @param $id
     */
	public static function deleteRecipeTag($id)
	{
		static
			::where('id', $id)
			->delete();
	}

}

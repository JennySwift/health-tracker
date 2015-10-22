<?php namespace App\Http\Controllers\Tags;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Exercises\Exercise;
use App\Models\Tags\Tag;
use Auth;
use DB;
use Debugbar;
use Illuminate\Http\Request;

/**
 * Class TagsController
 * @package App\Http\Controllers\Tags
 */
class TagsController extends Controller
{

    /**
     * Deletes all tags from the exercise then adds
     * the correct tags to the exercise
     * Todo: make sure it belongs to user
     * @param Request $request
     * @return mixed
     */
    public function insertTagsInExercise(Request $request)
    {
        $exercise = Exercise::find($request->get('exercise_id'));
        $tag_ids = $request->get('tags');

        //delete tags from the exercise
        //I wasn't sure if detach would work for this since I want to delete all tags that belong to the exercise.
        DB::table('taggables')->where('taggable_id', $exercise->id)->delete();

        //add tags to the exercise
        foreach ($tag_ids as $tag_id) {
            //add tag to the exercise
            $exercise->tags()->attach($tag_id, ['taggable_type' => 'exercise']);
        }

        return Exercise::getExercises();
    }

    /**
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tag = new Tag([
            'name' => $request->get('name'),
            'for' => $request->get('for')
        ]);

        $tag->user()->associate(Auth::user());
        $tag->save();

        return $this->responseCreated($tag);
	}

    /**
     *
     * @param Tag $tag
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();
        return $this->responseNoContent();
    }
}
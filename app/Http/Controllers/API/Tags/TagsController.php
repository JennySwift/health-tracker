<?php namespace App\Http\Controllers\API\Tags;

use App\Http\Controllers\Controller;
use App\Http\Requests;
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
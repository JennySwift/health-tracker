<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App;

abstract class Controller extends BaseController {

	use DispatchesCommands, ValidatesRequests;

	//So that I don't have to remember to uncomment line 18 of kernel.php before pushing

	public function activateCsrfMiddleware()
	{
		if (App::environment() != 'local') {
			$this->middleware('csrf');
		}	
	}

}

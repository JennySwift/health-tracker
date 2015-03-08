<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class DeleteController extends Controller {

	//
	public function something () {
		include(app_path() . '/inc/functions.php');
		
		// $table = $_GET['table'];
		$table = json_decode(file_get_contents('php://input'), true)["table"];
		// $id = $_GET['id'];
		$id = json_decode(file_get_contents('php://input'), true)["id"];
		
		try {
		
		    $sql = "DELETE FROM $table WHERE id = $id;";
		
		    $sql_result = $db->query($sql);
		
		    //=========================response=========================
		
		    $variables = get_defined_vars();
		
		    $response = array(
		        "variables" => $variables
		    );
		
		    $json = json_encode($response);
		    echo $json;
		}
		catch (Exception $e) {
		    $variables = get_defined_vars(); 
		    $json = json_encode($variables);
		    echo $json;
		    exit;
		}
	}

}

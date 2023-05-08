<?php declare(strict_types=1);
define('ROOT','/var/www');

//Get the request method.
$requestType = $_SERVER['REQUEST_METHOD'];

//Switch statement
switch ($requestType) {
    case 'POST':
      handle_post_request();  
      break;
    case 'GET':
      handle_get_request();  
      break;
    case 'DELETE':
      handle_delete_request();  
      break;
    default:
      //request type that isn't being handled.
      break;
}

function handle_get_request() {
	$file = ROOT . '/data/input.json';
	$data = file_get_contents($file);

	header("X-Robots-Tag: noindex, nofollow", true);
	header("Content-Type: application/json");
	echo $data;
	exit();
}

function handle_post_request() {
	$file = ROOT . '/data/input.json';

  $holidays = $_POST['holidays'];
  foreach($holidays as $key => $value) {
    if ($value['name'] == '') unset($_POST['holidays'][$key]);
  }

  try {
    file_put_contents($file,json_encode($_POST));
  } catch (Exception $e) {
    print "something went wrong, caught ya";
    exit();
  }
  header( "refresh:3;url=/admin" );
  echo "Changes Saved. Returning in 3 seconds.";
}

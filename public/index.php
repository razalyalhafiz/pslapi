<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../src/config/db.php';

$app = new \Slim\App;

// Get all phone types
$app->get('/api/phone_types', function(Request $request, Response $response) {
  $sql = "SELECT * FROM phone_types";
  try {
    // Get DB Object
    $db = new db();
    // Connect
    $db = $db->connect();
    // Fetch PDO Object
    $stmt = $db->query($sql);
    $contacts = $stmt->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($contacts);
  } catch (\Exception $e) {
    echo '{"error": {"text": ' . $e->getMessage(). ' }}';
  } finally {
    $db = null;
  }
});

// Contact Routes
require '../src/routes/contacts.php';

$app->run();

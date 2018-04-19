<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Enable CORS
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

// Get all contacts
$app->get('/api/contacts', function(Request $request, Response $response) {
  $sql = "SELECT * FROM contacts";
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

// Get contact
$app->get('/api/contacts/{id}', function(Request $request, Response $response) {
  $id = $request->getAttribute('id');
  $sql = "SELECT * FROM contacts WHERE id = $id";
  try {
    // Get DB Object
    $db = new db();
    // Connect
    $db = $db->connect();
    // Fetch PDO Object
    $stmt = $db->query($sql);
    $contact = $stmt->fetch(PDO::FETCH_OBJ);
    echo json_encode($contact);
  } catch (\Exception $e) {
    echo '{"error": {"text": ' . $e->getMessage(). ' }}';
  } finally {
    $db = null;
  }
});

// Add contact
$app->post('/api/contacts/add', function(Request $request, Response $response) {
  $last_name = $request->getParam('last_name');
  $first_name = $request->getParam('first_name');
  $phone_type = $request->getParam('phone_type');
  $number = $request->getParam('number');

  $sql = "INSERT INTO contacts (last_name, first_name, phone_type, number) VALUES
  (:last_name, :first_name, :phone_type, :number)";
  try {
    // Get DB Object
    $db = new db();
    // Connect
    $db = $db->connect();
    // Prepare statement
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':phone_type', $phone_type);
    $stmt->bindParam(':number', $number);
    // Execute statement
    $stmt->execute();
    echo '{"notice": {"text: "Contact added."}}';
  } catch (\Exception $e) {
    echo '{"error": {"text": ' . $e->getMessage(). ' }}';
  } finally {
    $db = null;
  }
});

// Update contact
$app->put('/api/contacts/update/{id}', function(Request $request, Response $response) {
  $id = $request->getAttribute('id');
  $last_name = $request->getParam('last_name');
  $first_name = $request->getParam('first_name');
  $phone_type = $request->getParam('phone_type');
  $number = $request->getParam('number');

  $sql = "UPDATE contacts SET
            last_name = :last_name,
            first_name = :first_name,
            phone_type = :phone_type,
            number = :number
          WHERE id = $id";

  try {
    // Get DB Object
    $db = new db();
    // Connect
    $db = $db->connect();
    // Prepare statement
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':phone_type', $phone_type);
    $stmt->bindParam(':number', $number);
    // Execute statement
    $stmt->execute();
    echo '{"notice": {"text: "Contact updated."}}';
  } catch (\Exception $e) {
    echo '{"error": {"text": ' . $e->getMessage(). ' }}';
  } finally {
    $db = null;
  }
});

// Delete contact
$app->delete('/api/contacts/delete/{id}', function(Request $request, Response $response) {
  $id = $request->getAttribute('id');
  $sql = "DELETE FROM contacts WHERE id = $id";
  try {
    // Get DB Object
    $db = new db();
    // Connect
    $db = $db->connect();
    // Prepare & execute
    $stmt = $db->prepare($sql);
    $stmt->execute();
    echo '{"notice": {"text: "Contact deleted."}}';
  } catch (\Exception $e) {
    echo '{"error": {"text": ' . $e->getMessage(). ' }}';
  } finally {
    $db = null;
  }
});

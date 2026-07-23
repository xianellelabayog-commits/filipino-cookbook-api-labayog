<?php


require __DIR__ . '/../vendor/autoload.php';


use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


$app = AppFactory::create();


// Slim 4 requires routing middleware before body parsing and error handling.
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();

// Detailed error output while developing (set the first "true" to false in production)
$errorMiddleware = $app->addErrorMiddleware(true, true, true);


/**
 * Force EVERY error (404 route not found, method not allowed, foreign key
 * violations, PHP exceptions, etc.) to come back as JSON instead of Slim's
 * default HTML error page.
 */
$errorMiddleware->setDefaultErrorHandler(function (
    Request $request,
    Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails
) use ($app) {
    $statusCode = 500;


    if ($exception instanceof \Slim\Exception\HttpNotFoundException) {
        $statusCode = 404;
        $message = 'Endpoint not found.';
    } elseif ($exception instanceof \Slim\Exception\HttpMethodNotAllowedException) {
        $statusCode = 405;
        $message = 'HTTP method not allowed for this endpoint.';
    } elseif ($exception instanceof PDOException) {
        $statusCode = 400;
        $message = 'Database error: ' . $exception->getMessage();
    } else {
        $message = $exception->getMessage() ?: 'An unexpected error occurred.';
    }


    $response = $app->getResponseFactory()->createResponse();
    $response->getBody()->write(json_encode([
        'status'  => 'error',
        'message' => $message,
    ]));


    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus($statusCode);
});


/**
 * A. DATABASE CONNECTION (PDO)
 * Adjust host / db / user / pass to match your MySQL setup.
 * Import `database/filipino_foods_relational.sql` into MySQL first,
 * then use the imported database name below.
 */
function env(string $key, string $default = ''): string
{
    $value = getenv($key);
    if ($value === false) {
        $value = $_SERVER[$key] ?? $_ENV[$key] ?? $default;
    }
    return $value;
}

function getDB(): PDO
{
    $host    = env('DB_HOST', 'localhost');
    $db      = env('DB_NAME', 'filipino_cookbook_api');
    $user    = env('DB_USER', 'root');
    $pass    = env('DB_PASS', '');
    $charset = env('DB_CHARSET', 'utf8mb4');


    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];


    return new PDO($dsn, $user, $pass, $options);
}


/**
 * Small helper so every route returns JSON the same way.
 */
function jsonResponse(Response $response, $data, int $status = 200): Response
{
    $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus($status);
}


/**
 * Validates Bearer token from Authorization header
 */
function validateToken(Request $request): ?string
{
    $authHeader = $request->getHeaderLine('Authorization');
    
    if (empty($authHeader)) {
        return 'Unauthorized access. Valid API token is required.';
    }
    
    // Extract token from "Bearer <token>" format
    if (strpos($authHeader, 'Bearer ') !== 0) {
        return 'Invalid token';
    }
    
    $token = substr($authHeader, 7); // Remove "Bearer " prefix
    
    if (empty($token)) {
        return 'Invalid token';
    }
    
    // Check if token matches the required token
    $requiredToken = 'dmmmsu-cookbook-token-2026';
    
    if ($token !== $requiredToken) {
        return 'Invalid token';
    }
    
    return null; // Token is valid
}

/**
 * Helper to attach the ingredients array to a single food row.
 */
function attachIngredients(PDO $db, array $food): array
{
    $stmt = $db->prepare(
        "SELECT i.ingredient_name
         FROM ingredients i
         JOIN food_ingredients fi ON i.ingredient_id = fi.ingredient_id
         WHERE fi.food_id = ?
         ORDER BY i.ingredient_name"
    );
    $stmt->execute([$food['food_id']]);
    $food['ingredients'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
    return $food;
}


/* ------------------------------------------------------------------ */
/* 1. PUBLIC WELCOME ROUTE - GET /                                     */
/* ------------------------------------------------------------------ */
$app->get('/', function (Request $request, Response $response) {
    return jsonResponse($response, [
        'message' => 'Welcome to the Secured Filipino Cookbook API',
        'note'    => 'Use a valid Bearer token to access /api endpoints.',
    ]);
});


/* ------------------------------------------------------------------ */
/* 2. GET ALL FOODS - GET /api/foods                                    */
/* ------------------------------------------------------------------ */
$app->get('/api/foods', function (Request $request, Response $response) {
    // Validate token
    $tokenError = validateToken($request);
    if ($tokenError) {
        return jsonResponse($response, [
            'status'  => 'error',
            'message' => $tokenError,
        ], 401);
    }

    $db = getDB();

    $stmt = $db->query(
        "SELECT f.food_id, f.food_name, c.category_name, o.origin_name, f.instructions
         FROM foods f
         JOIN categories c ON f.category_id = c.category_id
         JOIN origins o ON f.origin_id = o.origin_id
         ORDER BY f.food_id"
    );
    $foods = $stmt->fetchAll();


    foreach ($foods as $key => $food) {
        $foods[$key] = attachIngredients($db, $food);
    }


    return jsonResponse($response, $foods);
});


/* ------------------------------------------------------------------ */
/* 3. SEARCH FOOD BY NAME - GET /api/foods/search/{name}                */
/* ------------------------------------------------------------------ */
$app->get('/api/foods/search/{name}', function (Request $request, Response $response, array $args) {
    // Validate token
    $tokenError = validateToken($request);
    if ($tokenError) {
        return jsonResponse($response, [
            'status'  => 'error',
            'message' => $tokenError,
        ], 401);
    }

    $db = getDB();
    $name = trim($args['name']);


    $stmt = $db->prepare(
        "SELECT f.food_id, f.food_name, c.category_name, o.origin_name, f.instructions
         FROM foods f
         JOIN categories c ON f.category_id = c.category_id
         JOIN origins o ON f.origin_id = o.origin_id
         WHERE f.food_name LIKE ?
         ORDER BY f.food_id"
    );
    $stmt->execute(['%' . $name . '%']);
    $foods = $stmt->fetchAll();


    if (empty($foods)) {
        return jsonResponse($response, [
            'status'  => 'error',
            'message' => "No food found matching '$name'.",
        ], 404);
    }


    foreach ($foods as $key => $food) {
        $foods[$key] = attachIngredients($db, $food);
    }


    return jsonResponse($response, $foods);
});


/* ------------------------------------------------------------------ */
/* 4. GET FOOD BY ID - GET /api/foods/{id}                              */
/* ------------------------------------------------------------------ */
$app->get('/api/foods/{id}', function (Request $request, Response $response, array $args) {
    // Validate token
    $tokenError = validateToken($request);
    if ($tokenError) {
        return jsonResponse($response, [
            'status'  => 'error',
            'message' => $tokenError,
        ], 401);
    }

    $db = getDB();
    $id = $args['id'];


    $stmt = $db->prepare(
        "SELECT f.food_id, f.food_name, c.category_name, o.origin_name, f.instructions
         FROM foods f
         JOIN categories c ON f.category_id = c.category_id
         JOIN origins o ON f.origin_id = o.origin_id
         WHERE f.food_id = ?"
    );
    $stmt->execute([$id]);
    $food = $stmt->fetch();


    if (!$food) {
        return jsonResponse($response, [
            'status'  => 'error',
            'message' => 'Food not found',
        ], 404);
    }


    $food = attachIngredients($db, $food);


    return jsonResponse($response, $food);
});


/* ------------------------------------------------------------------ */
/* 5. GET ALL CATEGORIES - GET /api/categories                         */
/* ------------------------------------------------------------------ */
$app->get('/api/categories', function (Request $request, Response $response) {
    // Validate token
    $tokenError = validateToken($request);
    if ($tokenError) {
        return jsonResponse($response, [
            'status'  => 'error',
            'message' => $tokenError,
        ], 401);
    }

    $db = getDB();
    $stmt = $db->query("SELECT * FROM categories ORDER BY category_id");
    return jsonResponse($response, $stmt->fetchAll());
});


/* ------------------------------------------------------------------ */
/* 6. GET ALL INGREDIENTS - GET /api/ingredients                        */
/* ------------------------------------------------------------------ */
$app->get('/api/ingredients', function (Request $request, Response $response) {
    // Validate token
    $tokenError = validateToken($request);
    if ($tokenError) {
        return jsonResponse($response, [
            'status'  => 'error',
            'message' => $tokenError,
        ], 401);
    }

    $db = getDB();
    $stmt = $db->query("SELECT * FROM ingredients ORDER BY ingredient_id");
    return jsonResponse($response, $stmt->fetchAll());
});


/* ------------------------------------------------------------------ */
/* 7. ADD NEW FOOD - POST /api/foods                                    */
/* ------------------------------------------------------------------ */
$app->post('/api/foods', function (Request $request, Response $response) {
    // Validate token
    $tokenError = validateToken($request);
    if ($tokenError) {
        return jsonResponse($response, [
            'status'  => 'error',
            'message' => $tokenError,
        ], 401);
    }

    $db   = getDB();
    $data = $request->getParsedBody();


    // Handles: missing body, wrong Content-Type header, or malformed JSON
    // (all of which make getParsedBody() come back as null).
    if (!is_array($data)) {
        return jsonResponse($response, [
            'status'  => 'error',
            'message' => 'Request body must be valid JSON. Did you set the "Content-Type: application/json" header?',
        ], 400);
    }


    // Basic presence check on required fields
    $required = ['food_name', 'category_id', 'origin_id', 'instructions'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            return jsonResponse($response, [
                'status'  => 'error',
                'message' => "Missing required field: $field",
            ], 400);
        }
    }


    // Reject a food_name that already exists (case-insensitive).
    $dupStmt = $db->prepare("SELECT food_id FROM foods WHERE LOWER(food_name) = LOWER(?)");
    $dupStmt->execute([$data['food_name']]);
    if ($dupStmt->fetch()) {
        return jsonResponse($response, [
            'status'  => 'error',
            'message' => "A food named '{$data['food_name']}' already exists.",
        ], 409); // 409 Conflict
    }


    // Confirm category_id actually exists before we try to insert.
    $catStmt = $db->prepare("SELECT category_id FROM categories WHERE category_id = ?");
    $catStmt->execute([$data['category_id']]);
    if (!$catStmt->fetch()) {
        return jsonResponse($response, [
            'status'  => 'error',
            'message' => "category_id {$data['category_id']} does not exist.",
        ], 400);
    }


    // Confirm origin_id actually exists before we try to insert.
    $originStmt = $db->prepare("SELECT origin_id FROM origins WHERE origin_id = ?");
    $originStmt->execute([$data['origin_id']]);
    if (!$originStmt->fetch()) {
        return jsonResponse($response, [
            'status'  => 'error',
            'message' => "origin_id {$data['origin_id']} does not exist.",
        ], 400);
    }


    // Confirm every ingredient_id in the list actually exists.
    $ingredientIds = $data['ingredient_ids'] ?? [];
    if (!is_array($ingredientIds)) {
        return jsonResponse($response, [
            'status'  => 'error',
            'message' => 'ingredient_ids must be an array of ingredient id numbers.',
        ], 400);
    }
    foreach ($ingredientIds as $ingredientId) {
        $ingStmt = $db->prepare("SELECT ingredient_id FROM ingredients WHERE ingredient_id = ?");
        $ingStmt->execute([$ingredientId]);
        if (!$ingStmt->fetch()) {
            return jsonResponse($response, [
                'status'  => 'error',
                'message' => "ingredient_id $ingredientId does not exist.",
            ], 400);
        }
    }


    try {
        $db->beginTransaction();


        // food_id is a plain INT primary key (not auto-increment in the
        // provided schema), so we work out the next id ourselves.
        $maxIdStmt = $db->query("SELECT MAX(food_id) AS max_id FROM foods");
        $newId = (int) $maxIdStmt->fetch()['max_id'] + 1;


        $insert = $db->prepare(
            "INSERT INTO foods (food_id, food_name, category_id, origin_id, instructions)
             VALUES (?, ?, ?, ?, ?)"
        );
        $insert->execute([
            $newId,
            $data['food_name'],
            $data['category_id'],
            $data['origin_id'],
            $data['instructions'],
        ]);


        if (!empty($ingredientIds)) {
            $ingInsert = $db->prepare(
                "INSERT INTO food_ingredients (food_id, ingredient_id) VALUES (?, ?)"
            );
            foreach ($ingredientIds as $ingredientId) {
                $ingInsert->execute([$newId, $ingredientId]);
            }
        }


        $db->commit();
    } catch (PDOException $e) {
        $db->rollBack();
        return jsonResponse($response, [
            'status'  => 'error',
            'message' => 'Could not add food: ' . $e->getMessage(),
        ], 400);
    }


    return jsonResponse($response, [
        'status'  => 'success',
        'message' => 'Food added successfully.',
    ], 201);
});


/* ------------------------------------------------------------------ */
/* 8. UPDATE FOOD - PUT /api/foods/{id}                                */
/* ------------------------------------------------------------------ */
$app->put('/api/foods/{id}', function (Request $request, Response $response, array $args) {
    // Validate token
    $tokenError = validateToken($request);
    if ($tokenError) {
        return jsonResponse($response, [
            'status'  => 'error',
            'message' => $tokenError,
        ], 401);
    }

    $db   = getDB();
    $id   = $args['id'];
    $data = $request->getParsedBody();

    if (!is_array($data)) {
        return jsonResponse($response, [
            'status'  => 'error',
            'message' => 'Request body must be valid JSON. Did you set the "Content-Type: application/json" header?',
        ], 400);
    }

    $stmt = $db->prepare('SELECT * FROM foods WHERE food_id = ?');
    $stmt->execute([$id]);
    $food = $stmt->fetch();

    if (!$food) {
        return jsonResponse($response, [
            'status'  => 'error',
            'message' => 'Food not found.',
        ], 404);
    }

    $allowed = ['food_name', 'category_id', 'origin_id', 'instructions', 'ingredient_ids'];
    $updates = [];
    $params  = [];

    foreach ($allowed as $field) {
        if (array_key_exists($field, $data) && $field !== 'ingredient_ids') {
            if ($field === 'food_name' && empty($data[$field])) {
                return jsonResponse($response, [
                    'status'  => 'error',
                    'message' => 'food_name cannot be empty.',
                ], 400);
            }
            $updates[] = "$field = ?";
            $params[]  = $data[$field];
        }
    }

    if (isset($data['category_id'])) {
        $catStmt = $db->prepare('SELECT category_id FROM categories WHERE category_id = ?');
        $catStmt->execute([$data['category_id']]);
        if (!$catStmt->fetch()) {
            return jsonResponse($response, [
                'status'  => 'error',
                'message' => "category_id {$data['category_id']} does not exist.",
            ], 400);
        }
    }

    if (isset($data['origin_id'])) {
        $originStmt = $db->prepare('SELECT origin_id FROM origins WHERE origin_id = ?');
        $originStmt->execute([$data['origin_id']]);
        if (!$originStmt->fetch()) {
            return jsonResponse($response, [
                'status'  => 'error',
                'message' => "origin_id {$data['origin_id']} does not exist.",
            ], 400);
        }
    }

    if (isset($data['food_name'])) {
        $dupStmt = $db->prepare('SELECT food_id FROM foods WHERE LOWER(food_name) = LOWER(?) AND food_id != ?');
        $dupStmt->execute([$data['food_name'], $id]);
        if ($dupStmt->fetch()) {
            return jsonResponse($response, [
                'status'  => 'error',
                'message' => "A food named '{$data['food_name']}' already exists.",
            ], 409);
        }
    }

    $ingredientIds = $data['ingredient_ids'] ?? null;
    if ($ingredientIds !== null) {
        if (!is_array($ingredientIds)) {
            return jsonResponse($response, [
                'status'  => 'error',
                'message' => 'ingredient_ids must be an array of ingredient id numbers.',
            ], 400);
        }

        foreach ($ingredientIds as $ingredientId) {
            $ingStmt = $db->prepare('SELECT ingredient_id FROM ingredients WHERE ingredient_id = ?');
            $ingStmt->execute([$ingredientId]);
            if (!$ingStmt->fetch()) {
                return jsonResponse($response, [
                    'status'  => 'error',
                    'message' => "ingredient_id $ingredientId does not exist.",
                ], 400);
            }
        }
    }

    if (empty($updates) && $ingredientIds === null) {
        return jsonResponse($response, [
            'status'  => 'error',
            'message' => 'No fields provided to update.',
        ], 400);
    }

    try {
        $db->beginTransaction();

        if (!empty($updates)) {
            $sql = 'UPDATE foods SET ' . implode(', ', $updates) . ' WHERE food_id = ?';
            $params[] = $id;
            $updateStmt = $db->prepare($sql);
            $updateStmt->execute($params);
        }

        if ($ingredientIds !== null) {
            $deleteStmt = $db->prepare('DELETE FROM food_ingredients WHERE food_id = ?');
            $deleteStmt->execute([$id]);

            if (!empty($ingredientIds)) {
                $insertStmt = $db->prepare('INSERT INTO food_ingredients (food_id, ingredient_id) VALUES (?, ?)');
                foreach ($ingredientIds as $ingredientId) {
                    $insertStmt->execute([$id, $ingredientId]);
                }
            }
        }

        $db->commit();
    } catch (PDOException $e) {
        $db->rollBack();
        return jsonResponse($response, [
            'status'  => 'error',
            'message' => 'Could not update food: ' . $e->getMessage(),
        ], 400);
    }

    return jsonResponse($response, [
        'status'  => 'success',
        'message' => 'Food updated successfully.',
    ]);
});


/* ------------------------------------------------------------------ */
/* 9. DELETE FOOD - DELETE /api/foods/{id}                             */
/* ------------------------------------------------------------------ */
$app->delete('/api/foods/{id}', function (Request $request, Response $response, array $args) {
    // Validate token
    $tokenError = validateToken($request);
    if ($tokenError) {
        return jsonResponse($response, [
            'status'  => 'error',
            'message' => $tokenError,
        ], 401);
    }

    $db = getDB();
    $id = $args['id'];

    $stmt = $db->prepare('SELECT food_id FROM foods WHERE food_id = ?');
    $stmt->execute([$id]);
    if (!$stmt->fetch()) {
        return jsonResponse($response, [
            'status'  => 'error',
            'message' => 'Food not found.',
        ], 404);
    }

    try {
        $db->beginTransaction();
        $deleteIngredients = $db->prepare('DELETE FROM food_ingredients WHERE food_id = ?');
        $deleteIngredients->execute([$id]);

        $deleteFood = $db->prepare('DELETE FROM foods WHERE food_id = ?');
        $deleteFood->execute([$id]);
        $db->commit();
    } catch (PDOException $e) {
        $db->rollBack();
        return jsonResponse($response, [
            'status'  => 'error',
            'message' => 'Could not delete food: ' . $e->getMessage(),
        ], 400);
    }

    return jsonResponse($response, [
        'status'  => 'success',
        'message' => 'Food deleted successfully.',
    ]);
});


$app->run();

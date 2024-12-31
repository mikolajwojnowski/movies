
<?php
include('conf.php');  // Connection file 

// Get the limit, offset, sort, and search parameters from the request
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;  // Default limit is 10
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;  // Default page is 1
$offset = ($page - 1) * $limit;  // Calculate offset
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'name';  // Default sort is by name
$search = isset($_GET['search']) ? "%" . $_GET['search'] . "%" : '%';  // Search term

// Prepare the SQL query with LIMIT, OFFSET, SORT, and SEARCH
$sql = "SELECT * FROM movie WHERE title LIKE :search ORDER BY ";

if ($sort === 'name-desc') {
    $sql .= "title DESC";  // Sort by title descending
} elseif ($sort === 'rating') {
    $sql .= "rating DESC";  // Sort by rating descending
} else {
    $sql .= "title ASC";  // Default to sorting by title ascending
}

$sql .= " LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':search', $search, PDO::PARAM_STR);
$stmt->execute();

$movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return the data as JSON
echo json_encode($movies);

?>

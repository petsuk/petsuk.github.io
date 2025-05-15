<?php
include 'conn.php';


$mp_ID = isset($_GET['mpID']) ? $_GET['mpID'] : null;


$filename = 'grocery_list.csv';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');


$output = fopen('php://output', 'w');

fputcsv($output, ['Ingredient']);

if ($mp_ID) {
 
    $query = "SELECT r.ingredients as ingredients
              FROM SavedMP
              JOIN Recipes r ON SavedMP.recID = r.id
              WHERE SavedMP.mpID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $mp_ID);
} else {

    $query = "SELECT r.ingredients as ingredients
              FROM MealPlan
              JOIN Recipes r ON MealPlan.recID = r.id
              ORDER BY MealPlan.day ASC";
    $stmt = $conn->prepare($query);
}

$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $ings =  explode(';', $row['ingredients']);
    foreach ($ings as $ing){
    $ing = str_replace(',', ' ', $ing);
    
    fputcsv($output, [$ing]);
    }
}

fclose($output);
exit();
?>

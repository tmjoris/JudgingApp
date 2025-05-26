<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

include_once '../includes/db.php';

$sql = "
    SELECT users.name, COALESCE(SUM(scores.points), 0) AS total_points
    FROM users
    LEFT JOIN scores ON users.id = scores.user_id
    GROUP BY users.id
    ORDER BY total_points DESC, users.name ASC
";

$results = $conn->query($sql);

$data = [];
$rank = 1;
while ($row = $results->fetch_assoc()) {
    $row['rank'] = $rank++;
    $data[] = $row;
}

echo "data: " . json_encode($data) . "\n\n";
flush();
?>

<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

include_once '../includes/db.php';

$sql="
SELECT 
    u.username,
    COALESCE(ROUND(AVG(s.points), 2), 0) AS average_points
FROM users u
LEFT JOIN scores s ON u.username = s.user_name
GROUP BY u.username
ORDER BY average_points DESC, u.username ASC
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

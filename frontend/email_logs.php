<?php
require_once 'db.php';
$db = new MySqlDB();

$result = $db->getEmailLogs();

echo "<h2>Email Logs</h2><table border='1' cellpadding='8'>
<tr><th>Name</th><th>Email</th><th>Subject</th><th>Group ID</th><th>Sent At</th></tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
        <td>{$row['contact_name']}</td>
        <td>{$row['contact_email']}</td>
        <td>{$row['subject']}</td>
        <td>{$row['group_id']}</td>
        <td>{$row['sent_at']}</td>
    </tr>";
}
echo "</table>";
?>

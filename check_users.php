<?php
$conn = new mysqli('localhost', 'root', '', 'rimsdb');
$r = $conn->query('SELECT username, LEFT(password,30) as pw, role, is_active FROM user LIMIT 10');
while ($row = $r->fetch_assoc()) {
    echo $row['username'] . ' | ' . $row['pw'] . ' | ' . $row['role'] . ' | active=' . $row['is_active'] . "\n";
}

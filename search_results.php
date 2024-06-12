<?php
$servername = "localhost";
$username = "root"; 
$password = "";
$dbname = "sangalodatabase";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$search_term = $_POST['search'];
$search_terms = array_map('trim', explode(',', $search_term));
$sql = "SELECT * FROM राजनीतिज्ञ WHERE ";
$conditions = [];
$params = [];
foreach ($search_terms as $term) {
    $like_term = "%" . $term . "%";
    $conditions[] = "(प्रदेश LIKE ? OR जिल्ला LIKE ? OR स्थानीय_इकाई LIKE ? OR स्थिति LIKE ? OR राजनीतिक_पार्टी LIKE ? OR नाम LIKE ? OR लिङ्ग LIKE ? OR उमेर LIKE ? OR कुल_मतहरू LIKE ?)";
    $params = array_merge($params, array_fill(0, 9, $like_term));
}
$sql .= implode(" OR ", $conditions);

$stmt = $conn->prepare($sql);
$types = str_repeat('s', count($params));
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>प्रदेश</th><th>जिल्ला</th><th>स्थानीय_इकाई</th><th>स्थिति</th><th>राजनीतिक_पार्टी</th><th>नाम</th><th>लिङ्ग</th><th>उमेर</th><th>कुल_मतहरू</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['प्रदेश'] . "</td>";
        echo "<td>" . $row['जिल्ला'] . "</td>";
        echo "<td>" . $row['स्थानीय_इकाई'] . "</td>";
        echo "<td>" . $row['स्थिति'] . "</td>";
        echo "<td>" . $row['राजनीतिक_पार्टी'] . "</td>";
        echo "<td>" . $row['नाम'] . "</td>";
        echo "<td>" . $row['लिङ्ग'] . "</td>";
        echo "<td>" . $row['उमेर'] . "</td>";
        echo "<td>" . $row['कुल_मतहरू'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No results found.";
}

$stmt->close();
$conn->close();
?>

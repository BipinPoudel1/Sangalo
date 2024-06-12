<!DOCTYPE html>
<html>
<head>
    <title>Search Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        @media (max-width: 768px) {
            table, th, td {
                display: block;
            }
            th, td {
                padding: 8px;
                text-align: left;
            }
            th {
                display: none;
            }
            td {
                border: none;
                position: relative;
                padding-left: 50%;
                text-align: right;
            }
            td:before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 45%;
                padding-left: 15px;
                font-weight: bold;
                text-align: left;
            }
        }
        @media (max-width: 480px) {
            td {
                padding-left: 40%;
            }
            td:before {
                padding-left: 10px;
                width: 50%;
            }
        }
        .back-button {
            display: block;
            width: 150px;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px auto;
        }
    </style>
</head>
<body>
    <h1>Search Results</h1>

    <?php
    $servername = "localhost";
    $username = "tanjiro";
    $password = "kamado";
    $dbname = "sangalo";
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
        echo "<table>";
        echo "<tr><th>प्रदेश</th><th>जिल्ला</th><th>स्थानीय_इकाई</th><th>स्थिति</th><th>राजनीतिक_पार्टी</th><th>नाम</th><th>लिङ्ग</th><th>उमेर</th><th>कुल_मतहरू</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td data-label='प्रदेश'>" . $row['प्रदेश'] . "</td>";
            echo "<td data-label='जिल्ला'>" . $row['जिल्ला'] . "</td>";
            echo "<td data-label='स्थानीय_इकाई'>" . $row['स्थानीय_इकाई'] . "</td>";
            echo "<td data-label='स्थिति'>" . $row['स्थिति'] . "</td>";
            echo "<td data-label='राजनीतिक_पार्टी'>" . $row['राजनीतिक_पार्टी'] . "</td>";
            echo "<td data-label='नाम'>" . $row['नाम'] . "</td>";
            echo "<td data-label='लिङ्ग'>" . $row['लिङ्ग'] . "</td>";
            echo "<td data-label='उमेर'>" . $row['उमेर'] . "</td>";
            echo "<td data-label='कुल_मतहरू'>" . $row['कुल_मतहरू'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No results found.";
    }

    $stmt->close();
    $conn->close();
    ?>

    <a class="back-button" href="search.php">Back to Search</a>
</body>
</html>

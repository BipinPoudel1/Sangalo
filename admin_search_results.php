<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Search the Candidates</h1>
    <form method="post" action="admin_search_results.php">
        <input type="text" name="search" placeholder="Enter search term">
        <input type="submit" name="action" value="Search">
    </form>

    <?php
    // Database connection
    $servername = "localhost";
    $username = "root"; 
    $password = "";
    $dbname = "sangalodatabase";
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
   }

    // Initialize variable for existing candidate
    $existing_candidate = null;

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $action = $_POST['action'];

        if ($action == "Search") {
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

            // echo "SQL Query: " . $sql . "<br>"; // Debug message

            // Prepare and execute SQL query
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
              die("Error in preparing statement: " . $conn->error);
          }
          $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
            if ($stmt->execute() === false) {
                die("Error in executing statement: " . $stmt->error);
            }
            $result = $stmt->get_result();

            // Output search results
            if ($result->num_rows > 0) {
                echo "<table border='1'>";
                echo "<tr><th>प्रदेश</th><th>जिल्ला</th><th>स्थानीय_इकाई</th><th>स्थिति</th><th>राजनीतिक_पार्टी</th><th>नाम</th><th>लिङ्ग</th><th>उमेर</th><th>कुल_मतहरू</th><th>Actions</th></tr>";
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
                    echo "<td>
                            <form method='post' action='admin_search_results.php' style='display:inline;'>
                                <input type='hidden' name='action' value='Delete Candidate'>
                                <input type='hidden' name='province' value='" . $row['प्रदेश'] . "'>
                                <input type='hidden' name='district' value='" . $row['जिल्ला'] . "'>
                                <input type='hidden' name='local_unit' value='" . $row['स्थानीय_इकाई'] . "'>
                                <input type='hidden' name='status' value='" . $row['स्थिति'] . "'>
                                <input type='hidden' name='party' value='" . $row['राजनीतिक_पार्टी'] . "'>
                                <input type='hidden' name='name' value='" . $row['नाम'] . "'>
                                <input type='hidden' name='gender' value='" . $row['लिङ्ग'] . "'>
                                <input type='hidden' name='age' value='" . $row['उमेर'] . "'>
                                <input type='hidden' name='total_votes' value='" . $row['कुल_मतहरू'] . "'>
                                <input type='submit' value='Delete'>
                            </form>
                            <form method='post' action='admin_search_results.php' style='display:inline;'>
                                <input type='hidden' name='action' value='Fetch Candidate'>
                                <input type='hidden' name='province' value='" . $row['प्रदेश'] . "'>
                                <input type='hidden' name='district' value='" . $row['जिल्ला'] . "'>
                                <input type='hidden' name='local_unit' value='" . $row['स्थानीय_इकाई'] . "'>
                                <input type='hidden' name='status' value='" . $row['स्थिति'] . "'>
                                <input type='hidden' name='party' value='" . $row['राजनीतिक_पार्टी'] . "'>
                                <input type='hidden' name='name' value='" . $row['नाम'] . "'>
                                <input type='hidden' name='gender' value='" . $row['लिङ्ग'] . "'>
                                <input type='hidden' name='age' value='" . $row['उमेर'] . "'>
                                <input type='hidden' name='total_votes' value='" . $row['कुल_मतहरू'] . "'>
                                <input type='submit' value='Update'>
                            </form>
                          </td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "No results found.";
            }

            $stmt->close();
        }
    }

    $conn->close();
    ?>
</body>
</html>

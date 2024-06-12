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
    <form method="post" action="admin1.php">
        <input type="text" name="search" placeholder="Enter search term">
        <input type="submit" name="action" value="Search">
    </form>

    <?php
    

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
}

    $servername = "localhost";
    $username = "root"; 
    $password = "";
    $dbname = "sangalodatabase";
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $existing_candidate = null;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $action = $_POST['action'];

        if ($action == "search") {
            $search_term = $_POST['search'];
            $search_terms = array_map('trim', explode(',', $search_term));
            $sql = "SELECT * FROM राजनीतिज्ञ  WHERE ";
            $conditions = [];
            $params = [];
            foreach ($search_terms as $term) {
                $like_term = "%" . $term . "%";
                $conditions[] = "(प्रदेश LIKE ? OR जिल्ला LIKE ? OR स्थानीय_इकाई LIKE ? OR स्थिति LIKE ? OR राजनीतिक_पार्टी LIKE ? OR नाम LIKE ?)";
                $params = array_merge($params, array_fill(0, 6, $like_term));
            }
            $sql .= implode(" OR ", $conditions);


            $stmt = $conn->prepare($sql);
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<table border='1'>";
                echo "<tr><th>प्रदेश</th><th>जिल्ला</th><th>स्थानीय_इकाई</th><th>स्थिति</th><th>राजनीतिक_पार्टी</th><th>नाम</th><th>Actions</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['प्रदेश'] . "</td>";
                    echo "<td>" . $row['जिल्ला'] . "</td>";
                    echo "<td>" . $row['स्थानीय_इकाई'] . "</td>";
                    echo "<td>" . $row['स्थिति'] . "</td>";
                    echo "<td>" . $row['राजनीतिक_पार्टी'] . "</td>";
                    echo "<td>" . $row['नाम'] . "</td>";
                    echo "<td>
                            <form method='post' action='' style='display:inline;'>
                                <input type='hidden' name='action' value='Delete Candidate'>
                                <input type='hidden' name='province' value='" . $row['प्रदेश'] . "'>
                                <input type='hidden' name='district' value='" . $row['जिल्ला'] . "'>
                                <input type='hidden' name='local_unit' value='" . $row['स्थानीय_इकाई'] . "'>
                                <input type='hidden' name='status' value='" . $row['स्थिति'] . "'>
                                <input type='hidden' name='party' value='" . $row['राजनीतिक_पार्टी'] . "'>
                                <input type='hidden' name='name' value='" . $row['नाम'] . "'>
                                <input type='submit' value='Delete'>
                            </form>
                            <form method='post' action='' style='display:inline;'>
                                <input type='hidden' name='action' value='Fetch Candidate'>
                                <input type='hidden' name='province' value='" . $row['प्रदेश'] . "'>
                                <input type='hidden' name='district' value='" . $row['जिल्ला'] . "'>
                                <input type='hidden' name='local_unit' value='" . $row['स्थानीय_इकाई'] . "'>
                                <input type='hidden' name='status' value='" . $row['स्थिति'] . "'>
                                <input type='hidden' name='party' value='" . $row['राजनीतिक_पार्टी'] . "'>
                                <input type='hidden' name='name' value='" . $row['नाम'] . "'>
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
        } elseif ($action == "Fetch Candidate") {
            $province = $_POST['province'];
            $district = $_POST['district'];
            $local_unit = $_POST['local_unit'];
            $status = $_POST['status'];
            $party = $_POST['party'];
            $name = $_POST['name'];

            $sql = "SELECT * FROM राजनीतिज्ञ WHERE प्रदेश = ? AND जिल्ला = ? AND स्थानीय_इकाई = ? AND स्थिति = ? AND राजनीतिक_पार्टी = ? AND नाम = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssssss', $province, $district, $local_unit, $status, $party, $name);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $existing_candidate = $result->fetch_assoc();
            } else {
                echo "Candidate not found.";
            }

            $stmt->close();
        } elseif ($action == "Add Candidate") {
            $province = $_POST['province'];
            $district = $_POST['district'];
            $local_unit = $_POST['local_unit'];
            $status = $_POST['status'];
            $party = $_POST['party'];
            $name = $_POST['name'];
            $sql = "INSERT INTO राजनीतिज्ञ  (प्रदेश, जिल्ला, स्थानीय_इकाई, स्थिति, राजनीतिक_पार्टी, नाम) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssssss', $province, $district, $local_unit, $status, $party, $name);
            if ($stmt->execute()) {
                echo "New candidate added successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } elseif ($action == "Update Candidate") {
            $old_province = $_POST['old_province'];
            $old_district = $_POST['old_district'];
            $old_local_unit = $_POST['old_local_unit'];
            $old_status = $_POST['old_status'];
            $old_party = $_POST['old_party'];
            $old_name = $_POST['old_name'];

            $new_province = $_POST['new_province'];
            $new_district = $_POST['new_district'];
            $new_local_unit = $_POST['new_local_unit'];
            $new_status = $_POST['new_status'];
            $new_party = $_POST['new_party'];
            $new_name = $_POST['new_name'];

            $sql = "UPDATE राजनीतिज्ञ  SET प्रदेश = ?, जिल्ला = ?, स्थानीय_इकाई = ?, स्थिति = ?, राजनीतिक_पार्टी = ?, नाम = ? WHERE प्रदेश = ? AND जिल्ला = ? AND स्थानीय_इकाई = ? AND स्थिति = ? AND राजनीतिक_पार्टी = ? AND नाम = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssssssssssss', $new_province, $new_district, $new_local_unit, $new_status, $new_party, $new_name, $old_province, $old_district, $old_local_unit, $old_status, $old_party, $old_name);
            if ($stmt->execute()) {
                echo "Candidate updated successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } elseif ($action == "Delete Candidate") {
            $province = $_POST['province'];
            $district = $_POST['district'];
            $local_unit = $_POST['local_unit'];
            $status = $_POST['status'];
            $party = $_POST['party'];
            $name = $_POST['name'];

            $sql = "DELETE FROM राजनीतिज्ञ WHERE प्रदेश = ? AND जिल्ला = ? AND स्थानीय_इकाई = ? AND स्थिति = ? AND राजनीतिक_पार्टी = ? AND नाम = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssssss', $province, $district, $local_unit, $status, $party, $name);
            if ($stmt->execute()) {
                echo "Candidate deleted successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    }

    $conn->close();
    ?>

    <h2>Add New Candidate</h2>
    <form method="post" action="">
        <input type="hidden" name="action" value="Add Candidate">
        <label>प्रदेश:</label><input type="text" name="province" required><br>
        <label>जिल्ला:</label><input type="text" name="district" required><br>
        <label>स्थानीय इकाई:</label><input type="text" name="local_unit" required><br>
        <label>स्थिति:</label><input type="text" name="status" required><br>
        <label>राजनीतिक पार्टी:</label><input type="text" name="party" required><br>
        <label>नाम:</label><input type="text" name="name" required><br>
        <input type="submit" value="Add Candidate">
    </form>

    <h2>Update Candidate</h2>
    <form method="post" action="">
        <input type="hidden" name="action" value="Fetch Candidate">
        <h3>Existing Details</h3>
        <label>प्रदेश:</label><input type="text" name="province" required><br>
        <label>जिल्ला:</label><input type="text" name="district" required><br>
        <label>स्थानीय इकाई:</label><input type="text" name="local_unit" required><br>
        <label>स्थिति:</label><input type="text" name="status" required><br>
        <label>राजनीतिक पार्टी:</label><input type="text" name="party" required><br>
        <label>नाम:</label><input type="text" name="name" required><br>
        <input type="submit" value="Fetch Candidate">
    </form>

    <?php if ($existing_candidate): ?>
    <h3>Update Details</h3>
    <form method="post" action="">
        <input type="hidden" name="action" value="Update Candidate">
        <input type="hidden" name="old_province" value="<?php echo $existing_candidate['प्रदेश']; ?>">
        <input type="hidden" name="old_district" value="<?php echo $existing_candidate['जिल्ला']; ?>">
        <input type="hidden" name="old_local_unit" value="<?php echo $existing_candidate['स्थानीय_इकाई']; ?>">
        <input type="hidden" name="old_status" value="<?php echo $existing_candidate['स्थिति']; ?>">
        <input type="hidden" name="old_party" value="<?php echo $existing_candidate['राजनीतिक_पार्टी']; ?>">
        <input type="hidden" name="old_name" value="<?php echo $existing_candidate['नाम']; ?>">

        <label>प्रदेश:</label><input type="text" name="new_province" value="<?php echo $existing_candidate['प्रदेश']; ?>" required><br>
        <label>जिल्ला:</label><input type="text" name="new_district" value="<?php echo $existing_candidate['जिल्ला']; ?>" required><br>
        <label>स्थानीय इकाई:</label><input type="text" name="new_local_unit" value="<?php echo $existing_candidate['स्थानीय_इकाई']; ?>" required><br>
        <label>स्थिति:</label><input type="text" name="new_status" value="<?php echo $existing_candidate['स्थिति']; ?>" required><br>
        <label>राजनीतिक पार्टी:</label><input type="text" name="new_party" value="<?php echo $existing_candidate['राजनीतिक_पार्टी']; ?>" required><br>
        <label>नाम:</label><input type="text" name="new_name" value="<?php echo $existing_candidate['नाम']; ?>" required><br>
        <input type="submit" value="Update Candidate">
    </form>
    <?php endif; ?>
</body>
</html>

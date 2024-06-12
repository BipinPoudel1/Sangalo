<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1, h2 {
            text-align: center;
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
        form {
            max-width: 600px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
        }
        form label {
            margin-top: 10px;
        }
        form input[type="text"], form input[type="submit"] {
            padding: 10px;
            margin-top: 5px;
        }
        form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        form input[type="submit"]:hover {
            background-color: #45a049;
        }
        .actions {
            display: flex;
            gap: 10px;
        }
        .actions form {
            margin: 0;
        }
        .hidden {
            display: none;
        }
        @media (max-width: 768px) {
            table, th, td {
                display: block;
            }
            th {
                display: none;
            }
            td {
                padding-left: 50%;
                text-align: right;
                position: relative;
            }
            td:before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                width: 45%;
                padding-left: 10px;
                font-weight: bold;
                text-align: left;
            }
            td.actions {
                text-align: center;
                padding-left: 0;
            }
            .actions {
                flex-direction: column;
            }
        }
        @media (max-width: 480px) {
            td {
                padding-left: 40%;
            }
            td:before {
                left: 5px;
                width: 50%;
                padding-left: 5px;
            }
        }
        /* Add CSS styles for the "Add New Candidate" button */
        #addCandidateButton {
          background-color: #4CAF50;
          color: white;
          border: none;
          padding: 10px 20px;
          text-align: center;
          text-decoration: none;
          display: inline-block;
          font-size: 16px;
          margin-top: 20px;
         cursor: pointer;
         border-radius: 5px;
         }

      #addCandidateButton:hover {
    background-color: #45a049;
    }

    </style>
    <script>
        function showAddForm() {
            document.getElementById('addCandidateForm').classList.remove('hidden');
        }

        function showAlert(message) {
            alert(message);
        }
    </script>
</head>
<body>
    <h1>Search the Candidates</h1>
    <form method="post" action="">
        <input type="text" name="search" placeholder="Enter search term">
        <input type="submit" name="action" value="Search">
    </form>

    <?php
    $servername = "localhost";
    $username = "tanjiro"; 
    $password = "kamado";
    $dbname = "sangalo";
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $existing_candidate = null;
    $message = '';

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
                echo "<table>";
                echo "<tr><th>प्रदेश</th><th>जिल्ला</th><th>स्थानीय_इकाई</th><th>स्थिति</th><th>राजनीतिक_पार्टी</th><th>नाम</th><th>Actions</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td data-label='प्रदेश'>" . $row['प्रदेश'] . "</td>";
                    echo "<td data-label='जिल्ला'>" . $row['जिल्ला'] . "</td>";
                    echo "<td data-label='स्थानीय_इकाई'>" . $row['स्थानीय_इकाई'] . "</td>";
                    echo "<td data-label='स्थिति'>" . $row['स्थिति'] . "</td>";
                    echo "<td data-label='राजनीतिक_पार्टी'>" . $row['राजनीतिक_पार्टी'] . "</td>";
                    echo "<td data-label='नाम'>" . $row['नाम'] . "</td>";
                    echo "<td class='actions' data-label='Actions'>
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
            $sql = "INSERT INTO राजनीतिज्ञ (प्रदेश, जिल्ला, स्थानीय_इकाई, स्थिति, राजनीतिक_पार्टी, नाम) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssssss', $province, $district, $local_unit, $status, $party, $name);
            if ($stmt->execute()) {
                $message = "New candidate added successfully.";
                echo "<script>showAlert('$message');</script>";
            } else {
                $message = "Error: " . $stmt->error;
                echo "<script>showAlert('$message');</script>";
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

            $sql = "UPDATE राजनीतिज्ञ SET प्रदेश = ?, जिल्ला = ?, स्थानीय_इकाई = ?, स्थिति = ?, राजनीतिक_पार्टी = ?, नाम = ? WHERE प्रदेश = ? AND जिल्ला = ? AND स्थानीय_इकाई = ? AND स्थिति = ? AND राजनीतिक_पार्टी = ? AND नाम = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssssssssssss', $new_province, $new_district, $new_local_unit, $new_status, $new_party, $new_name, $old_province, $old_district, $old_local_unit, $old_status, $old_party, $old_name);
            if ($stmt->execute()) {
                $message = "Candidate updated successfully.";
                echo "<script>showAlert('$message');</script>";
            } else {
                $message = "Error: " . $stmt->error;
                echo "<script>showAlert('$message');</script>";
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
                $message = "Candidate deleted successfully.";
                echo "<script>showAlert('$message');</script>";
            } else {
                $message = "Error: " . $stmt->error;
                echo "<script>showAlert('$message');</script>";
            }
            $stmt->close();
        }
    }

    $conn->close();
    ?>

    <?php if ($existing_candidate) : ?>
        <h2>Update Candidate</h2>
        <form method="post" action="">
            <input type="hidden" name="action" value="Update Candidate">
            <input type="hidden" name="old_province" value="<?php echo $existing_candidate['प्रदेश']; ?>">
            <input type="hidden" name="old_district" value="<?php echo $existing_candidate['जिल्ला']; ?>">
            <input type="hidden" name="old_local_unit" value="<?php echo $existing_candidate['स्थानीय_इकाई']; ?>">
            <input type="hidden" name="old_status" value="<?php echo $existing_candidate['स्थिति']; ?>">
            <input type="hidden" name="old_party" value="<?php echo $existing_candidate['राजनीतिक_पार्टी']; ?>">
            <input type="hidden" name="old_name" value="<?php echo $existing_candidate['नाम']; ?>">

            <label for="new_province">Province:</label>
            <input type="text" id="new_province" name="new_province" value="<?php echo $existing_candidate['प्रदेश']; ?>"><br>

            <label for="new_district">District:</label>
            <input type="text" id="new_district" name="new_district" value="<?php echo $existing_candidate['जिल्ला']; ?>"><br>

            <label for="new_local_unit">Local Unit:</label>
            <input type="text" id="new_local_unit" name="new_local_unit" value="<?php echo $existing_candidate['स्थानीय_इकाई']; ?>"><br>

            <label for="new_status">Status:</label>
            <input type="text" id="new_status" name="new_status" value="<?php echo $existing_candidate['स्थिति']; ?>"><br>

            <label for="new_party">Party:</label>
            <input type="text" id="new_party" name="new_party" value="<?php echo $existing_candidate['राजनीतिक_पार्टी']; ?>"><br>

            <label for="new_name">Name:</label>
            <input type="text" id="new_name" name="new_name" value="<?php echo $existing_candidate['नाम']; ?>"><br>

            <input type="submit" value="Update">
        </form>
    <?php endif; ?>

    <h2><button id="addCandidateButton" onclick="showAddForm()">Add New Candidate</button></h2>
    <form method="post" action="" id="addCandidateForm" class="hidden">
        <input type="hidden" name="action" value="Add Candidate">

        <label for="province">Province:</label>
        <input type="text" id="province" name="province"><br>

        <label for="district">District:</label>
        <input type="text" id="district" name="district"><br>

        <label for="local_unit">Local Unit:</label>
        <input type="text" id="local_unit" name="local_unit"><br>

        <label for="status">Status:</label>
        <input type="text" id="status" name="status"><br>

        <label for="party">Party:</label>
        <input type="text" id="party" name="party"><br>

        <label for="name">Name:</label>
        <input type="text" id="name" name="name"><br>

        <input type="submit" value="Add Candidate">
    </form>
</body>
</html>

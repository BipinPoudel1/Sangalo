<!DOCTYPE html>
<html>
<head>
    <title>Search</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            text-align: center;
        }

        h1 {
            margin-top: 0;
        }

        .search-box {
            display: inline-block;
        }

        input[type="text"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 300px;
            box-sizing: border-box;
            background-image: url('search_icon.png'); /* Replace 'search_icon.png' with the path to your search icon */
            background-position: 10px 10px; /* Adjust the position of the icon */
            background-repeat: no-repeat;
            padding-left: 40px; /* Adjust the padding to leave space for the icon */
        }

        input[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            background-color: #007BFF;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Search Candidates</h1>
        <form class="search-box" method="post" action="search_results.php">
            <input type="text" name="search" placeholder="Enter search term"><br><br>
            <input type="submit" value="Search">
        </form>
    </div>
</body>
</html>

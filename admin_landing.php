<?php
session_start();
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Landing Page</title>
    <style>
       body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #333;
            overflow: hidden;
        }
        .navbar a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }
        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }
        .slider {
            position: relative;
            max-width: 100%;
            height: 550px;
            margin: auto;
            overflow: hidden;
        }
        .slides {
            display: none;
            width: 100%;
            height: 100%;
            position: absolute;
            animation: fade 1s;
        }
        .slides img {
            width: 100%;
            height:100%;
            object-fit: cover;
        }
        .text {
            position: absolute;
            bottom: 120px;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #f2f2f2;
            font-size: 24px;
            padding: 8px 12px;
            text-align: center;
            animation: fadeText 2s;
        }
        .text h2, .text p {
            margin: 0;
        }
        @keyframes fade {
            from {opacity: .4} 
            to {opacity: 1}
        }
        @keyframes fadeText {
            from {opacity: 0; transform: translate(-50%, -40%);}
            to {opacity: 1; transform: translate(-50%, -50%);}
        }
    </style>
</head>
<body>
<div class="navbar">
        <a href="landing.html">Home</a>
        <a href="for_admin.php">About Us</a>
        <a href="temp_CRUD_admin1.php">Search</a>
        <a href="landing.html" onclick="logout()">Logout</a>
        <a href="for_admin.php">Welcome, admin 💀</a>
        <!-- <a href="admin_dashboard.php">Admin Dashboard</a> -->
    </div>

    <div class="slider">
        <div class="slides">
            <img src="./Images/nepal-village.jpg" alt="Slide 1">
            <div class="text">
                <h2>Welcome, Admin</h2>
                <p>Manage your content here.</p>
            </div>
        </div>
        <div class="slides">
            <img src="./Images/nepal-map.jpg" alt="Slide 2">
            <div class="text">
                <h2>Admin Panel</h2>
                <p>Access all administrative functions.</p>
            </div>
        </div>
    </div>

    <script>
         // Function to show logout message
         function logout() {
            if (confirm("Logout Successfully")) {
                window.location.href = "landing.html"; // Redirect to logout script
            }
        }

        let slideIndex = 0;
        showSlides();

        function showSlides() {
            let slides = document.getElementsByClassName("slides");
            for (let i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";  
            }
            slideIndex++;
            if (slideIndex > slides.length) {slideIndex = 1}    
            slides[slideIndex-1].style.display = "block";  
            setTimeout(showSlides, 5000); // Change image every 5 seconds
        }
    </script>
</body>
</html>

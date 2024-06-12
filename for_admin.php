<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
        }
        .message {
            margin-top: 20px;
        }
        img {
            max-width: 100%;
            height: auto;
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
        @media (max-width: 768px) {
            .text {
                font-size: 20px;
                bottom: 100px;
            }
        }
        @media (max-width: 480px) {
            .text {
                font-size: 18px;
                bottom: 80px;
            }
            .navbar a {
                float: none;
                width: 100%;
                text-align: left;
                padding: 10px 16px;
            }
        }
     </style>   
</head>
<body>
<div class="navbar">
        <a href="landing.html">Home</a>
        <a href="#about">About Us</a>
        <a href="temp_CRUD_admin1.php">Search</a>
        <a href="landing.html" onclick="logout()">Logout</a>
        <a href="for_admin.php">Welcome, admin 💀</a>
        <!-- <a href="admin_dashboard.php">Admin Dashboard</a> -->
    </div>

    <script>
         // Function to show logout message
         function logout() {
            if (confirm("Logout Successfully")) {
                window.location.href = "landing.html"; // Redirect to logout script
            }
        }

    </script>
    <div class="container">
        <h1>Welcome, Admin</h1>
        <img src="./Images/admin.jpg" alt="admin Image">
        <div class="message">
            <p>Welcome, esteemed Administrator! Within this virtual realm, you wield the power to shape and mold the digital landscape with grace and precision. As the steward of this website, your presence ensures the seamless orchestration of content and functionality. With the ability to Create, Read, Update, and Delete (CRUD) data, you hold the key to maintaining order and facilitating growth. Embrace your role as the guardian of information, guiding its flow with wisdom and foresight. Through your actions, this digital canvas blossoms into a vibrant tapestry of interconnected experiences, enriching the lives of all who traverse its pathways. Your dedication and expertise are the cornerstone of this digital realm, and your contributions pave the way for boundless innovation and progress. Step forth with confidence, for you are the architect of possibility, shaping tomorrow's landscape with every keystroke.</p>
        </div>
    </div>
</body>
</html>

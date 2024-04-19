<!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles.css" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jersey+20&display=swap" rel="stylesheet">

    <title>Login Page</title>
 </head>
 <body class="jersey-20-regular">

<?php
    // Session start is used to make sure the log in state is kept
    // Simply removes your username from the session and destroys the session
    session_start();
    unset($_SESSION["username"]);
    session_destroy();
?>

<h2> You have logged out! </h2>
<p> Click here to log back in -> <a href="login.php">Log in</a></p>

 </body>
 </html>
 
<?php
// Session start is used to make sure the log in state is kept
session_start();
// If the user is not logged in, this will redirect them to the Register Screen
if (!isset($_SESSION['uid'])){
    header("Location: login.php");
    exit();
}

// This takes the form and begins to process it
if(isset($_POST["submit"])){
    // Connects to the database using dbConnect file
    require_once('dbConnect.php');

    // Get the current user's uid from the session
    $uid = isset($_SESSION["uid"]) ? $_SESSION["uid"] : null;

    // Collects project details submitted form
    // $_POST is used to access each feild, and assigned to a corresponding variable
    // htmlspecialchars has been used to prevent Cross Site Sripting attacks
    // 'htmlspecialchars' treats any text as plain text, so no html,JS or SQL querys can be injected
    $title = isset($_POST["title"]) ? htmlspecialchars($_POST["title"]) : false;
    $start_date = isset($_POST["sDate"]) ? htmlspecialchars($_POST["sDate"]) : false;
    $end_date = isset($_POST["eDate"]) ? htmlspecialchars($_POST["eDate"]) : false;
    $phase = isset($_POST["phase"]) ? htmlspecialchars($_POST["phase"]) : false;
    $description = isset($_POST["descript"]) ? htmlspecialchars($_POST["descript"]) : false;

    // Validates input data to ensure all required feilds are filled
    // I have left out "end date" becasue it might not be known, when starting a new project
    if(!$title || !$start_date || !$phase || !$description || !$uid){
        // Displays the alert
        echo "All fields are required!";
        exit;
    }

    try{
        // Preparing and executing the INSERT query into the database
        // 'prepare' is a PDO method 
        // '?' are placeholers for project details
        // Preparing also prevent SQL injections
        $stat = $db->prepare("INSERT INTO projects (title, start_date, end_date, phase, description, uid) VALUES (?, ?, ?, ?, ?, ?)");
        // 'execute' is called on the prepared variable ($stat)
        // This inserts the collected 'htmlspecialchars' values into the '?' placeholders 
        $stat->execute(array($title, $start_date, $end_date, $phase, $description, $uid));

        // Redirect to the same page if a successful insertion has been made!
        header("Location:projects.php");
        exit;
    }
    // If an error occurs, we store it in the $ex 
    // We then display it with an echo statement
    catch(PDOException $ex){
        echo "Sorry, could not create project <br>";
        echo "Error details: <em>". $ex->getMessage()."</em>";
    }
}
?>


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
<nav class="navbar">
        <ul id="options">
            <li><a class="not-current" onclick="window.location.href='login.php'">Login/Register</a></li>
            <li><a class="not-current" onclick="window.location.href='projects.php'">Projects</a></li>
            <li><a class="not-current" onclick="window.location.href='search.php'">Search</a></li>
            <li><a class="current">Add Project</a></li>
            
            <?php
            // This only displays a log out button if a user is loged in
                if (isset($_SESSION['uid'])){
                    ?>
                    <li><a class="not-current" onclick="window.location.href='logout.php'">Log out</a></li>
                    <?php
                } ?>
        </ul>
    </nav>



<div id="forms">
    <!-- This is a form that uses inputs and selects to add a new project -->
    <section id="register">
        Add New Project
        <form id="register-form" method="post">
            <div>
            Title:<br>    
            <input type ="text" name="title" placeholder="Title" required/><br> 
            Start Date: <br>     
            <input type ="date" name="sDate" placeholder="Start Date" required/><br> 
            End Date: <br>     
            <input type ="date" name="eDate" placeholder="End Date"/><br> 
            Phase:<br>     
            <select name='phase' required>
                    <option value='design'>Design</option>
                    <option value='development'>Development</option>
                    <option value='testing'>Testing</option>
                    <option value='deployment'>Deployment</option>
                    <option value='complete'>Complete</option>
                </select><br>
                Description:<br> 
                <input type="text" name="descript" placeholder="Description"  style="width: 100%; height: 50px; padding: 5px;">
            </div>
            <button id="submit" type="submit" name="submit">Submit</button>
        </form>
    </section>
</div>
</body>
</html>

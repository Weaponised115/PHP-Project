<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jersey+20&display=swap" rel="stylesheet">
    <style>
        .container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        #update {
            flex: 1;
            margin-left: 100px; 
        }
    </style>

    <title>Login Page</title>
    </head>
    <body class="jersey-20-regular">

    <nav class="navbar">
            <ul id="options">
                <li><a class="not-current" onclick="window.location.href='login.php'">Login/Register</a></li>
                <li><a class="not-current" onclick="window.location.href='projects.php'">Projects</a></li>
                <li><a class="not-current" onclick="window.location.href='search.php'">Search</a></li>
                <li><a class="not-current" onclick="window.location.href='create.php'">Add Project</a></li>
                <?php
                // Session start is used to make sure the log in state is kept
                    session_start();
                    if (isset($_SESSION['uid'])) {
                        // If the user is logged in, display the logout option
                        ?>
                        <li><a class="not-current" onclick="window.location.href='logout.php'">Log out</a></li>
                        <?php
                    } ?>
    </nav>
    <?php

                    // Redirects to login page if user is not logged in
                    if (!isset($_SESSION['uid'])) {
                        header("Location: login.php");
                        exit();
                    }

                    // Redirects to projects page if project ID is not provided
                    if (!isset($_GET['pid'])) {
                        header("Location:projects.php");
                        exit();
                    }

                    // Retrieves the project ID from the URL
                    $projectId = $_GET['pid'];

                    // Connects to the database using dbConnect file
                    require_once('dbConnect.php');

                    try {
                        // Creates a SELECT query to fetch project details from the 'projects' table
                        // Joins the 'projects' table with the 'users' table based on the user ID
                        // Retrieves project details along with user information
                        $query = "SELECT p.*, u.username, u.email FROM projects p JOIN users u ON p.uid = u.uid WHERE pid = :pid";
                        
                        // Prepares the SQL query to prevent SQL injection attacks
                        $stat = $db->prepare($query);
                    
                        // Binds the project ID parameter to the prepared statement for extra security 
                        $stat->bindParam(':pid', $projectId);
                        
                        // Executes the SELECT statement to retrieve project details
                        $stat->execute();
                        
                        // Fetches the project details as an array
                        $project = $stat->fetch(PDO::FETCH_ASSOC);
                    
                        // If no project is found
                        if (!$project) {
                            // Display an alert
                            echo "<p>Project not found!</p>";
                        } 
                     else {
                            // Display project details and update form
                            ?>
        <div class='container'>
            <div>
                <!-- Display project details -->
                <h2>Project Details</h2>
                <p><b>Title:</b> <?php echo $project['title']; // These use the gathered info stored in varaibles above ?></p>
                <p><b>Start Date:</b> <?php echo $project['start_date']; ?></p>
                <p><b>End Date:</b> <?php echo $project['end_date']; ?></p>
                <p><b>Phase:</b> <?php echo $project['phase']; ?></p>
                <p><b>Description:</b> <?php echo $project['description']; ?></p>
                <p><b>User:</b> <?php echo $project['username']; ?></p>
                <p><b>User Email:</b> <?php echo $project['email']; ?></p>
            </div>

            <div id='update'>
                <!-- Update project form -->
                <h2>Update Project</h2>
                <form method='post'>
                     <!-- This is a form that uses inputs and selects to change an existing project -->
                    <label>Title:</label><br>
                    <input type='text' name='title' value='<?php echo $project['title']; ?>' placeholder='Title' required><br>
                    <label>Start Date:</label><br>
                    <input type='date' name='start_date' value='<?php echo $project['start_date']; ?>' placeholder='Start Date' required><br>
                    <label>End Date:</label><br>
                    <input type='date' name='end_date' value='<?php echo $project['end_date']; ?>' placeholder='End Date'><br>
                    <label>Phase:</label><br>
                    <!-- Dropdown menu to select project phase -->
                    <select name='phase' style="margin-left:5px;" required>
                        <option value='design' <?php if($project['phase'] == 'design') echo 'selected'; ?>>Design</option>
                        <option value='development' <?php if($project['phase'] == 'development') echo 'selected'; ?>>Development</option>
                        <option value='testing' <?php if($project['phase'] == 'testing') echo 'selected'; ?>>Testing</option>
                        <option value='deployment' <?php if($project['phase'] == 'deployment') echo 'selected'; ?>>Deployment</option>
                        <option value='complete' <?php if($project['phase'] == 'complete') echo 'selected'; ?>>Complete</option>
                    </select><br>
                    <label>Description:</label><br>
                    <input type="text" name="descript" value='<?php echo $project['description']; ?>' placeholder="Description" style="width: 100%; height: 50px; padding: 5px;" required>
                    <!-- Button to submit updated project details -->
                    <button type='submit' name='update' style="margin-left:5px;">Update</button>

                    <!-- Button to remove the project -->
                    <button type='submit' name='remove' style="margin-left:5px; background-color: red; color: white;" onclick="return confirm('Are you sure you want to remove this project?')">Remove Project</button>

                </form>
            </div>
        </div> 
    <?php
    }
} catch(PDOException $ex) {
    // If an error occurs, displays an error alert
    echo "Failed to retrieve project details.<br>";
    echo "Error details: <em>" . $ex->getMessage() . "</em>";
    exit;
}
?>

        <?php

    // Checks if form is submitted
    if(isset($_POST["update"])) {
        require_once('dbConnect.php');

    // Extracts form data
    // $_POST is used to access each feild, and assigned to a corresponding variable
    // htmlspecialchars has been used to prevent Cross Site Sripting attacks
    // 'htmlspecialchars' treats any text as plain text, so no html,JS or SQL querys can be injected
    $title = isset($_POST["title"]) ? htmlspecialchars($_POST["title"]) : '';
    $start_date = isset($_POST["start_date"]) ? htmlspecialchars($_POST["start_date"]) : '';
    $end_date = isset($_POST["end_date"]) ? htmlspecialchars($_POST["end_date"]) : '';
    $phase = isset($_POST["phase"]) ? htmlspecialchars($_POST["phase"]) : '';
    $description = isset($_POST["descript"]) ? htmlspecialchars($_POST["descript"]) : '';

    // Updates project details in the database
    try {
        $query = "UPDATE projects SET title = ?, start_date = ?, end_date = ?, phase = ?, description = ? WHERE pid = ?";
        $stat = $db->prepare($query);
        $stat->execute(array($title, $start_date, $end_date, $phase, $description, $projectId));
        
        // Redirects to project details page
        header("Location: projectDetails.php?pid=$projectId");
        exit();
    } catch (PDOException $ex) {
        echo "Failed to update project details. <br>";
        echo "Error details: <em>" . $ex->getMessage() . "</em>";
    }
}

// if remove is submitted
if(isset($_POST["remove"])) {
    require_once('dbConnect.php');

    try {
        // Delete the project from the database
        $query = "DELETE FROM projects WHERE pid = ?";
        $stat = $db->prepare($query);
        $stat->execute(array($projectId));

        // Redirect to projects page after deletion
        header("Location: projects.php");
        exit();
    } catch (PDOException $ex) {
        echo "Failed to remove project. <br>";
        echo "Error details: <em>" . $ex->getMessage() . "</em>";
    }
}
?>
</body>
</html>

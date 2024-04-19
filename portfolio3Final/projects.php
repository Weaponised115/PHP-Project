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
            <li><a class="current">Projects</a></li>
            <li><a class="not-current" onclick="window.location.href='search.php'">Search</a></li>
            <li><a class="not-current" onclick="window.location.href='create.php'">Add Project</a></li>
            <?php
            // Session start is used to make sure the log in state is kept
                session_start();
                // Logs out option appears if user is logged in
                if (isset($_SESSION['uid'])){
                    ?>
                    <li><a class="not-current" onclick="window.location.href='logout.php'">Log out</a></li>
                    <?php
                } 
            ?>
        </ul>
    </nav>
    <?php	
    // Displays a welcome message using session stored username
    if (isset($_SESSION['uid'])){
        $uid = $_SESSION['uid'];
        // echos the message as a header (2)
        echo "<h2> Welcome " . $_SESSION['username'] . "! </h2>";
    }else{
        echo "<h2> Welcome!</h2>";
    }

    // Connects to the database using dbConnect file
    require_once('dbConnect.php');  
    try {
        // Uses a SELECT query to get projects from the database
        $query = "SELECT * FROM projects";
        $rows = $db->query($query);

        // Display projects if available (if more than 0)
        if ($rows && $rows->rowCount() > 0) {
    ?>
    <table cellspacing="0" cellpadding="5" id="myTable">
        <tr>
            <th align='left'><b>Project Title</b></th>
            <th align='left'><b>Project Phase</b></th>
            <th align='left'><b>Start Date</b></th>
        </tr>
        <?php
            // Iterates through project rows
            while ($row = $rows->fetch()) {
                ?>
                <tr>
                    <!-- Displays project details with link to project details page -->
                    <td class="projectTitles" align='left'><a href='projectDetails.php?pid=<?php echo $row['pid']; ?>'><?php echo $row['title']; ?></a></td>
                    <td align='left'><?php echo $row['phase']; ?></td>
                    <td align='left'><?php echo $row['start_date']; ?></td>
                </tr>
            <?php
            }
            ?>
    </table>
        <?php
        } else {
        ?>
            <p>No Projects in the list.</p>
        <?php 
        }
        ?>
        <?php
        // If an error occurs, we store it in the $ex 
        // We then display it with an echo statement
            } catch (PDOexception $ex) {
        ?>
            <p>Sorry, a database error occurred!</p>
            <p>Error details: <em><?php echo $ex->getMessage(); ?></em></p>
        <?php
        }
        ?>
    <h2 id="createButt"><a href="create.php">Add Project</a></h2><br>
    <h2 id="searchButt"><a href="search.php">Search Project</a></h2>
</body>
</html>

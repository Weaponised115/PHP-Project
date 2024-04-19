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
            <li><a class="current">Search</a></li>
            <li><a class="not-current" onclick="window.location.href='create.php'">Add Project</a></li>
            <?php
            // Session start is used to make sure the log in state is kept
                session_start();
                // If the user is logged in, the 'log out' option will appear 
                if (isset($_SESSION['uid'])){
                    ?>
                    <li><a class="not-current" onclick="window.location.href='logout.php'">Log out</a></li>
                    <?php
                } 
            ?>
        </ul>
    </nav>

    <?php
    // Connects to the database using dbConnect file
    require_once('dbConnect.php');

    if(isset($_POST["searchButt"])){
        // Gets the search request from the user input, sets it to an empty string if not provided
        $search = isset($_POST["search"]) ? $_POST["search"] : '';
        
        // Add wildcards to search for partial matches
        // Exactly like grep *search* in linux
        $searchTerm = "%$search%"; 
    
        // Converts search term to lowercase 
        $searchTermLower = strtolower($searchTerm); 
        
        try {
            // Preparing and executing the SELECT query into the database
            // 'prepare' is a PDO method 
            // '?' is a  placeholer
            // Preparing also prevent SQL injections
            // 'LOWER' will allow us to irradicate case sensetive erorrs
            $stat = $db->prepare("SELECT * FROM projects WHERE LOWER(title) LIKE LOWER(?) OR LOWER(start_date) LIKE LOWER(?)");
            // 'execute' is called on the prepared variable ($stat)
            // This inserts the collected values into the '?' placeholders and gets the user from the database
            $stat->execute(array($searchTermLower, $searchTermLower));
            
            // Gets search results
            $searchResults = $stat->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $ex) {
            // If an error occurs, displays an error alert
            echo "Failed to search projects. <br>";
            echo $ex->getMessage();
        }
    } else {
        // If search button is not yet clicked, fetches all projects
        try {
            $stat = $db->prepare("SELECT * FROM projects");
            $stat->execute();
            // Fetch all projects
            $searchResults = $stat->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $ex) {
            // If an error occurs, displays an error alert
            echo "Failed to fetch projects. <br>";
            echo $ex->getMessage();
        }
    }
    ?>
    
    <!-- Search form -->
    <div id="search">
        <section id="search">
            <form method="post">
                <input type="text" name="search" placeholder="Search by title or start date">
                <button type="submit" name="searchButt">Search</button>
            </form>
        </section>
    </div>
    
    <!-- Display search results if available -->
    <?php if(isset($searchResults) && !empty($searchResults)): ?>
        <div id="project-list">
            <?php foreach($searchResults as $row): ?>
                <div class="project">
                    <!-- Display project details -->
                    <h2><?php echo $row['title']; ?></h2>
                    <p><strong>Start Date:</strong> <?php echo $row['start_date']; ?></p>
                    <p><strong>End Date:</strong> <?php echo $row['end_date']; ?></p>
                    <p><strong>Phase:</strong> <?php echo $row['phase']; ?></p>
                    <p><strong>Description:</strong> <?php echo $row['description']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php elseif(isset($searchResults) && empty($searchResults)): ?>
        <!-- Display message if no projects found -->
        <p>No projects found.</p>
    <?php endif; ?>
 </body>
 </html>
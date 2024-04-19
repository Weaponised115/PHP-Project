<?php
// Session start is used to make sure the log in state is kept
session_start();

// Generates a CSRF token, if one is not(!) already set
if (!isset($_SESSION['csrf_token'])) {
    // 'bin2hex' converts a string to hex
    // Generates a secure random string of bytes using the 'random_bytes' function with a length of 32 bytes
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Creates a function to validate CSRF token
function validateToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Function to validate CSRF token
// Checks if the submitted CSRF token matches the token stored in the session
// This line ensures the CSRF protection mechanism by preventing timing attacks
if(isset($_POST["loginSubmit"])) {
    // Validate the CSRF token, if not then alert message
    if (!validateToken($_POST['csrf_token'])) {
        exit("CSRF token validation failed.");
    }

    // Makes sure both inputs are filled in, user and password
    // If not, alert the user, this shouldn't be possible anyway has "required" is set on inputs
    if(!isset($_POST['RealUser'],$_POST['RealPassword'])){
        exit("Please fill in BOTH fields.");
    }
    
    // Connects to the database using dbConnect file
    require_once ('dbConnect.php'); 
    try{
        // Preparing and executing the SELECT query into the database
        // 'prepare' is a PDO method 
        // '?' is a  placeholer
        // Preparing also prevent SQL injections
        $stat = $db->prepare("SELECT * FROM users WHERE username = ?");
        // 'execute' is called on the prepared variable ($stat)
        // This inserts the collected values into the '?' placeholders and gets the user from the database
        $stat->execute(array($_POST['RealUser']));

        // Checks if user exists
        if ($stat->rowCount()>0){
            $row = $stat->fetch(); 
             // Verifies password     
            if(password_verify($_POST['RealPassword'], $row['password'])){
                // Starts session and stores the users details in the session
                session_start();
                $_SESSION["username"] = $_POST['RealUser'];
                $_SESSION["uid"] = $row['uid']; // Storing uid in session
                // Redirect to projects page if a successful login is made
                header("Location: projects.php");
                exit();
            } else {
                // Stores alert as variable (used later)
                $passwordError = "<p style='color:red'>Error logging in, incorrect password</p>";
            }
        } else {
            // Stores alert as variable (used later)
            $usernameError = "<p style='color:red'>Error logging in, username not found</p>";
        }
    } catch(PDOException $ex){
        echo("Failed to connect to database. <br>");
        echo($ex->getMessage());
        exit;
    }
}
// Check if form is submitted for registration
if(isset($_POST["submit"])){
    // Validate CSRF token
    if (!validateToken($_POST['csrf_token'])) {
        exit("CSRF token validation failed.");
    }
    // Connects to the database
    require_once('dbConnect.php');

      // Retrieves deatils from submitted form and inserts to variables 
      // uses password_hash to make storing the password more secure
    $username = isset($_POST["username"]) ? $_POST["username"] : false;
    $email = isset($_POST["email"]) ? $_POST["email"] : false;
    $password = isset($_POST["password"]) ? password_hash($_POST["password"], PASSWORD_DEFAULT) : false;

    // Validates input data
    if(!$username){
        echo "Wrong Username!";
        exit;
    }
    if(!$password){
        exit("Wrong Password!");
    }
    try{
        // Preparing and executing the INSERT query into the database
        // 'prepare' is a PDO method 
        // '?' are placeholers for user details
        $stat=$db->prepare("insert into users values(default,?,?,?)");
        // 'execute' is called on the prepared variable ($stat)
        // This inserts the collected values into the '?' placeholders 
        $stat->execute(array($username, $password, $email));

        // Collects the correct user ID by collecting most recent ID 
        $id=$db->lastInsertId();
        echo"Congratulations! You are registered, with the ID: $id ";
    }
    // If an error occurs, the error is stored in $ex and displayed along with an alert
    catch(PDOException $ex){
        echo "Sorry, could not create account <br>";
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
<script src="js/main.js" ></script>

<nav class="navbar">
    <ul id="options">
        <li><a class="current">Login/Register</a></li>
        <li><a class="not-current" onclick="window.location.href='projects.php'">Projects</a></li>
        <li><a class="not-current" onclick="window.location.href='search.php'">Search</a></li>
        <li><a class="not-current" onclick="window.location.href='create.php'">Add Project</a></li>
    </ul>
</nav>


<?php if(isset($passwordError)) echo $passwordError; // Displays alert if password is wrong ?>
<?php if(isset($usernameError)) echo $usernameError; // Displays alert if username is wrong ?>

<div id="forms">
     <!-- This is a form that uses inputs to login -->
    <section id="login">
        Log in
        <form id="login-form" method="post">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div>
                <input type="text" name="RealUser" placeholder="Username" required/>
                <input type="password" name="RealPassword" placeholder="Password" required/>
            </div>
            <button class="homeButt"  id="submit" type="submit" name="loginSubmit">Submit</button>
        </form>
    </section>
     <!-- This is a form that uses inputs to sign up -->
    <section id="register">
        Register
        <form id="register-form" method="post">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div>
                <input type="text" name="username" placeholder="Username" required/>
                <input type="email" name="email" placeholder="Email" required/><br>
                <input type="password" id="password" name="password" placeholder="Password" required/>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required onchange="checkPassword()"/>
            </div>
            <button class="homeButt" id="submit" type="submit" name="submit">Submit</button>
        </form>
    </section>
</div>

</body>
</html>

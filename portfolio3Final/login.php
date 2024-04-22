<?php
// Session start is used to make sure the log in state is kept
session_start();

// If the user is not logged in, this will redirect them to the Register Screen
if(isset($_SESSION["username"])) {
    header("Location: projects.php");
    exit();
}
// Generates a CSRF token, if one is not already set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Creates a function to validate CSRF token
function validateToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Function to validate CSRF token
if(isset($_POST["loginSubmit"])) {
    if (!validateToken($_POST['csrf_token'])) {
        exit("CSRF token validation failed.");
    }
    if(!isset($_POST['RealUser'],$_POST['RealPassword'])){
        exit("Please fill in BOTH fields.");
    }
    
    require_once ('dbConnect.php'); 
    try{
        $stat = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stat->execute(array($_POST['RealUser']));

        if ($stat->rowCount()>0){
            $row = $stat->fetch(); 
            if(password_verify($_POST['RealPassword'], $row['password'])){
                session_start();
                $_SESSION["username"] = $_POST['RealUser'];
                $_SESSION["uid"] = $row['uid'];
                header("Location: projects.php");
                exit();
            } else {
                $passwordError = "<p style='color:red'>Error logging in, incorrect password</p>";
            }
        } else {
            $usernameError = "<p style='color:red'>Error logging in, username not found</p>";
        }
    } catch(PDOException $ex){
        echo("Failed to connect to database. <br>");
        echo($ex->getMessage());
        exit;
    }
}

if(isset($_POST["submit"])){
    if (!validateToken($_POST['csrf_token'])) {
        exit("CSRF token validation failed.");
    }
    
    require_once('dbConnect.php');

    $username = isset($_POST["username"]) ? $_POST["username"] : false;
    $email = isset($_POST["email"]) ? $_POST["email"] : false;
    $password = isset($_POST["password"]) ? password_hash($_POST["password"], PASSWORD_DEFAULT) : false;

    if(!$username){
        echo "Wrong Username!";
        exit;
    }
    if(!$password){
        exit("Wrong Password!");
    }
    try{
        $stat=$db->prepare("INSERT INTO users VALUES(default,?,?,?)");
        $stat->execute(array($username, $password, $email));

        $id=$db->lastInsertId();
        echo"Congratulations! You are registered, with the ID: $id ";
        
        session_start();
        $_SESSION["username"] = $username;
        $_SESSION["uid"] = $id;
        header("Location: projects.php");
        exit();
    } catch(PDOException $ex){
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

<?php if(isset($passwordError)) echo $passwordError; ?>
<?php if(isset($usernameError)) echo $usernameError; ?>

<div id="forms">
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

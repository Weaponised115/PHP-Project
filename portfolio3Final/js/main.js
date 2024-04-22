function checkPassword() {
    let fPass = document.getElementById("password");
    let cPass = document.getElementsByName("confirm_password")[0]; // Get element by name
    
    // Error message and check result
    let error =  '';
    let checkTrue = false;

    // If the passwords match, clear validity message and set the return to true
    if (fPass.value === cPass.value) {
        fPass.setCustomValidity('');
        return true;
    } else {
        // If the passwords do not match, set the validity message to 'Passwords Must Match Buddy!!!' and display it
        // Return false
        error = 'Passwords Must Match Buddy!!!';
        fPass.setCustomValidity(error);
        fPass.reportValidity();
        return false;
    }
}

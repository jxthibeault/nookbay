var username = document.getElementById("username");
var password = document.getElementById("password");
var confirmPass = document.getElementById("confirmPass");
var submit = document.getElementById("submit");
var errorMessage = document.getElementById("errorMessage");

submit.setAttribute("disabled", true);

function validateInput() {
    if (username.value.length < 4) {
        errorMessage.innerHTML = "Username must be at least 4 characters long.";
        submit.disabled = true;
    } else if (username.value.length > 16) {
        errorMessage.innerHTML = "Username must be no longer than 16 characters.";
        submit.disabled = true;
    } else if (password.value.length < 8) {
        errorMessage.innerHTML = "Password must be at least 8 characters long.";
        submit.disabled = true;
    } else if (password.value.length > 25) {
        errorMessage.innerHTML = "Password must be no longer than 25 characters.";
        submit.disabled = true;
    } else if (password.value !== confirmPass.value) {
        errorMessage.innerHTML = "Passwords entered do not match.";
        submit.disabled = true;
    } else {
        errorMessage.innerHTML = "";
        submit.disabled = false;
    }
}

var params = new URLSearchParams(location.search);
if (params.has("usernameError")) {
    errorMessage.innerHTML = "Username is already taken.";
}

username.onchange = validateInput;
password.onchange = validateInput;
confirmPass.onkeyup = validateInput;
var logoNavbar = document.getElementById("logo_navbar");

logoNavbar.innerHTML = "<div class=\"row navbar\">";
logoNavbar.innerHTML = logoNavbar.innerHTML + "<div class=\"col-10\"><img src=\"https://nookbay.app/media/logo.png\" /></div>";

var cookies = document.cookie;
if (cookies.includes("nookbayAuth")) {
    logoNavbar.innerHTML = logoNavbar.innerHTML + "<div class=\"col-1\"></div>";
    logoNavbar.innerHTML = logoNavbar.innerHTML + "<div class=\"col-1\">My Account</div>";
} else {
    logoNavbar.innerHTML = logoNavbar.innerHTML + "<div class=\"col-1\">Log in</div>";
    logoNavbar.innerHTML = logoNavbar.innerHTML + "<div class=\"col-1\">Register</div>";
}

logoNavbar.innerHTML = logoNavbar.innerHTML + "</div>";
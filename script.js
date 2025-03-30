// Function to handle Google Login credentials response
function handleCredentialResponse(response) {
    // Decode the JWT credential received from Google
    const userInfo = jwt_decode(response.credential);
    const userName = userInfo.name;
    const userEmail = userInfo.email;
    console.log("User Info:", userInfo);

    // Save user data in local storage or session
    localStorage.setItem("user", JSON.stringify(userInfo));
    localStorage.setItem("isLoggedIn", "true");

    // Redirect to home page
    window.location.href = "http://localhost/FarmEase/home.html";
}

// Initialize Google Login
window.onload = function () {
    google.accounts.id.initialize({
        client_id: "719581477177-paiev0gncnr91gtje5thgtat0vta6j53.apps.googleusercontent.com",
        callback: handleCredentialResponse,
    });

    google.accounts.id.renderButton(
        document.querySelector(".g_id_signin"), // Selector for the Google Sign-In button
        {
            theme: "outline",
            size: "large",
            text: "signin_with",
        }
    );
};


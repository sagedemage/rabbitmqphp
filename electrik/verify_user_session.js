function get_cookie_value(cookie_name) {
    let decodedCookie = decodeURIComponent(document.cookie);
    const cookieValue = decodedCookie
        .split("; ")
        .find((row) => row.startsWith(cookie_name + "="))
        ?.split("=")[1];
    return cookieValue;
}

function logMessage(message) {
    axios.post('/log_message.php', {
        message: message
    })
    .catch(function(error) {
        console.error("Logging error: ", error);
    });
}

let user_id = get_cookie_value("user_id");

axios.post('/verify_user_session.php', {
    user_id: user_id,
})
.then(function(response) {
    if (response.data !== true) {
        logMessage("Session verification failed for user_id: " + user_id);
        location.href = "/login.php";
    }
})
.catch(function(error) {
    console.log(error);
    logMessage("Error in session verification: " + error.toString());
});

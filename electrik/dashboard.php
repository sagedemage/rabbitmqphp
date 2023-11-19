<!DOCTYPE html>
<html>
	<body>
		<h1>Dashboard page</h1>
		<p>This is the dashboard page</p>

		<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
		<script>
			function get_cookie_value(cookie_name) {
				let decodedCookie = decodeURIComponent(document.cookie);
				const cookieValue = decodedCookie
					.split("; ")
					.find((row) => row.startsWith(cookie_name + "="))
					?.split("=")[1];
				return cookieValue;
			}
			let user_id = get_cookie_value("user_id");
			axios.post('/verify_user_session.php', {
				user_id: user_id,
			})
				.then(function (response) {
					if (response.data !== true) {
						location.href = "/login.html";
					}
				})
				.catch(function (error) {
					console.log(error);
				});
		</script>
	</body>
</html>

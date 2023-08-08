function validateForm() {
	var url = document.getElementById("url").value;
	if (!isValidURL(url)) {
		alert("Please enter a valid URL.");
		return false;
	}
	return true;
}

function isValidURL(url) {
	// Regular expression pattern to validate URLs
	var urlPattern = /^(https?:\/\/)?([\da-z.-]+)\.([a-z.]{2,6})(\/[\w.-]*)*\/?$/i;
	// Test the URL against the pattern
	return urlPattern.test(url);
}
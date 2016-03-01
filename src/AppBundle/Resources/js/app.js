$(document).ready(function() {
	if ($("#createContactButton").length > 0) {
		$("#createContactButton").on("click", function(e) {
			var isValid = isFormValid();
			var form = $("form").serialize();
			console.log(form);
			e.preventDefault();
			e.stopPropagation();
			if (isValid) {
				$.post("/contact/new", form, function(data) {
					console.log(data);
					if (data.status === "error") {
						alert('Une erreur est survenue lors de l\'enregistrement de l\'utilisateur');
					} else {
						alert('L\'utilisateur a bien été enregistré');
					}
				});
			} else {
				alert('Formulaire non valide');
			}
		});
	}

	function isFormValid() {
		if ($("form").length > 0) {
			console.log(isEmailValid($("#email").val().trim()));
			if ($("#lastname").val().trim() !== ""
					&& isEmailValid($("#email").val().trim())
					&& isWebsiteValid($("#website").val().trim())) {
				return true;
			}
		}
		return false;
	}

	function isEmailValid(email) {
		console.log(email);
		var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	    return re.test(email);
	}

	function isWebsiteValid(website) {
		var re = /(http|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])?/;
		return re.test(website);
	}
});

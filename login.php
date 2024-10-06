<?php
	require_once("db_utils.php");

	$d = new Database();
	$errors = [];
	$messages = [];

	session_start();

	if (isset($_GET["logout"])){
		session_destroy();
	} elseif (isset($_SESSION["user"])) {
		header("Location: profile.php");
	}

	if (isset($_GET["login-fail"])) {
		$messages[] = "Pogrešan nick ili šifra";
	}

	if (isset($_GET["forget-me"])) {
		setcookie("nick", "", time()-1000);
		header("Location: login.php");
	}

	function outputError($errorCode) {
		global $errors;
		if (isset($errors[$errorCode])) {
			echo '<div class="error">' . $errors[$errorCode] . '</div>';
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
	<link rel="stylesheet" href="css/navigacija.css">
	<title>Drustvena mreza - login</title>
</head>

<body>
	<div class="navigacija tamno">
        <a href="homepage.php">Pocetna</a>
		<a href="profile.php">Profil</a>
		<!-- <a href="login.php?logout" id="logout-button">Odjavi se</a> -->
	</div>

	<?php
		if (!empty($messages)) {
			echo "<div class=\"kontejner poruke svetlo\">";
			foreach ($messages as $message) {
				echo "<div>$message</div>";
			}
			echo "</div><br>";
		}
	?>

    <div class="kontejner">
		<h2>Uloguj se</h2>
		<form method="post" action="profile.php">
			<label for="nick">Korisničko ime:</label> 
			<input type="text" name="nick" value="<?php echo isset($_COOKIE["nick"]) ? $_COOKIE["nick"] : "";?>"><br>

			<label for="sifra">Sifra:</label>
			<input	type="password" name="sifra"><br>
					
			<input type="checkbox" name="remember-me" checked> Zapamti moj nick<br> 
			<a href="?forget-me">Forget me</a>

			<input type="submit" name="loginButton" value="Uloguj se">
		</form>
        Nemate nalog? <a href="/VP/Projekat/register.php">Registruj se</a>
	</div>

</body>
</html>
<?php
	require_once("db_utils.php");

	$d = new Database();
	$errors = [];
	$messages = [];
	

	session_start();

	if (isset($_GET["logout"])){
		session_destroy();
	} elseif (isset($_SESSION["user"])) {
		header( "Location: homepage.php" );
	}

	function outputError($errorCode) {
		global $errors;
		if (isset($errors[$errorCode])) {
			echo '<div class="error">' . $errors[$errorCode] . '</div>';
		}
	}
	
	$ime = $nick = $sifra = $opis = $email = $rodjendan = $pol= "";

	if (isset($_POST["registerButton"])) {
		if ($_POST["ime"]) {
			$ime = htmlspecialchars($_POST["ime"]);
		}	
		if ($_POST["nick"]) {
			$nick = htmlspecialchars($_POST["nick"]);
		}
		if ($_POST["sifra"]) {
			$sifra = $_POST["sifra"];
		}
		if ($_POST["opis"]) {
			$opis = htmlspecialchars($_POST["opis"]);
		}
		if ($_POST["email"]) {
			$email = htmlspecialchars($_POST["email"]);
		}
		if ($_POST["rodjendan"]) {
			$rodjendan = htmlspecialchars($_POST["rodjendan"]);
		}
		if (isset($_POST["pol"])) {
			$pol = htmlspecialchars($_POST["pol"]);
		}


		if (!$ime) {
			$errors["ime"] = "Unesite ime i prezime";
		}
		if (!$nick) {
			$errors["nick"] = "Unesite korisničko ime";
		}
		if (!$sifra) {
			$errors["sifra"] = "Unesite sifru";
		}

		if (empty($errors)) {
			$success = $d->insertUser($nick, $sifra, $ime, $opis, $email, $rodjendan, $pol);
			$messages[] = $success ? "Registracija je uspela" : "Registracija nije uspela";
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
    <title>Drustvena mreza - registracija</title>
</head>
<style>
.error {
	color: #FF0000;
	font-size: 0.7em;
}
</style>
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
		<h2>Registruj se</h2>
		<form method="post" action="">
			<label for="ime" class="obavezno-polje">Ime i prezime:</label>
			<?php outputError("ime");?>
			<input type="text" name="ime" value=""><br>
			
			<label for="nick" class="obavezno-polje">Korisničko ime:</label>
			<?php outputError("nick");?>
			<input type="text" name="nick" value=""><br>

			<label for="sifra" class="obavezno-polje">Lozinka:</label>
			<?php outputError("sifra");?>
			<input type="password" name="sifra" value=""><br>
			
			<label for="opis">Opis:</label>
			<input type="text" name="opis" value=""><br>
			
			<label for="email">Email:</label>
			<input type="text" name="email" value=""><br>
			
			<label for="date">Datum rođenja:</label>
			<input type="date" name="rodjendan" value=""><br>
					
			<label for="pol">Pol:</label>
			<input type="radio" name="pol" value="m"> M 
			<input type="radio" name="pol" value="z"> Ž <br> 
					
            <p>NAPOMENA: Polja oznacena sa * su obavezna polja.</p>

            <input type="submit" name="registerButton" value="Registruj se">
		</form>
		Imate nalog? <a href="/VP/Projekat/login.php">Prijavi se</a>
	</div>
</body>
</html>
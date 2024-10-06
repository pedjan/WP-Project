<?php	
	require_once("db_utils.php");
	session_start();
	$d = new Database();

    require_once "classes/Utils.php";
	
	$main_user = false;
	if (isset($_POST["loginButton"])) {
		$main_user = $d->checkLogin(htmlspecialchars($_POST["nick"]), htmlspecialchars($_POST["sifra"]));
		if (!$main_user) {	
			header("Location: login.php?login-fail");
		} else {
			$_SESSION["user"] = $main_user;
			if ($_POST["remember-me"]) {
				setcookie("nick", $main_user[COL_USER_USERNAME], time()+60*60*24*365);
			}
			header("Location: profile.php");
		}
	}else if(!isset($_SESSION["user"])) {
		header("Location: login.php");
	}

	$errorMessage = "";
	
	if (!isset($_SESSION["user"])) {
		header("Location: login.php");
	}
	
	if (!$main_user) {
		$main_user = $_SESSION["user"];
	}

	if (isset($_POST["postaviStatus"])) {
		if($_POST["status"] != "" || $_POST["link"] != "" || $_FILES["slika"]["error"] == UPLOAD_ERR_OK) {
			$putanjaslike = NULL;
			if ( isset( $_FILES["slika"] ) and $_FILES["slika"]["error"] == UPLOAD_ERR_OK ) {
				$putanjaslike = "images/" . basename( $_FILES["slika"]["name"] );
                move_uploaded_file($_FILES["slika"]["tmp_name"], $putanjaslike);
			}

			$success = $d->insertPost(htmlspecialchars($_POST["status"]), $main_user[COL_USER_ID], $putanjaslike, htmlspecialchars($_POST["link"]));
			if (!$success) {
				errorMessage("Status nije uspešno sačuvan.");
			} else {
				if (!isset($_SESSION["statusi"])) {
					$_SESSION["statusi"] = array();
				}
				$_SESSION["statusi"][] = htmlspecialchars($_POST["status"]);
			}
		}
	}
	
	function errorMessage($message) {
		global $errorMessage;
		$errorMessage = "<div class='error-msg kontejner svetlo'>$message</div>";
	}

    if (isset($_POST["postaviKomentar"]) && $_POST["komentar"] != "") {
        $main_user = $_SESSION["user"];
		$success = $d->insertComent(htmlspecialchars($_POST["komentar"]), $main_user[COL_USER_ID], $_POST["post1"]);
	}
    if(isset($_POST["like"]) && isset($_SESSION["user"])) {
        $d->updateLike($_POST["like"]);
    }
    if(isset($_POST["dislike"]) && isset($_SESSION["user"])) {
        $d->updateDisike($_POST["dislike"]);
    }
    if((isset($_POST["postid"]) && isset($_POST["userid"]) && isset($_POST["postcontent"])) && $_SESSION["user"][COL_USER_ID] == $_POST["userid"]) {
        $content = htmlspecialchars($_POST["postcontent"]);
        $postid = $_POST["postid"];
        echo "<div style=\"position: fixed; z-index: 1; width: 100%; height: 100%; margin-left: -8px; background-color: rgba(0, 0, 0, 0.6);\"></div>";
        echo "<div style=\"
        position: fixed;
        z-index: 2; 
        background-color: #f5f7f8; 
        border: 1px solid; 
        margin-left: 26.29%;
        margin-top: 20%; 
        width: 45%; 
        padding: 15px;
        box-shadow: 0 2px 4px 0 rgba(0,0,0,0.16),0 2px 10px 0 rgba(0,0,0,0.12);
        border-radius: 14px;\"> 
            <h3>Izmena posta:</h3>
            <form action=\"\" method=\"post\" enctype=\"multipart/form-data\">
                    <textarea class=\"komentar\" name=\"newcontent\" rows=\"4\" cols=\"120\" style=\"resize: vertical;\">{$content}</textarea>
                    <input type=\"hidden\" id=\"post1\" name=\"postid\" value=\"{$postid}\">
                    <input type=\"submit\" value=\"Edit\" name=\"editpost\">
            </form>            
            <form action=\"\" method=\"post\">
                <input type=\"hidden\" id=\"post1\" name=\"delete\" value=\"{$postid}\">
                <input type=\"submit\" value=\"Delete\" name=\"Delete\">
            </form> 
        </div>";
    }
    if(isset($_POST["editpost"])) {
        $d->updatePost($_POST["postid"], htmlspecialchars($_POST["newcontent"]));
    }
    if(isset($_POST["delete"])) {
        $d->deleteCommentsFromPost($_POST["delete"]);
        $stampaj = $d->deletePost($_POST["delete"]);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drustvena mreza - profil</title>
    <link rel="stylesheet" href="css/login.css">
	<link rel="stylesheet" href="css/navigacija.css">
</head>
<style>
    h1 {
        text-align: center;
    }
    .objava { 
        background-color: #f5f7f8;
        border: 1px solid;
        margin: auto;
        margin-top: 50px;
        width: 45%;
        padding-left: 15px;
        padding-right: 15px;
        padding-bottom: 15px;
        box-shadow: 0 2px 4px 0 rgba(0,0,0,0.16),0 2px 10px 0 rgba(0,0,0,0.12);
        border-radius: 14px;
    }
    .sadrzaj {
        padding: 0px 10px;
    }

    .komentari {
        display: flex;
        padding: 0px 10px;
    }

    .like {
        flex: 1;
        text-align: center;
        background-color: green; 
    }  
    .dislike {
        flex: 1;
        text-align: center;
        background-color: red; 
    }  
    .naslov {
        display: flex;
        padding: 0px 10px;
    }
    .linija {
        padding: 1px;
        background-color: #704c4c;
    }
    .linijaa {
        margin-left: 50px;
        margin-right: 50px;
        padding: 1px;
        background-color: #704c4c;
    }
    textarea {
        width: 100%;
    }
    a{
        text-decoration:none;
        color: black;
    }
    .link-button, button:hover{
        background: none;
        border: none;
        color: black;
        text-decoration: none;
    }
    img {
        display: block;
        margin: auto;
        max-width: 100%;
    }
</style>
<body>
	<div class="navigacija tamno">
        <a href="homepage.php">Pocetna</a>
		<a href="profile.php">Profil</a>
        <?php
        if(isset($_SESSION["user"])) {
		    echo "<a href=\"login.php?logout\" id=\"logout-button\">Odjavi se</a>";
        } else {
            echo "<a href=\"login.php\" id=\"logout-button\">Prijavi se</a>";
        }
        ?>
	</div>
    <br>
	<h3>Prijavljen si kao: <?php echo $main_user[COL_USER_NAME];?></h3>

	<?php echo $errorMessage; ?>
	<div class="kontejner svetlo">
			<form action="" method="post" enctype="multipart/form-data">
				Novi status: <textarea name="status" rows="4" cols="50" style="resize: vertical;"></textarea><br>
				Izberite sliku: <input type="file" name="slika"><br>
				Ubacite link: <input type="text" name="link"><br>
				<input type="submit" value="Postavi" name="postaviStatus">
			</form>
		</div>
        <?php
        $posts = Utils::ucitajZaId($main_user[COL_USER_ID]);
		if ($posts){
            foreach($posts as $post){
                $imeKorisnka = $d->getUserById($post->getUserId());
                echo "<div class=\"objava\">";
                    echo "<div class=\"naslov\">
                    <a href=\"?posts={$imeKorisnka[COL_USER_ID]}\"><p style=\"flex: 1;\">".$imeKorisnka[COL_USER_NAME]."</p></a>
                    <p style=\"flex: 1; text-align: center\">".$post->getTime()."</p>";
                    if(isset($_SESSION["user"])) {
                        if($_SESSION["user"][COL_USER_ID] == $imeKorisnka[COL_USER_ID]) {
                            echo "
                            <form method=\"post\" action=\"\" class=\"inline\">
                                <input type=\"hidden\" name=\"postcontent\" value=\"{$post->getContent()}\">
                                <input type=\"hidden\" name=\"userid\" value=\"{$post->getUserId()}\">
                                <button type=\"submit\" name=\"postid\" value=\"{$post->getId()}\" class=\"link-button\" style=\"flex: 1; text-align: right;\">
                                    Edit
                                </button>
                            </form>";
                        } else if($_SESSION["user"][COL_USER_ADMIN] == 1) {
                            echo "
                            <form method=\"post\" action=\"\" class=\"inline\">
                                <button type=\"submit\" name=\"delete\" value=\"{$post->getId()}\" class=\"link-button\" style=\"flex: 1; text-align: right;\">
                                    Delete
                                </button>
                            </form>";
                        }
                    }
                    echo "</div>
                    <div class=\"sadrzaj\">
                        <p>{$post->getContent()}</p>";
                        if($post->getSlika() != "") {
                            echo "<img src=\"{$post->getSlika()}\" alt=\"Greska pri ucitavanju slike\" ></img><br>";
                        }
                        if($post->getLink() != "") {
                            echo "<iframe src=\"{$post->getLink()}\" width=\"100%\" ></iframe>";
                        }
                    echo "</div>
                    <div class=\"komentari\">
                        <form method=\"post\" action=\"\" class=\"inline\">
                            <button type=\"submit\" name=\"like\" value=\"{$post->getId()}\" class=\"like\">
                                Like: {$post->getLikes()}
                            </button>
                            <button type=\"submit\" name=\"dislike\" value=\"{$post->getId()}\" class=\"dislike\">
                                Dislike: {$post->getDislikes()}
                            </button>
                        </form>
                    </div>";
                if(count($post->getKomentari()) >= 0) {
                    $komentari = $post->getKomentari();
                    foreach($komentari as $komentar) {
                        $k = $d->getUserById($komentar->getUserId())[COL_USER_NAME];
                        echo"<div>
                            <p>{$k} u {$komentar->getTime()}</p>
                            <p>{$komentar->getContent()}</p>
                        </div>
                        <div class=\"linijaa\"></div>";
                    }
                }
                if(isset($_SESSION["user"])) {
                    echo "<div>
                    <h3>Komentarisi:</h3>
                    <form action=\"\" method=\"post\" enctype=\"multipart/form-data\">
                        <textarea class=\"komentar\" name=\"komentar\" rows=\"4\" cols=\"120\" style=\"resize: vertical;\"></textarea>
                        <input type=\"hidden\" id=\"post1\" name=\"post1\" value=\"{$post->getId()}\">
                        <input type=\"submit\" value=\"Komentarisi\" name=\"postaviKomentar\">
                    </form>
                    </div>";
                }
                echo "</div>";
            }
        } else {
            echo "<h3 style=\"text-align:center;\">Jos uvek ne postoji ni jedan post</h3>";
        }
	?>	
</body>
</html>
<?php
require_once("constants.php");

class Database {
    private $hashing_salt = "dsaf7493^&$(#@Kjh";

    private $conn;

    public function __construct($configFile = "config.ini") {
        if ($config = parse_ini_file($configFile)) {
            $host = $config["host"];
            $database = $config["database"];
            $user = $config["user"];
            $password = $config["password"];
            $this->conn = new PDO("mysql:host=$host;dbname=$database", $user, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }

    public function __destruct() {
        $this->conn = null;
    }

    function insertUser($nick, $sifra, $ime, $opis, $email, $rodjendan, $pol) {
        try {
            $sql_existing_user = "SELECT * FROM " . TBL_USER . " WHERE " . COL_USER_USERNAME . "= :nick";
            $st = $this->conn->prepare($sql_existing_user);
            $st->bindValue(":nick", $nick, PDO::PARAM_STR);
            $st->execute();
            if ($st->fetch()) {
                return false;
            }
            $hashed_password = crypt($sifra, $this->hashing_salt);
            $sql_insert = "INSERT INTO " . TBL_USER . " (".COL_USER_USERNAME."," .COL_USER_PASSWORD."," .COL_USER_NAME."," .COL_USER_DESC."," .COL_USER_EADDRESS."," .COL_USER_BIRTHDAY."," .COL_USER_GENDER."," .COL_USER_ADMIN.")"
                                              ." VALUES (       :nick,                :sifra,                :ime,              :opis,               :email,              :rodjendan,           :pol,                :admin)";
            $st = $this->conn->prepare($sql_insert);
            $st->bindValue("nick", $nick, PDO::PARAM_STR);
            $st->bindValue("sifra", $hashed_password, PDO::PARAM_STR);
            $st->bindValue("ime", $ime, PDO::PARAM_STR);
            $st->bindValue("opis", $opis, PDO::PARAM_STR);
            $st->bindValue("email", $email, PDO::PARAM_STR);
            $st->bindValue("rodjendan", $rodjendan, PDO::PARAM_STR);
            $st->bindValue("pol", $pol, PDO::PARAM_STR);
            $st->bindValue("admin", 0, PDO::PARAM_INT);
            return $st->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function insertPost($content, $userId, $slika, $link) {
        try {
            $sql = "INSERT INTO " . TBL_POST . " (".COL_POST_TIME."," .COL_POST_CONTENT."," .COL_POST_LIKES."," .COL_POST_DISLIKES.",".COL_POST_USERID."," .COL_POST_SLIKA."," .COL_POST_LINK.")"
                                        ."VALUES (      :time,           :content,              :likes,              :dislikes,            :userId,            :slika,             :link)";
            $time = date("d.m.Y H:i:s");
            $st = $this->conn->prepare($sql);
            $st->bindValue("time", $time, PDO::PARAM_STR);
            $st->bindValue("content", $content, PDO::PARAM_STR);
            $st->bindValue("likes", 0, PDO::PARAM_INT);
            $st->bindValue("dislikes", 0, PDO::PARAM_INT);
            $st->bindValue("userId", $userId, PDO::PARAM_INT);
            $st->bindValue("slika", $slika, PDO::PARAM_STR);
            $st->bindValue("link", $link, PDO::PARAM_STR);
            return $st->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function insertComent($content, $userId, $postId) {
        try {
            $sql = "INSERT INTO " . TBL_COMMENT . " (".COL_COMMENT_TIME. "," .COL_COMMENT_CONTENT."," .COL_COMMENT_USERID."," .COL_COMMENT_POSTID.")"
                                           ."VALUES (       :time,               :content,                 :userId,                :postId)";
            $time = date("d.m.Y H:i:s");
            $st = $this->conn->prepare($sql);
            $st->bindValue("time", $time, PDO::PARAM_STR);
            $st->bindValue("content", $content, PDO::PARAM_STR);
            $st->bindValue("userId", $userId, PDO::PARAM_INT);
            $st->bindValue("postId", $postId, PDO::PARAM_INT);
            return $st->execute();
        } catch (PDOException $e) {
            return false;
        }        
    }

    public function getPosts($userId) {
        try {
            $sql = "SELECT * FROM " . TBL_POST . " WHERE " . COL_POST_USERID . "=:user";
            $st = $this->conn->prepare($sql);
            $st->bindValue("user", $userId, PDO::PARAM_INT);
            $st->execute();
            return $st->fetchAll();
        } catch (PDOException $e) {
            return array();
        }
    }

    public function getAllPosts() {
        try {
            $sql = "SELECT * FROM " . TBL_POST;
            $st = $this->conn->prepare($sql);
            $st->execute();
            return $st->fetchAll();
        } catch (PDOException $e) {
            return array();
        }
    }
    public function getPostsByUser($userid) {
        try {
            $sql = "SELECT * FROM " . TBL_POST . " WHERE " . COL_POST_USERID . "=:userid";
            $st = $this->conn->prepare($sql);
            $st->bindValue("userid", $userid, PDO::PARAM_INT);
            $st->execute();
            return $st->fetchAll();
        } catch (PDOException $e) {
            return array();
        }
    }

    public function getKomentariByPostId($postId) {
        try {
            $sql = "SELECT * FROM " . TBL_COMMENT . " WHERE " . COL_COMMENT_POSTID . "=:postId";
            $st = $this->conn->prepare($sql);
            $st->bindValue("postId", $postId, PDO::PARAM_INT);
            $st->execute();
            return $st->fetchAll();
        } catch (PDOException $e) {
            return null;
        }        
    }

    public function checkLogin($nick, $sifra) {
        try {
            $hashed_password = crypt($sifra, $this->hashing_salt);
            $sql = "SELECT * FROM " . TBL_USER . " WHERE " . COL_USER_USERNAME . "=:nick and " . COL_USER_PASSWORD . "=:sifra";
            $st = $this->conn->prepare($sql);
            $st->bindValue("nick", $nick, PDO::PARAM_STR);
            $st->bindValue("sifra", $hashed_password, PDO::PARAM_STR);
            $st->execute();
            $_SESSION["prijavljen"] = array();
            return $st->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }

    public function getUserById($userId) {
        try {
            $sql = "SELECT * FROM " . TBL_USER . " WHERE " . COL_USER_ID . "=:userId";
            $st = $this->conn->prepare($sql);
            $st->bindValue("userId", $userId, PDO::PARAM_INT);
            $st->execute();
            return $st->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }

    public function updateLike($postId) {
        try {
            $sql = "UPDATE " . TBL_POST . " SET " . COL_POST_LIKES . " = " . COL_POST_LIKES . " + 1 WHERE " . COL_POST_ID . "=:postId";
            $st = $this->conn->prepare($sql);
            $st->bindValue("postId", $postId, PDO::PARAM_INT);
            $st->execute();
            return true;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function updateDisike($postId) {
        try {
            $sql = "UPDATE " . TBL_POST . " SET " . COL_POST_DISLIKES . " = " . COL_POST_DISLIKES . " + 1 WHERE " . COL_POST_ID . "=:postId";
            $st = $this->conn->prepare($sql);
            $st->bindValue("postId", $postId, PDO::PARAM_INT);
            $st->execute();
            return true;
        } catch (PDOException $e) {
            return null;
        }        
    }

    public function updatePost($postId, $newcontent) {
        try {
            $sql = "UPDATE " . TBL_POST . " SET " . COL_POST_CONTENT . "=:newcontent" . " WHERE " . COL_POST_ID . "=:postId";
            // UPDATE post SET content = "novo" WHERE id = 1; OVAJ SQL UPIT RADI
            $st = $this->conn->prepare($sql);
            $st->bindValue("newcontent", $newcontent, PDO::PARAM_STR);
            $st->bindValue("postId", $postId, PDO::PARAM_INT);
            $st->execute();
            return true;
        } catch (PDOException $e) {
            return null;
        }        
    }

    public function deleteCommentsFromPost($postId) {
        try {
            $sql = "DELETE FROM " . TBL_COMMENT . " WHERE " . COL_COMMENT_POSTID . "=:postId";
            $st = $this->conn->prepare($sql);
            $st->bindValue("postId", $postId, PDO::PARAM_INT);
            $st->execute();
            return true;
        } catch (PDOException $e) {
            return null;
        }         
    }

    public function deletePost($postId) {
        try {
            $sql = "DELETE FROM " . TBL_POST . " WHERE " . COL_POST_ID . "=:postId";
            $st = $this->conn->prepare($sql);
            $st->bindValue("postId", $postId, PDO::PARAM_INT);
            $st->execute();
            return true;
        } catch (PDOException $e) {
            return null;
        }    
    }
}

?>
<?php
require_once "classes/Komentar.php";
require_once "classes/Post.php";
require_once("db_utils.php");
class Utils {
    public static function ucitaj() {
        $d = new Database();
        $postovi = array();
        foreach($d->getAllPosts() as $post) {
            $postObj = new Post($post[COL_POST_TIME], $post[COL_POST_ID], $post[COL_POST_CONTENT], $post[COL_POST_USERID], $post[COL_POST_LIKES], $post[COL_POST_DISLIKES], $post[COL_POST_SLIKA], $post[COL_POST_LINK]);
            foreach($d->getKomentariByPostId($post[COL_POST_ID]) as $komentar) {
                $time = $komentar[COL_COMMENT_TIME];
                $id = $komentar[COL_COMMENT_ID];
                $content  = $komentar[COL_COMMENT_CONTENT];
                $userId  = $komentar[COL_COMMENT_USERID];
                $postId  = $komentar[COL_COMMENT_POSTID];
                $k = new Komentar($time, $id, $content, $userId, $postId);
                $postObj->dodajKomentar($k);
            }
            $postovi[] = $postObj;
        }
        if(count($postovi) >= 0) {
            return $postovi;
        }else  return null;
    }

    public static function ucitajZaId($id) {
        $d = new Database();
        $postovi = array();
        foreach($d->getPostsByUser($id) as $post) {
            $postObj = new Post($post[COL_POST_TIME], $post[COL_POST_ID], $post[COL_POST_CONTENT], $post[COL_POST_USERID], $post[COL_POST_LIKES], $post[COL_POST_DISLIKES], $post[COL_POST_SLIKA], $post[COL_POST_LINK]);
            foreach($d->getKomentariByPostId($post[COL_POST_ID]) as $komentar) {
                $time = $komentar[COL_COMMENT_TIME];
                $id = $komentar[COL_COMMENT_ID];
                $content  = $komentar[COL_COMMENT_CONTENT];
                $userId  = $komentar[COL_COMMENT_USERID];
                $postId  = $komentar[COL_COMMENT_POSTID];
                $k = new Komentar($time, $id, $content, $userId, $postId);
                $postObj->dodajKomentar($k);
            }
            $postovi[] = $postObj;
        }
        if(count($postovi) >= 0) {
            return $postovi;
        }else  return null;
    }
}

?>
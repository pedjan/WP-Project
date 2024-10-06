<?php

class Post {
    private $id;
    private $content;
    private $time;
    private $userId;
    private $likes;
    private $dislikes;
    private $slika;
    private $link;
    private $komentari;

    public function __construct($time, $id, $content, $userId, $likes, $dislikes, $slika, $link) {
        $this->time = $time;
        $this->id = $id;
        $this->content = $content;
        $this->userId = $userId;
        $this->likes = $likes;
        $this->dislikes = $dislikes;
        $this->slika = $slika;
        $this->link = $link;
        $this->komentari = array();
    }

    public function getTime() {
        return $this->time;
    }

    public function getId() {
        return $this->id;
    }

    public function getContent() {
        return $this->content;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getLikes() {
        return $this->likes;
    }

    public function getDislikes() {
        return $this->dislikes;
    }

    public function getSlika() {
        return $this->slika;
    }

    public function getLink() {
        return $this->link;
    }

    public function dodajKomentar($k) {
        if (is_a($k, "Komentar"))
            $this->komentari[] = $k;
    }

    public function getKomentari() {
        return $this->komentari;
    }

}

?>
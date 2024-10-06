<?php

class Komentar {
    private $time;
    private $id;
    private $content;
    private $userId;
    private $postId;

    public function __construct($time, $id, $content, $userId, $postId) {
        $this->time = $time;
        $this->id = $id;
        $this->content = $content;
        $this->userId = $userId;
        $this->postId = $postId;
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

    public function getPostId() {
        return $this->postId;
    }

}

?>
<?php

    define("TBL_USER", "User");
    define("COL_USER_USERNAME", "nick");
    define("COL_USER_ID", "id");
    define("COL_USER_PASSWORD", "sifra");
    define("COL_USER_NAME", "ime");
    define("COL_USER_DESC", "opis");
    define("COL_USER_EADDRESS", "email");
    define("COL_USER_BIRTHDAY", "rodjendan");
    define("COL_USER_GENDER", "pol");
    define("COL_USER_ADMIN", "admin");
    

    define("TBL_POST", "Post");
    define("COL_POST_TIME", "time");
    define("COL_POST_ID", "id");
    define("COL_POST_CONTENT", "content");
    define("COL_POST_USERID", "userId");
    define("COL_POST_LIKES", "likes");
    define("COL_POST_DISLIKES", "dislikes");
    define("COL_POST_SLIKA", "slika");
    define("COL_POST_LINK", "link");

    define("TBL_COMMENT", "Comment");
    define("COL_COMMENT_TIME", "time");
    define("COL_COMMENT_ID", "id");
    define("COL_COMMENT_CONTENT", "content");
    define("COL_COMMENT_USERID", "userId");
    define("COL_COMMENT_POSTID", "postId");
?>
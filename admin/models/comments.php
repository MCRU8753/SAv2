<?php

require_once 'users.php';

class Comment
{
    public $id;
    public $ad_id;
    public $user_id;
    public $text;
    public $country;
    public $user;

    // Konstruktor
    public function __construct($id, $ad_id, $user_id, $text, $country)
    {
        $this->id = $id;
        $this->ad_id = $ad_id;
        $this->user_id = $user_id;
        $this->user = User::find($user_id);
        $this->text = $text;
        $this->country = $country;
    }

    // Metoda, ki iz baze vrne vse uporabnike 
    public static function all()
    {
        $db = Db::getInstance(); // pridobimo instanco baze
        $query = "SELECT * FROM comments;"; // pripravimo query
        $res = $db->query($query); // poženemo query
        $comments = array();
        while ($comment = $res->fetch_object()) {
            // Za vsak rezultat iz baze ustvarimo objekt (kličemo konstuktor) in ga dodamo v array $comments
            array_push($comments, new Comment($comment->id, $comment->ad_id, $comment->user_id, $comment->text, $comment->country));
        }
        return $comments;
    }

    public static function lastFive() {
        $db = Db::getInstance(); 
        $query = "SELECT * FROM comments ORDER BY id DESC LIMIT 5;"; // pripravimo query
        $res = $db->query($query); // poženemo query
        $comments = array();
        while ($comment = $res->fetch_object()) {
            // Za vsak rezultat iz baze ustvarimo objekt (kličemo konstuktor) in ga dodamo v array $comments
            array_push($comments, new Comment($comment->id, $comment->ad_id, $comment->user_id, $comment->text, $comment->country));
        }
        return $comments;

    }

    // Metoda, ki vrne komentar z določenim ID-jem iz baze
    public static function find($id)
    {
        $db = Db::getInstance();
        $id = mysqli_real_escape_string($db, $id);
        $query = "SELECT * FROM comments WHERE id = '$id';";
        $res = $db->query($query);
        if ($comment = $res->fetch_object()) {
            return new Comment($comment->id, $comment->ad_id, $comment->user_id, $comment->text, $comment->country);
        }
        return null;
    }
    
    // Metoda, ki doda nov komentar v bazo
    public static function insert($ad_id, $user_id, $text, $country)
    {
        $db = Db::getInstance();
        $ad_id = mysqli_real_escape_string($db, $ad_id);
        $user_id = mysqli_real_escape_string($db, $user_id);
        $text = mysqli_real_escape_string($db, $text);
        $country = mysqli_real_escape_string($db, $country);

        $query = "INSERT INTO comments (ad_id, user_id, text, country) VALUES (?, ?, ?, ?);";
        
        $stmt = $db->prepare($query);
        $stmt->bind_param("iiss", $ad_id, $user_id, $text, $country); 
        if($stmt->execute()){
            $id = mysqli_insert_id($db);
            return Comment::find($id);
        }
        else {
            return null;
        }
    }

    // Metoda, ki izbriše komentar iz baze
    public function delete()
    {
        $db = Db::getInstance();
        $id = mysqli_real_escape_string($db, $this->id);
        $query = "DELETE FROM comments WHERE id = '$id'";
        if ($db->query($query)) {
            return true;
        } else {
            return false;
        }
    }
}
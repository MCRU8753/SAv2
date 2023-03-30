<?php

class comments_controller
{

    public function index()
    {
        $comments = Comment::all();

        echo json_encode($comments);
    }

    public function show($id)
    {
        $comment = Comment::find($id);
        echo json_encode($comment);
    }

    public function store()
    {
        $comment = Comment::insert($_POST["ad_id"], $_POST["user_id"], $_POST["text"], $_POST["country"]);
        echo json_encode($comment);
    }

    public function delete($id)
    {
        $comment = Comment::find($id);
        $comment->delete();
        echo json_encode($comment);
    }

    public function outputLastFive() {
        $comments = Comment::lastFive();
        echo json_encode($comments);
    }
}

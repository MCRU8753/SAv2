<?php

require_once "../admin/connection.php";
require_once "../admin/models/comments.php";
require_once "controllers/comments_controller.php";

session_start();

$comments_controller = new comments_controller;

header('Content-Type: application/json');

header("Access-Control-Allow-Origin: *");

$method = $_SERVER['REQUEST_METHOD'];

// Razberemo parametre iz URL - razbijemo URL po '/'
if(isset($_SERVER['PATH_INFO']))
	$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
else
	$request="";

// Preverimo, če je v url-ju prva pot
if(!isset($request[0]) || $request[0] != "comments"){
    echo json_encode((object)["status"=>"404", "message"=>"Not found"]);
    die();
}

// Odvisno od metode pokličemo ustrezen controller action
switch($method){
    case "GET":
        if(isset($request[1]) && $request[1] == "lastFive") {
            $comments_controller->outputLastFive();
        }
        else if(isset($request[1])){
            $comments_controller->show($request[1]);
        } else {
            $comments_controller->index();
        }
        break;
    case "POST": 
        $comments_controller->store();
        break;
    case "DELETE":
        if(!isset($request[1])){
            echo json_encode((object)["status"=>"500", "message"=>"Invalid parameters"]);
            die();
        }
        $comments_controller->delete($request[1]);
        break;
    default: 
        break;
}
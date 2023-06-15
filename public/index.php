<?php

require_once "../config/config.php";
require "../vendor/autoload.php";

session_start();

use src\controller\{Router,Notification};
use src\controller\Home;
use src\controller\User;
use src\controller\Admin;
use src\controller\Student;
use src\controller\Connection;
use src\controller\Complaint;
use src\controller\History;
use app\App;

$router = new Router;

//home routes
$router->get("/",[Home::class,"index"]);
$router->get("index/",[Home::class,"index"]);
$router->get("home/",[Home::class,"index"]);
$router->get("login/",[Home::class,"login"]);
$router->get("create_account/",[Home::class,"create_account"]);

//end of home routes


//student routes

$router->get("student/",[Student::class,"index"]);
$router->get("student/",[Student::class,"index"]);
$router->get("student/dashboard",[Student::class,"dashboard"]);
$router->get("student/complaint",[Student::class,"complaint"]);
$router->get("student/chat",[Student::class,"chat"]);
$router->get("student/notification",[Student::class,"notification"]);
$router->get("student/history",[Student::class,"history"]);
$router->post("student/history",[History::class,"add_history"]);
$router->get("student/settings",[Student::class,"settings"]);
$router->post("student/complaint",[Student::class,"make_complaint"]);

//end of student routes

//connection routes
$router->get("open_connections/",[Connection::class,"open"]);
$router->get("create_connections/",[Connection::class,"create"]);
$router->get("create_connection/",[User::class,"connect"]);
$router->post("connect/",[Connection::class,"add_connection"]);
//admin routes
$router->get("admin/",[Admin::class,"index"]);
$router->get("admin/dashboard",[Admin::class,"dashboard"]);
$router->get("admin/complaint",[Admin::class,"complaint"]);
$router->get("admin/chat",[Admin::class,"chat"]);
//$router->get("admin/quick_chat",[Admin::class,"quick_chat"]);
$router->get("admin/notification",[Admin::class,"notification"]);
$router->get("admin/history",[Admin::class,"history"]);
$router->get("admin/settings",[Admin::class,"settings"]);

//end of admin routes

//user routes
$router->get("get_chat/",[User::class,"get_messages"]);
$router->get("logout/",[User::class,"log_out"]);
$router->post("login/",[User::class,"login"]);
$router->post("create_account/",[User::class,"create"]);
$router->get("user_data/image",[User::class,"get_user_image"]);
$router->get("user_data/chat",[User::class,"get_user_chat"]);
$router->get("user_data/fullname",[User::class,"get_fullname"]);
$router->get("user_data/status",[User::class,"get_user_status"]);
//end of user routes

//complaint routes

$router->get("complaint/files",[Complaint::class,"get_files"]);
$router->post("complaint/files",[Complaint::class,"upload_files"]);
$router->delete("complaint/delete_file",[Complaint::class,"delete_file"]);
$router->get("complaint/delete_files",[Complaint::class,"delete_files"]);
$router->post("complaint/update",[Complaint::class,"update"]);


//history
$router->post("history/search",[History::class,"search"]);
$router->delete("history/clear",[History::class,"delete"]);

//notification
$router->delete("notification/delete",[Notification::class,"delete"]);
$router->update("notification/update",[Notification::class,"update"]);

echo (new App)->run($router, [$_SERVER["REQUEST_URI"],$_SERVER["REQUEST_METHOD"]]);

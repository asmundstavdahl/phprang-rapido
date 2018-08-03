<?php

use \Rapd\Router;

Router::get("list", "/", [TodoController::class, "list"]);

Router::get("new", "/new", [TodoController::class, "new"]);
Router::post("new_submit", "/new", [TodoController::class, "newSubmit"]);

Router::get("edit", "/(\d+)", [TodoController::class, "edit"]);
Router::post("edit_submit", "/(\d+)", [TodoController::class, "editSubmit"]);

Router::get("done", "/(\d+)/done", [TodoController::class, "done"]);

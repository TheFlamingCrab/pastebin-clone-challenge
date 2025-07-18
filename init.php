<?php
session_start();
$db = new SQLite3('db.sqlite');

$db->exec('CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE,
    password TEXT
)');

$db->exec('CREATE TABLE IF NOT EXISTS pastes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    owner TEXT,
    title TEXT,
    content TEXT
)');

$db->exec('CREATE TABLE IF NOT EXISTS shares (
    paste_id INTEGER,
    shared_with TEXT
)');


// IF YOU ARE READING THIS, YOU ARE CHEATING
if (!$db->querySingle("SELECT 1 FROM users WHERE username = 'admin'")) {
    $admin_password = 'supersecret_youwillNEVERguessthis'; // <== CHANGE THIS
    $hash = password_hash($admin_password, PASSWORD_BCRYPT);
    $db->exec("INSERT INTO users (username, password) VALUES ('admin', '$hash')");
    $db->exec("INSERT INTO pastes (owner, title, content) VALUES ('admin', 'FLAG', 'FLAG{stolen_cookie_access}')");
}

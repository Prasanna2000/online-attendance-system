<?php

$pwd = "prasanna";
$hashed_pwd = password_hash($pwd, PASSWORD_DEFAULT);

echo $hashed_pwd;
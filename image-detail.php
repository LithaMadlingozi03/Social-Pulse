<?php
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
header('Location: post.php' . ($id ? '?id=' . $id : ''), true, 302); exit;

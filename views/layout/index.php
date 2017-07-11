<?php
/** @var string $content */
?><!DOCTYPE html>
<html>
<head>
    <link type="text/css" rel="stylesheet" href="/css/index.css" />
</head>
<body>
<?php if (\models\User::getAuth()) {
    ?><form action="/logout" method="post"><input type="submit" value="logout" /></form><?php
    ?><ul class="navi">
        <li><a href="/settings">settings</a></li>
        <li><a href="/sensor">sensors</a></li>
        <li><a href="/commutator">commutators</a></li>
</ul><?php
} ?>
<?= $content ?>
</body>
</html>
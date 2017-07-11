<?php
?>
<div class="auth-form">
    <?php if (isset($error)) { ?>
        <div class="error"><?= $error ?></div><?php } ?>
    <form action="/" method="post">
        <input type="text" name="email" placeholder="email"/>
        <input type="password" name="password" placeholder="password"/>
        <input type="submit" value="login">
    </form>
</div>
<!DOCTYPE html>
<html lang="pl">

<?php include_once("includes/head.inc.php"); ?>

<body>
    <?php include_once("includes/nav.inc.php"); ?>
    <div class="gallery-segment-container">
        <section>
            <span class="data_container">Zalogowano do konta o login:</h1>
                <hr><br>
                <h1> <?php echo ('<span class="data_container">');
                echo ($user_login);
                echo ("</span>"); ?></h1>
                <form method="post">
                    <a href="/home"> <input class="submit-button" type="submit" value="Log out" name="submit"> </a>
                </form>
        </section>
    </div>
    <?php include "includes/footer.inc.php"; ?>
</body>

</html>
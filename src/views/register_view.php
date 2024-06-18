<!DOCTYPE html>
<html lang="en">

<?php include('includes/head.inc.php') ?>

<body>
    <div id="root">
        <?php include('includes/nav.inc.php') ?>
        <div class="gallery-segment-container">
            <div class="form-image-container">
                <form method="post" enctype="multipart/form-data">
                    <input class="text-input" type="text" name="email" placeholder="Email" />
                    <input class="text-input" type="text" name="login" placeholder="Login" />
                    <input class="text-input" type="password" name="pas" placeholder="Hasło" />
                    <input class="text-input" type="password" name="re_pas" placeholder="Hasło" />
                    <input class="submit-button" type="submit" value="submit" />
                </form>
                <?php include('partial/messages_view.php') ?>
            </div>
        </div>
    </div>
</body>

</html>
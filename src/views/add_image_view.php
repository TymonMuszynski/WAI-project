<!DOCTYPE html>
<html lang="en">

<?php include('includes/head.inc.php') ?>

<body>
    <div id="root">
        <?php include('includes/nav.inc.php') ?>
        <div class="gallery-segment-container">
            <div class="form-image-container">
                <form method="post" enctype="multipart/form-data">
                    <input class="text-input" type="text" name="text" placeholder="text" />
                    <input class="text-input" type="text" name="author" placeholder="author" />
                    <input class="text-input" type="text" name="watermark" placeholder="WaterMark" />
                    <input class="file-button" type="file" name="image" />
                    <input class="submit-button" type="submit" value="submit" />
                </form>
                <?php include('partial/messages_view.php') ?>
            </div>
        </div>
    </div>
</body>

</html>
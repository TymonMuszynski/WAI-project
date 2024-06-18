<nav>
    <a href="/">
        <div class="logo-container"><span>Tymon Muszyński</span></div>
    </a>
    <ul>
        <li><a href="/"><span>Home</span> </a></li>
        <li><a href="/gallery?page=1"><span>Galeria</span></a></li>
        <?php
        if (empty($_SESSION['user_id'])) {
            echo ('<li><a href="/login"><span>Sign in</span></a></li>');
            echo (' <li><a href="/register"><span>Register</span></a></li>');
        } else {
            echo ('<li><a href="/logged"><span>Account</span></a></li>');
        }
        ?>
    </ul>
    <div class="line"></div>
</nav>
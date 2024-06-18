<?php
if (!empty($alerts)) {
    foreach ($alerts as $alert) {
        $message = $alert['message'];
        echo ("<span>$message</span>");
    }
}
<?php

namespace App\Views;

class Layout {
    public static function header($title = "My school") {
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>{$title}</title>
            <link rel="stylesheet" href="/css/style.css">
        </head>
        <body>
        HTML;

        self::navbar(); // Call navbar at the top of the page
        echo '<div class="container">';
    }

    public static function navbar() {
        echo <<<HTML
        <nav>
            <ul>
                <li><a href="/">Kezdőlap</a></li>
                <li><a href="/subjects">Tantárgyak</a></li>
            </ul>
        </nav>
        HTML;
    }

    public static function sidebar() {
        echo <<<HTML
        <aside>
            <h3>Sidebar</h3>
            <ul>
                <li><a href="#">Dashboard</a></li>
                <li><a href="#">Profile</a></li>
                <li><a href="#">Settings</a></li>
            </ul>
        </aside>
        HTML;
    }

    public static function footer() {
        echo <<<HTML
        </div> <!-- Closing container -->
            <footer> 
                <hr>
                <p>2025 &copy; Kovács László</p>
            </footer>
        </body>
        </html>
        HTML;
    }
}

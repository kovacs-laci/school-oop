<?php

namespace App\Views;

class View {
    public static function render($view, $data = []) {
        extract($data); // Extract variables from the array
        $viewFile = __DIR__ . "/$view.php";
        if (file_exists($viewFile)) {
            Layout::header($title ?? 'My title');
//            Layout::sidebar();
            require_once $viewFile;
            Layout::footer(); // Call Layout::footer()
        } else {
            Display::message("View '$viewFile' not found.", 'error');
            die();
        }
    }
}

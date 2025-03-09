<?php
namespace App\Routing;
use App\Controllers\HomeController;
use App\Controllers\SubjectController;
use App\Models\Subject;
use App\Views\Display;

class Router {
//    protected $routes = [];

//    function __construct() {
//
//    }

    private function handleMessages(): void
    {
        if (isset($_SESSION['success_message'])) {
            Display::message($_SESSION['success_message'], 'success');
            unset($_SESSION['success_message']); // Remove message after displaying
        }
        if (isset($_SESSION['warning_message'])) {
            Display::message($_SESSION['warning_message'], 'warning');
            unset($_SESSION['warning_message']);
        }
    }

//    public function add($method, $uri, $controller) {
//        $this->routes[strtoupper($method)][$uri] = $controller;
//    }

//    public function dispatch($method, $uri) {
//        $method = strtoupper($method);
//
//        if (isset($this->routes[$method][$uri])) {
//            return $this->routes[$method][$uri];
//        } else {
//            // Handle 404 Not Found
//            return function() {
//                echo "404 Not Found";
//            };
//        }
//    }

    public function handle() {
        $this->handleMessages();
        $method = strtoupper($_SERVER['REQUEST_METHOD']);
        $requestUri = $_SERVER['REQUEST_URI'];

        // Check if the method is overridden by the `_method` field
        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }

        switch ($method) {
            case 'GET':
                $this->handleGetRequests($requestUri);
                break;
            case 'POST':
                $this->handlePostRequests($requestUri);
                break;
            case 'PATCH':
                $this->handlePatchRequests($requestUri);
                break;
            case 'DELETE':
                $this->handleDeleteRequests($requestUri);
                break;
            default:
                // Handle unsupported methods or return a 405 Method Not Allowed
                header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
                echo "405 Method Not Allowed";
        }
    }


    private function handleGetRequests(mixed $requestUri)
    {
        switch ($requestUri) {
            case '/':
                HomeController::index();
                return;
            case '/subjects':
                $subjectController = new SubjectController(new Subject());
                $subjectController->index();
                break;
            default:
                // 404

        }
    }

    private function handlePostRequests(mixed $requestUri)
    {
        $data = $this->filterPostData($_POST);
        $id = $data['id'] ?? null;

        switch ($requestUri) {
            case '/subjects':
                if (!empty($data)) {
                    $subjectController = new SubjectController(new Subject());
                    $subjectController->save($data);
                }
                break;
            case '/subjects/create':
                $subjectController =  new SubjectController(new Subject());
                $subjectController->create();
                break;
            case '/subjects/edit':
                $subjectController =  new SubjectController(new Subject());
                $subjectController->edit($id);
                break;
        }
    }

    private function handlePatchRequests(mixed $requestUri) {
        $data = $this->filterPostData($_POST);
        switch ($requestUri) {
            case '/subjects':
                $id = $data['id'] ?? null;
                $subjectController =  new SubjectController(new Subject());
                $subjectController->update($id, $data);
                break;

            default:
                echo "404 Not Found";
        }
    }

    private function handleDeleteRequests(mixed $requestUri) {
        $data = $this->filterPostData($_POST);
        switch ($requestUri) {
            case '/subjects':
                $subjectController =  new SubjectController(new Subject());
                $subjectController->delete((int) $data['id']);
                break;
        }
    }

    private function filterPostData($data)
    {
        if (empty($data)) {
            return $data;
        }
        $filter = array_flip(['_method', 'submit', 'btn-del', 'btn-save', 'btn-edit', 'btn-plus', 'btn-update']);
        foreach ($data as $key => $value) {
            if (isset($filter[$key])) {
                unset($data[$key]);
            }
        }

        return $data;
    }

}

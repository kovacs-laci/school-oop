<?php
namespace App\Controllers;
use App\Models\Subject;
use App\Views\Display;

class SubjectController extends Controller {

    public function __construct()
    {
        $subject = new Subject();
        parent::__construct($subject);
    }

    public function index(): void
    {
        $subjects = $this->model->all(['order_by' => ['name'], 'direction' => ['ASC']]);
        $this->render('subjects/index', ['subjects' => $subjects]);
    }

    public function create(): void
    {
        $this->render('subjects/create');
    }
    public function edit(int $id): void
    {
        $subject = $this->model->find($id);
        if (!$subject) {
            // Handle invalid ID gracefully
            $_SESSION['warning_message'] = "A tantárgy a megadott azonosítóval: $id nem található.";
            $this->redirect('/subjects');
        }
        $this->render('subjects/edit', ['subject' => $subject]);
    }

    public function save(array $data): void
    {
        if (empty($data['name'])) {
            $_SESSION['warning_message'] = "A tantárgy neve kötelező mező.";
            $this->redirect('/subjects/create'); // Redirect if input is invalid
        }
        // Use the existing model instance
        $this->model->name = $data['name'];
        $this->model->create();
        $this->redirect('/subjects');
    }

    public function update(int $id, array $data): void
    {
        $subject = $this->model->find($id);
        if (!$subject || empty($data['name'])) {
            // Handle invalid ID or data
            $this->redirect('/subjects');
        }
        $subject->name = $data['name'];
        $subject->update();
        $this->redirect('/subjects');
    }

    function show(int $id): void
    {
        $subject = $this->model->find($id);
        if (!$subject) {
            $_SESSION['warning_message'] = "A tantárgy a megadott azonosítóval: $id nem található.";
            $this->redirect('/subjects'); // Handle invalid ID
        }
        $this->render('subjects/show', ['subject' => $subject]);
    }

    function delete(int $id): void
    {
        $subject = $this->model->find($id);
        if ($subject) {
            $result = $subject->delete();
            if ($result) {
                $_SESSION['success_message'] = 'Sikeresen törölve';
            }
        }

        $this->redirect('/subjects'); // Redirect regardless of success
    }

}

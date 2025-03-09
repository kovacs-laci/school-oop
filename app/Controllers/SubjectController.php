<?php
namespace App\Controllers;
use App\Database\Repositories\Repository;
use App\Database\Repositories\SubjectRepository;
use App\Models\Model;
use App\Models\Subject;
use App\Views\View;

class SubjectController extends Controller {

//    public function __construct() {
//        $subjectRepository = new SubjectRepository();
//        parent::__construct(new Subject($subjectRepository));
//    }

    public function __construct(SubjectRepository $repository)
    {
        parent::__construct($repository);
    }

    public function index() {
        $subjects = $this->repository->getAll(['orderBy' => ['name'], 'order' => ['ASC']]);
        View::render('subjects/index', ['subjects' => $subjects]);
    }

    public function create() {
        View::render('subjects/create');
    }
    public function edit(int $id) {
        $subject = $this->repository->findOne($id);
        View::render('subjects/edit', ['subject' => $subject]);
    }

    public function save(array $data)
    {
        $subject = new Subject(new SubjectRepository());
        $subject->name = $data['name'];
        $subject->save();
        header('Location: /subjects');
    }

    public function update(int $id, array $data)
    {
        $subject = $this->repository->findOne($id);
        $subject->name = $data['name'];
        $subject->save();
        header('Location: /subjects');
    }

//    public function add() {
//        if ($_SERVER["REQUEST_METHOD"] === "POST") {
//            $name = $_POST["name"] ?? '';
//            if (!empty($name)) {
//                $this->model->createSubject($name);
//                header("Location: /subjects/index");
//                exit;
//            }
//        }
//        View::render('subjects/add', ['title' => 'Add Subject']);
//    }
    function show(int $id)
    {
        // TODO: Implement show() method.
    }

    function delete(int $id)
    {
        $subject = $this->repository->findOne($id);
        $subject->delete();
        header('Location: /subjects');
    }

//    function add()
//    {
//        // TODO: Implement add() method.
//    }
//
//    function get()
//    {
//        // TODO: Implement get() method.
//    }
}

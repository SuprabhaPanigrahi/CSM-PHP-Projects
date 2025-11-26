<?php
// src/controllers/AdminController.php
class AdminController extends Controller {
    private $batchModel, $techModel, $questionModel, $attemptModel;
    public function __construct() {
        if (empty($_SESSION['admin'])) {
            $this->redirect('?r=auth/login');
        }
        $this->batchModel = new Batch();
        $this->techModel = new Technology();
        $this->questionModel = new Question();
        $this->attemptModel = new Attempt();
    }

    public function dashboard() {
        $batches = $this->batchModel->all();
        $techs = $this->techModel->all();
        $questions = $this->questionModel->all();
        $this->view('admin/dashboard', ['batches'=>$batches,'techs'=>$techs,'questions'=>$questions]);
    }

    public function batches() {
        if ($_SERVER['REQUEST_METHOD']==='POST' && !empty($_POST['name'])) {
            $this->batchModel->create($_POST['name']);
            $this->redirect('?r=admin/batches');
        }
        if (isset($_GET['delete'])) {
            $this->batchModel->delete((int)$_GET['delete']);
            $this->redirect('?r=admin/batches');
        }
        $this->view('admin/batches', ['batches'=>$this->batchModel->all()]);
    }

    public function techs() {
        if ($_SERVER['REQUEST_METHOD']==='POST' && !empty($_POST['name'])) {
            $this->techModel->create($_POST['name']);
            $this->redirect('?r=admin/techs');
        }
        if (isset($_GET['delete'])) {
            $this->techModel->delete((int)$_GET['delete']);
            $this->redirect('?r=admin/techs');
        }
        $this->view('admin/techs', ['techs'=>$this->techModel->all()]);
    }

    public function questions() {
        if ($_SERVER['REQUEST_METHOD']==='POST' && !empty($_POST['desc'])) {
            $data = [
                'desc'=>$_POST['desc'],
                'opt1'=>$_POST['opt1'],
                'opt2'=>$_POST['opt2'],
                'opt3'=>$_POST['opt3'],
                'opt4'=>$_POST['opt4'],
                'answer'=>$_POST['answer'],
                'batchID'=>$_POST['batchID'],
                'techID'=>$_POST['techID'],
            ];
            $this->questionModel->create($data);
            $this->redirect('?r=admin/questions');
        }
        $this->view('admin/questions', [
            'questions'=>$this->questionModel->all(),
            'batches'=>$this->batchModel->all(),
            'techs'=>$this->techModel->all()
        ]);
    }

    public function results() {
        $this->view('admin/results', ['results'=>$this->attemptModel->all()]);
    }
}

<?php
// src/controllers/GuestController.php
class GuestController extends Controller {
    private $guestModel, $batchModel, $techModel, $questionModel, $attemptModel, $attemptAnswerModel;
    public function __construct() {
        $this->guestModel = new GuestUser();
        $this->batchModel = new Batch();
        $this->techModel = new Technology();
        $this->questionModel = new Question();
        $this->attemptModel = new Attempt();
        $this->attemptAnswerModel = new AttemptAnswer();
    }

    public function email() {
        // shows email input page
        $this->view('guest/email', [
            'batches'=>$this->batchModel->all(),
            'techs'=>$this->techModel->all()
        ]);
    }

    // AJAX endpoint: check email
    public function checkEmail() {
        $email = $_POST['email'] ?? '';
        header('Content-Type: application/json');
        if (!$email) { echo json_encode(['ok'=>false]); return; }
        $row = $this->guestModel->findByEmail($email);
        if ($row) {
            echo json_encode(['ok'=>true,'exists'=>true,'data'=>$row]);
        } else {
            echo json_encode(['ok'=>true,'exists'=>false]);
        }
    }

    public function start() {
        if ($_SERVER['REQUEST_METHOD']!=='POST') $this->redirect('?r=guest/email');
        $email = $_POST['email'];
        $guest = $this->guestModel->findByEmail($email);
        if (!$guest) {
            // create
            $id = $this->guestModel->create($_POST);
            $guestID = $id;
        } else {
            $guestID = $guest['guestID'];
        }
        // load questions for batch & tech
        $batchID = (int)($_POST['batchID'] ?? $guest['batchID'] ?? 0);
        $techID = (int)($_POST['techID'] ?? $guest['techID'] ?? 0);
        $questions = $this->questionModel->forBatchTech($batchID, $techID);
        // save guest info in session for the exam
        $_SESSION['guest'] = ['guestID'=>$guestID,'email'=>$email,'batchID'=>$batchID,'techID'=>$techID];
        $this->view('guest/exam', ['questions'=>$questions, 'guestID'=>$guestID]);
    }

    public function submit() {
        if ($_SERVER['REQUEST_METHOD']!=='POST') $this->redirect('?r=guest/email');
        if (empty($_SESSION['guest'])) $this->redirect('?r=guest/email');
        $guest = $_SESSION['guest'];
        $answers = $_POST['answer'] ?? [];
        // fetch questions for evaluation
        $questions = $this->questionModel->forBatchTech($guest['batchID'], $guest['techID']);
        $total = count($questions);
        $score = 0;
        $attemptID = $this->attemptModel->create($guest['guestID'], 0, $total); // temp score 0
        foreach ($questions as $q) {
            $qid = $q['QnID'];
            $selected = isset($answers[$qid]) ? (int)$answers[$qid] : 0;
            $this->attemptAnswerModel->save($attemptID, $qid, $selected);
            if ($selected && (string)$selected === (string)$q['Answer']) {
                $score++;
            }
        }
        // update attempt with real score
        $stmt = Database::getInstance()->getConnection()->prepare("UPDATE attempt SET score=? WHERE attemptID=?");
        $stmt->bind_param('ii', $score, $attemptID);
        $stmt->execute();

        // show result
        $this->view('guest/result', ['score'=>$score,'total'=>$total]);
    }
}

<?php // src/views/guest/exam.php ?>
<h2>Exam</h2>
<form method="post" action="?r=guest/submit" id="examForm">
<?php if (empty($questions)): ?>
  <p>No questions found for selected batch/technology.</p>
<?php else: ?>
  <?php foreach($questions as $i => $q): ?>
    <div class="question" style="margin-bottom:15px;">
      <p><strong>Q<?= $i+1 ?>:</strong> <?= htmlspecialchars($q['Qn_desc']) ?></p>
      <ol type="1">
        <li><label><input type="radio" name="answer[<?= $q['QnID'] ?>]" value="1"> <?= htmlspecialchars($q['Opt_1']) ?></label></li>
        <li><label><input type="radio" name="answer[<?= $q['QnID'] ?>]" value="2"> <?= htmlspecialchars($q['Opt_2']) ?></label></li>
        <li><label><input type="radio" name="answer[<?= $q['QnID'] ?>]" value="3"> <?= htmlspecialchars($q['Opt_3']) ?></label></li>
        <li><label><input type="radio" name="answer[<?= $q['QnID'] ?>]" value="4"> <?= htmlspecialchars($q['Opt_4']) ?></label></li>
      </ol>
    </div>
  <?php endforeach; ?>

  <!-- Optional timer: simple JS timer (example 15 minutes). You can change duration. -->
  <div id="timer">Time left: <span id="time">15:00</span></div>
  <button type="submit">Submit Exam</button>
<?php endif; ?>
</form>

<script>
var totalSeconds = 15 * 60; // change as needed
function startTimer() {
  var el = $('#time');
  var t = setInterval(function(){
    if (totalSeconds <= 0) {
      clearInterval(t);
      alert('Time up! Submitting exam.');
      $('#examForm').submit();
      return;
    }
    totalSeconds--;
    var m = Math.floor(totalSeconds/60);
    var s = totalSeconds%60;
    el.text((''+m).padStart(2,'0')+':'+(''+s).padStart(2,'0'));
  }, 1000);
}
$(function(){ startTimer(); });
</script>

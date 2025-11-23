<?php // src/views/admin/questions.php ?>
<h2>Questions</h2>
<form method="post" action="?r=admin/questions">
  <textarea name="desc" placeholder="Question description" required></textarea><br>
  <input name="opt1" placeholder="Option 1" required><br>
  <input name="opt2" placeholder="Option 2" required><br>
  <input name="opt3" placeholder="Option 3" required><br>
  <input name="opt4" placeholder="Option 4" required><br>
  <label>Answer (1-4): <input name="answer" type="number" min="1" max="4" required></label><br>
  <label>Batch:
    <select name="batchID" required>
      <?php foreach($batches as $b): ?><option value="<?= $b['BatchID'] ?>"><?= htmlspecialchars($b['BatchName']) ?></option><?php endforeach; ?>
    </select>
  </label><br>
  <label>Tech:
    <select name="techID" required>
      <?php foreach($techs as $t): ?><option value="<?= $t['TechID'] ?>"><?= htmlspecialchars($t['TechName']) ?></option><?php endforeach; ?>
    </select>
  </label><br>
  <button type="submit">Add Question</button>
</form>

<h3>Existing</h3>
<table border="1" cellpadding="6">
<tr><th>ID</th><th>Question</th><th>Opts</th><th>Ans</th><th>Batch</th><th>Tech</th></tr>
<?php foreach($questions as $q): ?>
  <tr>
    <td><?= $q['QnID'] ?></td>
    <td><?= htmlspecialchars($q['Qn_desc']) ?></td>
    <td><?= htmlspecialchars($q['Opt_1']) ?> | <?= htmlspecialchars($q['Opt_2']) ?> | <?= htmlspecialchars($q['Opt_3']) ?> | <?= htmlspecialchars($q['Opt_4']) ?></td>
    <td><?= htmlspecialchars($q['Answer']) ?></td>
    <td><?= htmlspecialchars($q['BatchName']) ?></td>
    <td><?= htmlspecialchars($q['TechName']) ?></td>
  </tr>
<?php endforeach; ?>
</table>

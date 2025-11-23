<?php // src/views/guest/email.php ?>
<h2>Start Exam (Guest)</h2>
<form id="guestForm" method="post" action="?r=guest/start">
  <label>Email: <input type="email" name="email" id="email" required></label>
  <button type="button" id="checkBtn">Check</button>
  <div id="guestDetails" style="display:none;margin-top:10px;">
    <label>Name: <input type="text" name="name" id="name"></label><br>
    <label>Phone: <input type="text" name="phone" id="phone"></label><br>
    <label>Gender:
      <select name="gender" id="gender">
        <option value="male">Male</option><option value="female">Female</option><option value="other">Other</option>
      </select>
    </label><br>
    <label>Batch:
      <select name="batchID" id="batchID">
        <?php foreach($batches as $b): ?><option value="<?= $b['BatchID'] ?>"><?= htmlspecialchars($b['BatchName']) ?></option><?php endforeach; ?>
      </select>
    </label><br>
    <label>Technology:
      <select name="techID" id="techID">
        <?php foreach($techs as $t): ?><option value="<?= $t['TechID'] ?>"><?= htmlspecialchars($t['TechName']) ?></option><?php endforeach; ?>
      </select>
    </label><br>
    <button type="submit">Start Exam</button>
  </div>
</form>

<script>
$('#checkBtn').on('click', function(){
  var email = $('#email').val().trim();
  if (!email) { alert('Enter email'); return; }
  $.post('?r=guest/checkEmail',{email:email}, function(res){
    if (!res.ok) { alert('Error'); return; }
    $('#guestDetails').show();
    if (res.exists) {
      // fill and readonly fields
      $('#name').val(res.data.name).prop('readonly', true);
      $('#phone').val(res.data.phone).prop('readonly', true);
      $('#gender').val(res.data.gender).prop('disabled', true);
      $('#batchID').val(res.data.batchID).prop('disabled', true);
      $('#techID').val(res.data.techID).prop('disabled', true);
    } else {
      $('#name').val('').prop('readonly', false);
      $('#phone').val('').prop('readonly', false);
      $('#gender').val('male').prop('disabled', false);
      $('#batchID').prop('disabled', false);
      $('#techID').prop('disabled', false);
    }
  }, 'json');
});
</script>

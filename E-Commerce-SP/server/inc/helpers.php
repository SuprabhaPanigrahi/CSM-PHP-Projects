 <?php 

function json_ok($data = []) { echo json_encode(['ok'=>true] + 
$data); exit; } 

function json_fail($msg, $code=400){ http_response_code($code); echo 
json_encode(['ok'=>false,'error'=>$msg]); exit; } 

function json_error($msg, $code=500){
    http_response_code($code);
    echo json_encode(array_merge(['status' => 'error'],['message' => $msg]));
}
function safe_name($name){ return preg_replace('/[^a-z0-9_.
]/i','_', $name); }

?>
<?php 

function require_login() { 
  if (empty($_SESSION['user_id'])) { 
    http_response_code(401); 
    echo json_encode(['ok'=>false,'error'=>'unauthorized']); 
    exit; 
  } 
} 
function is_logged_in(){ 
  return !empty($_SESSION['user_id']); 
}

?>
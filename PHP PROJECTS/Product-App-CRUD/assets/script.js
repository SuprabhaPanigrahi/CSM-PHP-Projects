function validate() {
 
  let username = document.getElementById("username").value;
  let password = document.getElementById("password").value;
  let errorMessage = "";
  if (username === "") {
    alert("Username cannot be empty.");
    document.getElementById("username").focus();
    return false;
  }
  if(password===""){
    alert("Password cannot be empty.");
    document.getElementById("password").focus();
    return false;
  }
  return true;
}
<?php

    session_start();
    include __DIR__ . '/database/connection.php';
    //Check if user is logged in ,if not redirect to login page
    $selectedCategoryId=0;
    $selectedProductId=0;
    $price=0;
    $quantity=0;
    if(!isset($_SESSION['username'])) {
        header("Location: index.php");
        exit();
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $selectedCategoryId = $_POST['category'] ?? 0;
        $selectedProductId = $_POST['product'] ?? 0;
        $price = $_POST['price'] ?? 0;
        $quantity = $_POST['quantity'] ?? 0;

        $errorMessage="";

        if($selectedCategoryId >0 && $selectedProductId >0 && $price >0 && $quantity >0 ){
          print_r($_POST);
          // Process the form data (e.g., save to database)
          $sql = "INSERT INTO product_details (category_id, product_id, price, quantity) VALUES ('$selectedCategoryId', '$selectedProductId', '$price', '$quantity')";
          if (mysqli_query($conn, $sql)) {
              // Success
              echo "New record created successfully";
          } else {
              // Error
              echo "Error: " . $sql . "<br>" . mysqli_error($conn);
          } 
        }else{
          $errorMessage = "Please fill all the fields correctly.";
        }
          


    }

    if(isset($_GET['delete_id'])) {
        $deleteId = $_GET['delete_id'];
        $sql = "DELETE FROM product_details WHERE id = $deleteId";
        mysqli_query($conn, $sql);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    if(isset($_GET['edit_id'])) {
        $editId = $_GET['edit_id'];
        // Fetch existing data for the record
        $sql = "SELECT * FROM product_details WHERE id = $editId";
        $result = mysqli_query($conn, $sql);
        $record = mysqli_fetch_assoc($result);
        $selectedCategoryId = $record['category_id'];
        $selectedProductId = $record['product_id'];
        $price = $record['price'];
        $quantity = $record['quantity'];
        // You can use $record to pre-fill the form for editing
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <h1>Manage Products</h1>
  <h4>Welcome Back !!! <?php echo $_SESSION['username']; ?></h4>
  <a href="logout.php">Logout</a>
  <hr/>
  <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <?php
      if(!empty($errorMessage)){
        echo "<p style='color:red;'>$errorMessage</p>";
      }
      ?>
    <label>Select Category</label>
    <select  name="category" onchange="submitForm()" >
      <option>--SELECT---</option>
      <?php
       $sql = "SELECT * FROM categories";
       $result = mysqli_query($conn, $sql);
       if(mysqli_num_rows($result) > 0) {
          while($row = mysqli_fetch_assoc($result)) {
            if($selectedCategoryId == $row['id']){
              echo "<option value='{$row['id']}' selected >{$row['name']}</option>";
            }else{
              echo "<option value='{$row['id']}'>{$row['name']}</option>";
            }
          }
       }  
      ?>
      <!-- <option>Electronics</option>
      <option>Clothing</option>
      <option>Books</option>
      <option>Home & Kitchen</option> -->
    </select>
    <br/>
    <label>Select Products</label>
    <select name="product">
      <option>--SELECT---</option>
      <?php
         if($selectedCategoryId>0){
           $sql = "SELECT * FROM products WHERE category_id = $selectedCategoryId";
           $result = mysqli_query($conn, $sql);
           if(mysqli_num_rows($result)>0){
            while($row = mysqli_fetch_assoc($result)){
              if($selectedProductId == $row['id']){
                echo "<option value='{$row['id']}' selected >{$row['product_name']}</option>";
              }else{
                echo "<option value='{$row['id']}'>{$row['product_name']}</option>";
              }
            }
           }
         }
      ?>
      <!-- <option>TV</option>
      <option>Refrigerator</option>
      <option>Washing Machine</option>
      <option>Microwave</option>
      <option>Smartphone</option> -->
    </select>
    <br/>
    <label>Enter Price</label>
    <input type="text" name="price" value="<?php echo $price;?>" />
    <br/>
    <label>Enter Quantity</label>
    <input type="text" name="quantity" value="<?php echo $quantity;?>" />
    <br/>
    <button type="submit">Submit</button>
  </form>
  <hr/>
  <h1>Product Details</h1>
  <table border=1 cellpadding=10px>
     <tr>
      <th>Id</th>
      <th>Category</th>
      <th>Product</th>
      <th>Price</th>
      <th>Quantity</th>
      <th>Action</th>
     </tr>
     <?php
       $sql = "SELECT pd.id, c.name AS category_name, p.product_name, pd.price, pd.quantity 
               FROM product_details pd
               INNER JOIN categories c ON pd.category_id = c.id
               INNER JOIN products p ON pd.product_id = p.id";
      $result = mysqli_query($conn, $sql);
      if(mysqli_num_rows($result)>0){
           while($row=mysqli_fetch_assoc($result)){
              echo "<tr>
                      <td>".$row['id']."</td>
                      <td>".$row['category_name']."</td>
                      <td>".$row['product_name']."</td>
                      <td>$".$row['price']."</td>
                      <td>".$row['quantity']."</td>
                      <td><a href='$_SERVER[PHP_SELF]?edit_id={$row['id']}'>Edit</a> | <a href='$_SERVER[PHP_SELF]?delete_id={$row['id']}'>Delete</a></td>
                    </tr>";
           }
      }else{
        echo "<tr><td colspan='6'>No records found.</td></tr>";
      }
     ?>
    
  </table>
  <script>
    function submitForm(){
      document.querySelector('form').submit();
    }
  </script>
</body>
</html>
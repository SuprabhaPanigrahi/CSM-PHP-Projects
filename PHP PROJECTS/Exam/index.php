<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2 class="mb-4">Place an Order</h2>
  <form id="orderForm" class="mb-4">
    <input type="hidden" id="orderId" name="orderId"> 

    <!-- Category -->
    <div class="mb-3">
      <label class="form-label">Category:</label><br>
      <div class="form-check form-check-inline">
        <input type="radio" class="form-check-input" name="category" id="cat1" value="Electronics">
        <label class="form-check-label" for="cat1">Electronics</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="category" id="cat2" value="Clothing">
        <label class="form-check-label" for="cat2">Clothing</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="category" id="cat3" value="Consumables">
        <label class="form-check-label" for="cat3">Consumables</label>
      </div>
    </div>

    <div class="mb-3">
      <label for="product" class="form-label">Product:</label>
      <select class="form-select" id="product" name="product">
        <option value="">Select Product</option>
      </select>
    </div>

    <div class="mb-3">
      <label for="quantity" class="form-label">Quantity:</label>
      <input type="number" class="form-control" id="quantity" name="quantity" value="1">
    </div>

    <button type="submit" class="btn btn-primary" id="submitBtn">Add Order</button>
    <button type="button" class="btn btn-secondary" id="cancelUpdate" style="display:none;">Cancel Update</button>
  </form>

  <!-- Orders Table -->
  <h3>Existing Orders</h3>
  <table class="table table-bordered" id="ordersTable">
    <thead class="table-light">
      <tr>
        <th>#</th>
        <th>Category</th>
        <th>Product</th>
        <th>Quantity</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>

    </tbody>
  </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>

<script>
$(document).ready(function(){
  loadOrders();
  loadProducts();

  $('#orderForm').on('submit', function(e){
    e.preventDefault();
    let orderId = $('#orderId').val();
    if(orderId) {
      updateOrderSubmit(orderId);
    } else {
      addOrders();
    }
  });

  $('#cancelUpdate').on('click', function(){
    $('#orderForm')[0].reset();
    $('#orderId').val('');
    $('#submitBtn').text('Add Order');
    $(this).hide();
  });
});

// Load Orders
function loadOrders(){
  $.ajax({
    url : 'process/fetch_orders.php',
    method : 'GET',
    success : function(response){
      let orders = JSON.parse(response);
      let str = '';
      orders.forEach((row, index) => {
        str += `
          <tr>
            <td>${index + 1}</td>
            <td>${row.category}</td>
            <td>${row.product}</td>
            <td>${row.quantity}</td>
            <td>
              <button class="btn btn-success btn-sm me-2" onclick="editOrder(${row.orderId})">Update</button>
              <button class="btn btn-danger btn-sm" onclick="deleteOrder(${row.orderId})">Delete</button>
            </td>
          </tr>`;
      });
      $('tbody').html(str);
    },
    error : function(){ console.log("Error fetching orders"); }
  });
}

// Load Products
function loadProducts(){
  $.ajax({
    url : 'process/fetch_products.php',
    method : 'GET',
    success : function(response){
      let products = JSON.parse(response);
      let options = '<option value="">~~Select Product~~</option>';
      products.forEach(row => {
        options += `<option value="${row.productId}">${row.productName}</option>`;
      });
      $('#product').html(options);
    },
    error: function(){ console.log("Error fetching products"); }
  });
}

// Add Orders
function addOrders(){
  let category = $('input[name="category"]:checked').val();
  let product = $('#product').val();
  let quantity = $('#quantity').val();

  if(!category || !product || quantity <= 0){
    alert("Please fill all fields correctly.");
    return;
  }

  $.ajax({
    url: 'process/add_orders.php',
    method: 'POST',
    data: { category, product, quantity },
    success: function(response){
      alert(response);
      loadOrders();
      $('#orderForm')[0].reset();
    },
    error: function(){ console.log("Error adding order"); }
  });
}

function editOrder(id){
  $.ajax({
    url: 'process/fetch_orders_id.php?orderId=' + id,
    method: 'GET',
    success: function(response){
      let data = JSON.parse(response);
      $('#orderId').val(data.orderId);
      $(`input[name="category"][value="${data.category}"]`).prop('checked', true);
      $('#product').val(data.productId);
      $('#quantity').val(data.quantity);
      $('#submitBtn').text('Update Order');
      $('#cancelUpdate').show();
    },
    error: function(){ console.log("Error fetching order"); }
  });
}

// Update Order
function updateOrderSubmit(orderId){
  let category = $('input[name="category"]:checked').val();
  let product = $('#product').val();
  let quantity = $('#quantity').val();

  if(!category || !product || quantity <= 0){
    alert("Please fill all fields correctly.");
    return;
  }

  $.ajax({
    url: 'process/update_orders.php',
    method: 'POST',
    data: { orderId, category, product, quantity },
    success: function(response){
      alert(response);
      loadOrders();
      $('#orderForm')[0].reset();
      $('#orderId').val('');
      $('#submitBtn').text('Add Order');
      $('#cancelUpdate').hide();
    },
    error: function(){ console.log("Error updating order"); }
  });
}


function deleteOrder(id){
  if(confirm("Are you sure you want to delete this order?")){
    $.ajax({
      url: 'process/delete_orders.php?orderId=' + id,
      method: 'GET',
      success: function(response){
        alert(response);
        loadOrders();
      },
      error: function(){ console.log("Error deleting order"); }
    });
  }
}
</script>
</body>
</html>

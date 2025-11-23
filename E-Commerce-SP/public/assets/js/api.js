// API Base URLs - Updated paths
const CUSTOMER_API = "../../../server/api/customers";
const CATEGORY_API = "../../../server/api/category";
const PRODUCT_API = "../../../server/api/products";

// ==================== CUSTOMER API FUNCTIONS ====================
export async function fetchCustomers() {
  try {
    const response = await fetch(`${CUSTOMER_API}/fetch-customer.php`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const data = await response.json();
    return Array.isArray(data) ? data : [];
  } catch (error) {
    console.error('Error fetching customers:', error);
    return [];
  }
}

export async function addCustomer(customerData) {
  try {
    const response = await fetch(`${CUSTOMER_API}/insert-customer.php`, {
      method: "POST",
      body: customerData,
    });
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    return await response.json();
  } catch (error) {
    console.error('Error adding customer:', error);
    return { success: false, message: error.message };
  }
}

export async function updateCustomer(customerData) {
  try {
    const response = await fetch(`${CUSTOMER_API}/update-customer.php`, {
      method: "POST",
      body: customerData,
    });
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    return await response.json();
  } catch (error) {
    console.error('Error updating customer:', error);
    return { success: false, message: error.message };
  }
}

export async function deleteCustomer(id) {
  try {
    const response = await fetch(`${CUSTOMER_API}/delete-customer.php`, {
      method: "POST",
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ id })
    });
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    return await response.json();
  } catch (error) {
    console.error('Error deleting customer:', error);
    return { success: false, message: error.message };
  }
}

// ==================== CATEGORY API FUNCTIONS ====================
export async function fetchCategories() {
  try {
    const response = await fetch(`${CATEGORY_API}/fetch-category.php`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const data = await response.json();
    return Array.isArray(data) ? data : [];
  } catch (error) {
    console.error('Error fetching categories:', error);
    return [];
  }
}

export async function addCategory(categoryData) {
  try {
    const response = await fetch(`${CATEGORY_API}/insert-category.php`, {
      method: "POST",
      body: categoryData,
    });
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    return await response.json();
  } catch (error) {
    console.error('Error adding category:', error);
    return { success: false, message: error.message };
  }
}

export async function updateCategory(categoryData) {
  try {
    const response = await fetch(`${CATEGORY_API}/update-category.php`, {
      method: "POST",
      body: categoryData,
    });
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    return await response.json();
  } catch (error) {
    console.error('Error updating category:', error);
    return { success: false, message: error.message };
  }
}

export async function deleteCategory(id) {
  try {
    const response = await fetch(`${CATEGORY_API}/delete-category.php`, {
      method: "POST",
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ id })
    });
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    return await response.json();
  } catch (error) {
    console.error('Error deleting category:', error);
    return { success: false, message: error.message };
  }
}

// ==================== PRODUCT API FUNCTIONS ====================
export async function fetchProducts() {
  try {
    const response = await fetch(`${PRODUCT_API}/fetch-product.php`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const data = await response.json();
    return Array.isArray(data) ? data : [];
  } catch (error) {
    console.error('Error fetching products:', error);
    return [];
  }
}

export async function addProduct(productData) {
  try {
    const response = await fetch(`${PRODUCT_API}/insert-product.php`, {
      method: "POST",
      body: productData,
    });
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    return await response.json();
  } catch (error) {
    console.error('Error adding product:', error);
    return { success: false, message: error.message };
  }
}

export async function updateProduct(productData) {
  try {
    const response = await fetch(`${PRODUCT_API}/update-product.php`, {
      method: "POST",
      body: productData,
    });
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    return await response.json();
  } catch (error) {
    console.error('Error updating product:', error);
    return { success: false, message: error.message };
  }
}

export async function deleteProduct(id) {
  try {
    console.log('🔄 API: Deleting product ID:', id);
    
    const response = await fetch(`${PRODUCT_API}/delete-product.php`, {
      method: "POST",
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ id: id })
    });

    // First get the raw response text
    const responseText = await response.text();
    console.log('📨 API Raw response:', responseText);

    // Try to parse as JSON
    let result;
    try {
      result = JSON.parse(responseText);
    } catch (parseError) {
      console.error('❌ API JSON Parse Error:', parseError);
      return { 
        success: false, 
        message: 'Invalid server response' 
      };
    }

    return result;

  } catch (error) {
    console.error('❌ API Network Error:', error);
    return { success: false, message: 'Network error: ' + error.message };
  }
}
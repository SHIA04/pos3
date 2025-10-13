<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale-1">
  <title>Order Management - Order Flow POS</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; color: #343a40; }
    .sidebar { background-color: #ffffff; color: #343a40; width: 250px; padding: 20px; min-height: 100vh; box-shadow: 0 0 20px rgba(0, 0, 0, 0.05); position: fixed; display: flex; flex-direction: column; }
    .sidebar .logo { font-size: 24px; font-weight: 700; color: rebeccapurple; text-align: center; margin-bottom: 30px; }
    .sidebar nav a { color: #6c757d; text-decoration: none; display: flex; align-items: center; padding: 12px 20px; border-radius: 8px; margin-bottom: 10px; transition: all 0.3s ease; font-weight: 500; }
    .sidebar nav a i { margin-right: 15px; font-size: 1.2rem; }
    .sidebar nav a:hover, .sidebar nav a.active { background-color: rebeccapurple; color: #ffffff; }
    .logout-btn { background-color: #f1f3f5; color: #6c757d; border: none; font-weight: 500; border-radius: 8px; padding: 12px 20px; text-decoration: none; display: flex; align-items: center; margin-top: auto; transition: all 0.3s ease; }
    .logout-btn:hover { background-color: #dc3545; color: #ffffff; }
    
    .content { margin-left: 250px; padding: 30px; }
    .page-header { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1.5rem; margin-bottom: 2.5rem; }
    .page-header .title h1 { font-size: 2.2rem; font-weight: 700; color: #343a40; }
    .btn-primary { background: rebeccapurple; border: none; padding: 0.6rem 1.25rem; font-weight: 500; border-radius: 0.75rem; }
    
    .nav-tabs { border-bottom: 2px solid #dee2e6; }
    .nav-tabs .nav-link.active { color: rebeccapurple; border-color: rebeccapurple; background-color: transparent; }

    .order-card { background-color: #fff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.06); padding: 15px; border-left: 4px solid #6c757d; }
    .order-card.status-processing { border-left-color: #ffc107; }
    .order-card.status-done { border-left-color: #198754; opacity: 0.8; }
    .order-id { font-weight: 700; color: rebeccapurple; }
    
    /* Order Builder Modal Styles */
    #menu-list-container { max-height: 300px; overflow-y: auto; }
    .menu-list-item { cursor: pointer; }
    .menu-list-item:hover { background-color: #f8f9fa; }
    #order-summary-list .list-group-item { display: flex; align-items: center; justify-content: space-between; }
    .quantity-controls { display: flex; align-items: center; gap: 0.5rem; }
    .quantity-controls .form-control { width: 60px; text-align: center; }
    #grand-total { font-size: 1.5rem; font-weight: 700; color: rebeccapurple; }

    @media (max-width: 992px) {
      .sidebar { display: none; }
      .content { margin-left: 0; }
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar d-none d-lg-flex">
  <div>
    <div class="logo">Staff Dashboard</div>
    <nav>
      <a href="#"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
      <a href="#"><i class="bi bi-journal-text"></i> Menu</a>
      <a href="#" class="active"><i class="bi bi-basket-fill"></i> Orders</a>
      <a href="#"><i class="bi bi-gear-fill"></i> Settings</a>
    </nav>
  </div>
  <a href="#" class="logout-btn"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="content">
  <div class="page-header">
    <div class="title"><h1>Order Management</h1></div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#orderModal">
        <i class="bi bi-plus-lg me-2"></i>Create New Order
    </button>
  </div>
  <!-- Tab Navigation and Content would go here as in the previous version -->
  <p class="text-center text-muted">Order board will be displayed here.</p>
</div>


<!-- Create Order Modal -->
<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="orderModalLabel">Create New Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="orderForm" action="#" method="post">
          <!-- Customer and Booking Info -->
          <div class="row g-3 mb-4">
            <div class="col-md-6">
              <label class="form-label">Customer Name</label>
              <input type="text" class="form-control" name="customer_name" required>
            </div>
            <div class="col-md-6 d-flex align-items-end">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="scheduleCheck">
                <label class="form-check-label" for="scheduleCheck">Schedule for later? (Booking)</label>
              </div>
            </div>
            <div class="col-12" id="scheduleFields" style="display: none;">
              <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Booking Date</label><input type="date" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">Booking Time</label><input type="time" class="form-control"></div>
              </div>
            </div>
          </div>
          
          <!-- Order Builder -->
          <div class="row g-4">
            <!-- Left: Available Menu Items -->
            <div class="col-md-5">
              <h6>Available Menu Items</h6>
              <input type="text" id="menu-search" class="form-control mb-2" placeholder="Search for an item...">
              <div id="menu-list-container" class="list-group">
                <!-- Menu items will be populated by JavaScript -->
              </div>
            </div>
            
            <!-- Right: Current Order -->
            <div class="col-md-7">
              <h6>Current Order</h6>
              <div id="order-summary-list" class="list-group">
                <!-- Selected items will appear here -->
                <div class="list-group-item text-center text-muted">Select items from the left.</div>
              </div>
              <div class="d-flex justify-content-end align-items-center mt-3">
                  <span class="fs-5 me-2">Grand Total:</span>
                  <span id="grand-total">₱0.00</span>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" form="orderForm" class="btn btn-primary">Place Order</button>
      </div>
    </div>
  </div>
</div>


<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- DUMMY DATA ---
    const menuItems = [
        { id: 1, name: 'Gourmet Burger', price: 250.00 },
        { id: 2, name: 'Lava Cake', price: 180.00 },
        { id: 3, name: 'Iced Macchiato', price: 150.00 },
        { id: 4, name: 'Carbonara', price: 280.00 },
        { id: 5, name: 'Caesar Salad', price: 220.00 },
        { id: 6, name: 'French Fries', price: 100.00 }
    ];

    // --- References to DOM Elements ---
    const scheduleCheck = document.getElementById('scheduleCheck');
    const scheduleFields = document.getElementById('scheduleFields');
    const menuListContainer = document.getElementById('menu-list-container');
    const orderSummaryList = document.getElementById('order-summary-list');
    const grandTotalDisplay = document.getElementById('grand-total');
    const menuSearch = document.getElementById('menu-search');
    
    // --- State Variable ---
    let cart = new Map(); // Use a Map to store cart items { id => {name, price, quantity} }

    // --- Functions ---
    // Function to render the available menu items
    function renderMenuList(filter = '') {
        menuListContainer.innerHTML = '';
        const filteredItems = menuItems.filter(item => item.name.toLowerCase().includes(filter.toLowerCase()));
        
        filteredItems.forEach(item => {
            const itemHTML = `
                <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center menu-list-item" 
                   data-id="${item.id}" data-name="${item.name}" data-price="${item.price}">
                    ${item.name}
                    <span class="badge bg-primary rounded-pill">₱${item.price.toFixed(2)}</span>
                </a>
            `;
            menuListContainer.insertAdjacentHTML('beforeend', itemHTML);
        });
    }

    // Function to render the current order summary
    function renderCart() {
        orderSummaryList.innerHTML = '';
        let grandTotal = 0;

        if (cart.size === 0) {
            orderSummaryList.innerHTML = '<div class="list-group-item text-center text-muted">Select items from the left.</div>';
            grandTotalDisplay.textContent = '₱0.00';
            return;
        }

        cart.forEach((item, id) => {
            const itemSubtotal = item.price * item.quantity;
            grandTotal += itemSubtotal;
            const itemHTML = `
                <div class="list-group-item">
                    <div class="fw-bold">${item.name}</div>
                    <div class="d-flex justify-content-between align-items-center mt-1">
                        <div class="quantity-controls">
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-id="${id}" data-action="decrease">-</button>
                            <input type="text" class="form-control form-control-sm" value="${item.quantity}" readonly>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-id="${id}" data-action="increase">+</button>
                        </div>
                        <span class="fw-bold">₱${itemSubtotal.toFixed(2)}</span>
                        <button type="button" class="btn-close" aria-label="Remove" data-id="${id}" data-action="remove"></button>
                    </div>
                </div>
            `;
            orderSummaryList.insertAdjacentHTML('beforeend', itemHTML);
        });

        grandTotalDisplay.textContent = '₱' + grandTotal.toFixed(2);
    }

    // --- Event Listeners ---
    // Show/hide booking fields
    scheduleCheck.addEventListener('change', function() {
        scheduleFields.style.display = this.checked ? 'block' : 'none';
    });

    // Add item to cart when clicked from menu list
    menuListContainer.addEventListener('click', function(e) {
        e.preventDefault();
        const itemElement = e.target.closest('.menu-list-item');
        if (!itemElement) return;

        const id = parseInt(itemElement.dataset.id);
        const name = itemElement.dataset.name;
        const price = parseFloat(itemElement.dataset.price);

        if (cart.has(id)) {
            cart.get(id).quantity++;
        } else {
            cart.set(id, { name, price, quantity: 1 });
        }
        renderCart();
    });

    // Handle quantity changes and item removal
    orderSummaryList.addEventListener('click', function(e) {
        const target = e.target;
        if (!target.dataset.id) return;

        const id = parseInt(target.dataset.id);
        const action = target.dataset.action;

        if (action === 'increase') {
            cart.get(id).quantity++;
        } else if (action === 'decrease') {
            if (cart.get(id).quantity > 1) {
                cart.get(id).quantity--;
            } else {
                cart.delete(id);
            }
        } else if (action === 'remove') {
            cart.delete(id);
        }
        renderCart();
    });
    
    // Filter menu list on search
    menuSearch.addEventListener('keyup', () => renderMenuList(menuSearch.value));

    // --- Initial Render ---
    renderMenuList();
});
</script>

</body>
</html>
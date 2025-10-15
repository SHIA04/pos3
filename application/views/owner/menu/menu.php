<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Menu Management - Order Flow POS</title>

  <!-- Google Fonts for a more modern look -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- AlertifyJS -->
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css"/>

  <style>
    /* --- Base Dashboard Styles --- */
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background-color: ivory;
      color: #593b8c;
    }
    .navbar {
      background-color: #2D1E64;
    }
    .sidebar {
      background-color: rebeccapurple;
      color: ivory;
      width: 240px;
      padding: 20px;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      box-shadow: 2px 0 15px rgba(0,0,0,0.1);
      position: fixed;
    }
    .sidebar h1 {
      font-size: 22px;
      margin-bottom: 30px;
      text-align: center;
      font-weight: 700;
    }
    .sidebar nav a {
      color: ivory;
      font-weight: 500;
      text-decoration: none;
      display: flex;
      align-items: center;
      padding: 12px 18px;
      border-radius: 8px;
      margin-bottom: 8px;
      transition: all 0.3s ease;
    }
    .sidebar nav a i {
        margin-right: 12px;
        font-size: 1.1rem;
    }
    .sidebar nav a:hover, .sidebar nav a.active {
      background-color: rgba(255, 255, 255, 0.2);
    }
    .logout-btn {
      background-color: transparent;
      border: 2px solid #C68EFD;
      color: #C68EFD;
      font-weight: bold;
      border-radius: 8px;
      padding: 10px 14px;
      text-decoration: none;
      text-align: center;
      margin-top: auto;
      transition: all 0.3s ease;
    }
    .logout-btn:hover {
      background-color: #C68EFD;
      color: rebeccapurple;
    }
    .content {
      margin-left: 240px;
      padding: 30px;
    }
    @media (max-width: 992px) {
      .sidebar { display: none; }
      .content { margin-left: 0; }
    }

    /* --- Styles Specific to Menu Page --- */
    .page-header {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }
    .page-header .title h1 {
        color: rebeccapurple;
        font-weight: 700;
        font-size: 2.5rem;
        margin: 0;
    }
    .page-header .title p {
        color: #8c7aa8;
        margin: 0;
    }
    .page-header .actions {
        display: flex;
        gap: 1rem;
    }
    .search-bar {
        position: relative;
    }
    .search-bar .form-control {
        padding-left: 2.5rem;
        border-radius: 0.75rem;
        border: 1px solid #e0e0e0;
    }
    .search-bar .bi-search {
        position: absolute;
        left: 0.8rem;
        top: 50%;
        transform: translateY(-50%);
        color: #aaa;
    }
    .btn-primary {
        background: rebeccapurple;
        border: none;
        padding: 0.6rem 1.25rem;
        font-weight: 500;
        border-radius: 0.75rem;
        transition: background-color 0.3s ease;
    }
    .btn-primary:hover {
        background-color: #5a3b9a;
    }
    .menu-card {
        background-color: #ffffff;
        border: 1px solid #f0f0f0;
        border-radius: 1rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .menu-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.1);
    }
    .card-img-container {
        height: 220px;
        overflow: hidden;
        position: relative;
    }
    .card-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    .menu-card:hover .card-img-container img {
        transform: scale(1.05);
    }
    .card-category {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background-color: rgba(90, 59, 154, 0.8);
        backdrop-filter: blur(5px);
        color: #fff;
        padding: 0.3rem 0.8rem;
        border-radius: 50rem;
        font-size: 0.75rem;
        font-weight: 500;
    }
    .card-body {
        padding: 1.5rem;
        flex-grow: 1;
    }
    .card-title-line {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    .card-title-line h5 {
        font-weight: 600;
        font-size: 1.2rem;
        color: rebeccapurple;
        margin-bottom: 0.5rem;
    }
    .card-price {
        font-size: 1.3rem;
        font-weight: 700;
        color: #3d3d3d;
        white-space: nowrap;
    }
    .card-text {
        color: #888;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
    }
    .card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
        background: none;
        border-top: 1px solid #f5f5f5;
        margin-top: auto;
    }
    .status-badge {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
    }
    .status-badge .dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }
    .status-badge.available { color: #198754; }
    .status-badge.available .dot { background-color: #198754; }
    .status-badge.outofstock { color: #dc3545; }
    .status-badge.outofstock .dot { background-color: #dc3545; }
    .card-actions {
        display: flex;
        gap: 0.5rem;
    }
    .card-actions .btn {
        background-color: #f5f5f5;
        color: #888;
        border: none;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }
    .card-actions .btn:hover {
        background-color: rebeccapurple;
        color: white;
    }
  </style>
</head>
<body>

<!-- Mobile Navbar -->
<nav class="navbar d-lg-none">
  <div class="container-fluid">
    <button class="btn btn-outline-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
      ☰ Menu
    </button>
    <h1 class="text-white ms-3 fs-5">Order Flow</h1>
  </div>
</nav>

<!-- Sidebar -->
<div class="sidebar d-none d-lg-flex flex-column">
  <h1><b>OWNER DASHBOARD</b></h1>
 <nav>
    <a href="<?php echo site_url('dashboard'); ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="<?php echo site_url('owner/staff'); ?>" ><i class="bi bi-people-fill"></i> Staff</a>
    <a href="<?php echo site_url('owner/menu'); ?>" class="active"><i class="bi bi-journal-text"></i> Menu</a>
    <a href="<?php echo site_url('owner/orders'); ?>"><i class="bi bi-basket"></i> Orders</a>
    <a href="<?php echo site_url('owner/inventory'); ?>" ><i class="bi bi-box-seam"></i> Inventory</a>
    <a href="<?php echo site_url('owner/sales'); ?>"><i class="bi bi-cash-stack"></i> Sales</a>
    <a href="<?php echo site_url('owner/settings'); ?>"><i class="bi bi-gear"></i> Settings</a>
  </nav>
  <a href="<?php echo site_url('auth/logout'); ?>" class="logout-btn mt-auto"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<!-- Offcanvas (for mobile) -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar" style="background-color:rebeccapurple;">
    <!-- Offcanvas content would be the same as the dashboard -->
</div>

<!-- Main Content -->
<div class="content">

    <!-- Page Header -->
    <div class="page-header">
        <div class="title">
            <h1>Manage Menu</h1>
            <p>Add, edit, and update your restaurant's offerings.</p>
        </div>
        <div class="actions">
            <div class="search-bar">
                <i class="bi bi-search"></i>
                <input type="text" class="form-control" placeholder="Search items...">
            </div>
            <button id="addNewItemBtn" class="btn btn-primary" type="button">
                <i class="bi bi-plus-lg me-2"></i>Add New Item
            </button>
        </div>
    </div>

    <!-- Menu Grid -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 row-cols-xxl-4 g-4" id="menu-grid">
        <?php if (!empty($menu_items) && is_array($menu_items)): ?>
            <?php foreach ($menu_items as $item): ?>
                <div class="col" data-menu-id="<?php echo $item['menu_id']; ?>">
                    <div class="menu-card">
                        <div class="card-img-container">
                            <?php if (!empty($item['image'])): ?>
                                <img src="<?php echo base_url(htmlspecialchars($item['image'])); ?>" alt="<?php echo htmlspecialchars($item['item_name']); ?>">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/600x400?text=No+Image" alt="No Image">
                            <?php endif; ?>
                            <div class="card-category"><?php echo !empty($item['category']) ? htmlspecialchars($item['category']) : 'Uncategorized'; ?></div>
                        </div>
                        <div class="card-body">
                            <div class="card-title-line">
                                <h5><?php echo htmlspecialchars($item['item_name']); ?></h5>
                                <span class="card-price">₱<?php echo number_format($item['price'], 2); ?></span>
                            </div>
                            <p class="card-text"><?php echo !empty($item['description']) ? htmlspecialchars($item['description']) : ''; ?></p>
                        </div>
                        <div class="card-footer">
                            <?php
                                // Determine stock-based status. Prefer inventory stock_quantity when available.
                                $stock_qty = isset($item['stock_quantity']) ? (int)$item['stock_quantity'] : null;
                                if ($stock_qty === null) {
                                    // Fallback to existing status field
                                    $status_text = !empty($item['status']) ? $item['status'] : 'Unknown';
                                    $status_class = ($status_text === 'Available') ? 'available' : 'outofstock';
                                } else {
                                    if ($stock_qty > 0) { $status_text = 'In Stock'; $status_class = 'available'; }
                                    else { $status_text = 'Out of Stock'; $status_class = 'outofstock'; }
                                }
                            ?>
                            <div class="status-badge <?php echo $status_class; ?>">
                                <span class="dot"></span> <?php echo htmlspecialchars($status_text); ?>
                            </div>
                            <div class="card-actions">
                                <button class="btn btn-sm btn-edit" data-id="<?php echo $item['menu_id']; ?>"><i class="bi bi-pencil-fill"></i></button>
                                <button class="btn btn-sm btn-delete" data-id="<?php echo $item['menu_id']; ?>"><i class="bi bi-trash3-fill"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">No menu items found. Use "Add New Item" to create one.</div>
            </div>
        <?php endif; ?>
    </div>

  <footer class="mt-5 text-center text-muted">
    &copy; <?= date('Y'); ?> Order Flow Tagoloan POS – All Rights Reserved.
  </footer>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
<!-- Small helper to safely parse fetch responses and log non-JSON (HTML error pages) -->
<script>
    function parseFetchResponse(res){
        return res.text().then(function(text){
            var ct = res.headers.get('content-type') || '';
            if(ct.indexOf('application/json') !== -1){
                try{ return JSON.parse(text); }catch(e){
                    console.error('Failed to parse JSON response', e, text);
                    return {__parse_error:true, text: text, status: res.status};
                }
            }
            // not json (probably HTML error page)
            console.warn('Non-JSON response received', { status: res.status, text: text });
            return {__non_json:true, text: text, status: res.status};
        });
    }
</script>
<!-- Add Item Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addItemModalLabel">Add New Menu Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                    <form id="addItemForm" method="post" enctype="multipart/form-data" action="<?php echo site_url('owner/menu/add'); ?>">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" id="csrf_token_field">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="itemName" class="form-label">Item Name</label>
                        <input type="text" class="form-control" id="itemName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="itemPrice" class="form-label">Price</label>
                        <input type="number" step="0.01" class="form-control" id="itemPrice" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="itemCategory" class="form-label">Category</label>
                        <select class="form-control" id="itemCategory" name="category" required>
                            <option value="" disabled selected>Select category</option>
                            <option value="Waffles">Waffles</option>
                            <option value="Cakes">Cakes</option>
                            <option value="Beverages">Beverages</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="itemImage" class="form-label">Image (optional)</label>
                        <input type="file" class="form-control" id="itemImage" name="image">
                    </div>
                    <div class="mb-3">
                        <label for="itemDesc" class="form-label">Description</label>
                        <textarea class="form-control" id="itemDesc" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Show Bootstrap modal when button clicked
    (function(){
        var addBtn = document.getElementById('addNewItemBtn');
        var addModalEl = document.getElementById('addItemModal');
        if(addBtn && addModalEl){
            var addModal = new bootstrap.Modal(addModalEl);
            addBtn.addEventListener('click', function(){
                addModal.show();
            });
        }

                // Live search filter
                var searchInput = document.querySelector('.search-bar input');
                if(searchInput){
                    searchInput.addEventListener('keyup', function(){
                        var q = this.value.toLowerCase().trim();
                        var cards = document.querySelectorAll('#menu-grid .col');
                        cards.forEach(function(col){
                            var name = (col.querySelector('.card-title-line h5') || {textContent:''}).textContent.toLowerCase();
                            var cat = (col.querySelector('.card-category') || {textContent:''}).textContent.toLowerCase();
                            if(!q || name.indexOf(q) !== -1 || cat.indexOf(q) !== -1) col.style.display = '';
                            else col.style.display = 'none';
                        });
                    });
                }

            // Submit via AJAX (fetch) and append the new item to the grid
            var addForm = document.getElementById('addItemForm');
            var addModalEl = document.getElementById('addItemModal');
            if(addForm){
                addForm.addEventListener('submit', function(e){
                    e.preventDefault();
                    var formData = new FormData(addForm);

                    // Debug: log CSRF field before sending
                    try { const csrfEl = document.getElementById('csrf_token_field'); console.log('addForm CSRF', csrfEl ? { name: csrfEl.name, value: csrfEl.value } : 'missing'); } catch(e){}
                    fetch(addForm.action, {
                        method: 'POST',
                        body: formData,
                        credentials: 'include'
                    }).then(function(res){
                        return res.json();
                            }).then(function(json){
                        if(json.success){
                            // Close the modal
                            addModal.hide();

                            // Set a flag so after reload we can show a top alert
                            try { localStorage.setItem('menu_added_msg', 'Menu item added'); } catch(e){}
                            // Reload the page so new item (and image) comes from server
                                    if (json.inventory_created) {
                                        try { localStorage.setItem('menu_added_msg', 'Menu item added (inventory record created)'); } catch(e){}
                                    }
                                    window.location.reload();
                        } else {
                            // Update CSRF token if present on error too
                            if (json.csrf_token_name && json.csrf_hash) {
                                var csrfField = document.getElementById('csrf_token_field');
                                if (csrfField) {
                                    csrfField.name = json.csrf_token_name;
                                    csrfField.value = json.csrf_hash;
                                }
                            }
                            if(typeof alertify !== 'undefined') alertify.error(json.message || 'Failed to add item');
                        }
                    }).catch(function(err){
                        console.error(err);
                        if(typeof alertify !== 'undefined') alertify.error('Request failed');
                    });
                });
            }
    })();
</script>
<script>
    // If a menu add happened and set a flag, show a top-center alert after reload
    (function(){
        try{
            var msg = localStorage.getItem('menu_added_msg');
            if(msg){
                if(typeof alertify !== 'undefined'){
                    alertify.set('notifier','position','top-center');
                    alertify.success(msg);
                } else {
                    // fallback
                    alert(msg);
                }
                localStorage.removeItem('menu_added_msg');
            }
        }catch(e){}
    })();
</script>
<!-- Edit Item Modal -->
<div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editItemModalLabel">Edit Menu Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editItemForm" method="post" enctype="multipart/form-data" action="<?php echo site_url('owner/menu/update'); ?>">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" id="csrf_token_field_edit">
                <input type="hidden" name="menu_id" id="edit_menu_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editItemName" class="form-label">Item Name</label>
                        <input type="text" class="form-control" id="editItemName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editItemPrice" class="form-label">Price</label>
                        <input type="number" step="0.01" class="form-control" id="editItemPrice" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="editItemCategory" class="form-label">Category</label>
                        <select class="form-control" id="editItemCategory" name="category" required>
                            <option value="" disabled selected>Select category</option>
                            <option value="Waffles">Waffles</option>
                            <option value="Cakes">Cakes</option>
                            <option value="Beverages">Beverages</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editItemImage" class="form-label">Image (optional)</label>
                        <input type="file" class="form-control" id="editItemImage" name="image">
                    </div>
                    <div class="mb-3">
                        <label for="editItemDesc" class="form-label">Description</label>
                        <textarea class="form-control" id="editItemDesc" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function(){
        // Helper to find card element for a menu id
        function findCard(menuId){
            return document.querySelector('[data-menu-id="' + menuId + '"]');
        }

        // Wire edit buttons
        document.addEventListener('click', function(e){
            var editBtn = e.target.closest('.btn-edit');
            if(editBtn){
                var id = editBtn.dataset.id;
                fetch('<?php echo site_url('owner/menu/get'); ?>?id=' + encodeURIComponent(id), { credentials: 'include', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(function(res){
                        return parseFetchResponse(res).then(function(json){ return { res: res, json: json }; });
                    }).then(function(pair){
                        var res = pair.res, json = pair.json;
                        if(json && json.success){
                            var item = json.item;
                            document.getElementById('edit_menu_id').value = item.menu_id;
                            document.getElementById('editItemName').value = item.item_name;
                            document.getElementById('editItemPrice').value = item.price;
                            document.getElementById('editItemCategory').value = item.category;
                            document.getElementById('editItemDesc').value = item.description;
                            // update csrf field
                            if(json.csrf_token_name && json.csrf_hash){
                                var f = document.getElementById('csrf_token_field_edit');
                                if(f){ f.name = json.csrf_token_name; f.value = json.csrf_hash; }
                            }
                            var m = new bootstrap.Modal(document.getElementById('editItemModal'));
                            m.show();
                        } else {
                            // If server returned an HTML error, show helpful info
                            if(json && (json.__non_json || json.__parse_error)){
                                console.error('Server error or non-JSON response for GET menu/get', json.status, json.text);
                                alert('Server returned an error. See console for details.');
                            } else {
                                alert((json && json.message) ? json.message : 'Failed to fetch item');
                            }
                        }
                    }).catch(function(err){
                        console.error('Fetch failed', err);
                        alert('Failed to contact server. See console for details.');
                    });
            }
        });

        // Handle edit form submit
        var editForm = document.getElementById('editItemForm');
        if(editForm){
                editForm.addEventListener('submit', function(e){
                e.preventDefault();
                var fd = new FormData(editForm);
                // Debug: log CSRF and session cookie presence
                try { const csrfEl = document.getElementById('csrf_token_field_edit'); console.log('editForm CSRF', csrfEl ? { name: csrfEl.name, value: csrfEl.value } : 'missing'); } catch(e){}
                fetch(editForm.action, { method: 'POST', body: fd, credentials: 'include', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(function(res){ return parseFetchResponse(res); }).then(function(json){
                        if(json && json.success){
                            // update DOM card
                            var item = json.item;
                            var card = findCard(item.menu_id);
                            if(card){
                                card.querySelector('.card-title-line h5').textContent = item.item_name;
                                card.querySelector('.card-price').textContent = '₱' + parseFloat(item.price).toFixed(2);
                                card.querySelector('.card-text').textContent = item.description || '';
                                if(item.category) card.querySelector('.card-category').textContent = item.category;
                                if(item.image){
                                    var img = card.querySelector('.card-img-container img');
                                    if(img) img.src = '<?php echo base_url(); ?>' + item.image;
                                }
                            }
                            // update csrf
                            if(json.csrf_token_name && json.csrf_hash){ var f = document.getElementById('csrf_token_field_edit'); if(f){ f.name = json.csrf_token_name; f.value = json.csrf_hash; } }
                            // close modal
                            var mEl = document.getElementById('editItemModal');
                            bootstrap.Modal.getInstance(mEl).hide();
                            if(typeof alertify !== 'undefined') alertify.success('Item updated');
                        } else {
                            if(json && (json.__non_json || json.__parse_error)){
                                console.error('Server error or non-JSON response for POST menu/update', json.status, json.text);
                                alert('Server returned an error (see console).');
                            } else {
                                if(typeof alertify !== 'undefined') alertify.error(json.message || 'Update failed');
                            }
                        }
                    }).catch(function(err){ console.error('Request failed', err); if(typeof alertify !== 'undefined') alertify.error('Request failed'); });
            });
        }

        // Handle delete buttons with duplicate-submission protection
        document.addEventListener('click', function(e){
            var delBtn = e.target.closest('.btn-delete');
            if(!delBtn) return;

            var id = delBtn.dataset.id;

            function performDelete(){
                // Prevent double submissions
                if (delBtn.dataset.deleting) return;
                delBtn.dataset.deleting = '1';
                delBtn.disabled = true;

                var fd = new FormData();
                // include csrf
                var csrfField = document.getElementById('csrf_token_field');
                if(csrfField){ fd.append(csrfField.name, csrfField.value); }
                fd.append('menu_id', id);

                fetch('<?php echo site_url('owner/menu/delete'); ?>', { method: 'POST', body: fd, credentials: 'include', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(function(res){ return parseFetchResponse(res); })
                    .then(function(json){
                        // If server provided fresh CSRF tokens, always update the form field
                        if (json && json.csrf_token_name && json.csrf_hash) {
                            var f = document.getElementById('csrf_token_field');
                            if (f) { f.name = json.csrf_token_name; f.value = json.csrf_hash; }
                        }

                        if(json && json.success){
                            var card = findCard(id);
                            if(card) card.remove();
                            if(typeof alertify !== 'undefined') alertify.success('Item deleted');
                        } else {
                            if(json && (json.__non_json || json.__parse_error)){
                                console.error('Server error or non-JSON response for POST menu/delete', json.status, json.text);
                                if(typeof alertify !== 'undefined') alertify.error('Server error — please refresh the page and try again');
                                else alert('Server error — please refresh the page and try again');
                            } else {
                                if(typeof alertify !== 'undefined') alertify.error(json.message || 'Delete failed');
                            }
                        }
                    })
                    .catch(function(err){
                        console.error('Delete request failed', err);
                        if(typeof alertify !== 'undefined') alertify.error('Delete request failed');
                    })
                    .finally(function(){
                        // Re-enable button
                        delete delBtn.dataset.deleting;
                        try { delBtn.disabled = false; } catch(e){}
                    });
            }

            if(typeof alertify !== 'undefined'){
                alertify.confirm('Delete item', 'Are you sure you want to delete this item?', performDelete, function(){});
            } else {
                if(window.confirm('Are you sure you want to delete this item?')) performDelete();
            }
        });
    })();
</script>
<script>
<?php if($this->session->flashdata('success')): ?>
  alertify.success("<?= $this.session->flashdata('success'); ?>");
<?php endif; ?>

<?php if($this->session->flashdata('error')): ?>
  alertify.error("<?= strip_tags($this->session->flashdata('error')); ?>");
<?php endif; ?>
</script>

</body>
</html>
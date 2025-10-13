<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Menu Management - Order Flow POS</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- AlertifyJS -->
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css"/>

  <style>
    :root {
      --primary-color: rebeccapurple;
      --primary-hover: #5a3b9a;
      --light-bg: #f5f4f9;
      --card-bg: #ffffff;
      --text-dark: #343a40;
      --text-muted: #6c757d;
      --border-color: #e9ecef;
      --status-available-bg: #d4edda;
      --status-available-text: #155724;
      --status-unavailable-bg: #f8d7da;
      --status-unavailable-text: #721c24;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: var(--light-bg);
      color: var(--text-dark);
    }

    /* --- Sidebar --- */
    .sidebar {
      background-color: var(--card-bg);
      width: 250px;
      padding: 20px;
      min-height: 100vh;
      border-right: 1px solid var(--border-color);
      position: fixed;
      display: flex;
      flex-direction: column;
    }
    .sidebar .logo { font-size: 24px; font-weight: 700; color: var(--primary-color); text-align: center; margin-bottom: 30px; }
    .sidebar nav a { color: var(--text-muted); text-decoration: none; display: flex; align-items: center; padding: 12px 20px; border-radius: 8px; margin-bottom: 10px; transition: all 0.2s ease-in-out; font-weight: 500; }
    .sidebar nav a i { margin-right: 15px; font-size: 1.2rem; }
    .sidebar nav a:hover { background-color: var(--light-bg); color: var(--primary-color); }
    .sidebar nav a.active { background-color: var(--primary-color); color: #ffffff; }
    .logout-btn { background-color: transparent; border: 1px solid var(--border-color); color: var(--text-muted); font-weight: 500; border-radius: 8px; padding: 12px 20px; text-decoration: none; display: flex; align-items: center; margin-top: auto; transition: all 0.2s ease-in-out; }
    .logout-btn:hover { background-color: #dc3545; color: #ffffff; border-color: #dc3545; }
    
    /* --- Main Content --- */
    .content { margin-left: 250px; padding: 40px; }
    
    .page-header { 
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 1.5rem;
      margin-bottom: 2.5rem; 
    }
    .page-header .title h1 { font-size: 2.2rem; font-weight: 700; color: var(--text-dark); margin: 0; }
    .page-header .title p { color: var(--text-muted); font-size: 1rem; margin: 0; }
    
    .btn-primary { 
      background: var(--primary-color); border: none; padding: 0.7rem 1.35rem; font-weight: 500; border-radius: 0.5rem; transition: all 0.2s ease-in-out;
      box-shadow: 0 4px 12px rgba(90, 59, 154, 0.2);
    }
    .btn-primary:hover { background-color: var(--primary-hover); transform: translateY(-2px); box-shadow: 0 6px 16px rgba(90, 59, 154, 0.3); }

    .search-bar { position: relative; }
    .search-bar .form-control { padding-left: 2.5rem; border-radius: 0.5rem; border: 1px solid var(--border-color); }
    .search-bar .bi-search { position: absolute; left: 0.8rem; top: 50%; transform: translateY(-50%); color: #aaa; }

    /* --- Menu Card Redesign --- */
    .menu-card { 
      background-color: var(--card-bg); border: 1px solid var(--border-color); border-radius: 1rem; 
      box-shadow: 0 8px 25px rgba(0,0,0,0.05); overflow: hidden; 
      transition: transform 0.3s ease, box-shadow 0.3s ease; 
      position: relative; height: 100%; display: flex; flex-direction: column; 
    }
    .menu-card:hover { transform: translateY(-5px); box-shadow: 0 12px 35px rgba(0,0,0,0.08); }
    .menu-card:hover .card-img-container img { transform: scale(1.05); }

    .card-img-container { height: 220px; overflow: hidden; position: relative; }
    .card-img-container img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease; }
    .card-category { position: absolute; top: 1rem; left: 1rem; background-color: rgba(0,0,0,0.4); backdrop-filter: blur(5px); color:#fff; padding: .3rem .8rem; border-radius:50rem; font-size:.75rem; font-weight: 500; }
    
    .card-body { padding: 1.25rem; flex-grow: 1; display: flex; flex-direction: column; }
    .card-title-line { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom: 0.5rem; }
    .card-title-line h5 { margin:0; color: var(--primary-color); font-weight: 600; }
    .card-price { font-weight: 700; font-size: 1.1rem; color: var(--text-dark); }
    .card-text { color: var(--text-muted); font-size: 0.9rem; line-height: 1.6; margin-top: auto; }
    
    .card-footer { display:flex; justify-content:space-between; align-items:center; padding: 0.75rem 1.25rem; border-top: 1px solid var(--border-color); background-color: #fafbff; }
    .status-badge { display: flex; align-items: center; gap: 6px; padding: 0.3em 0.8em; font-weight: 500; font-size: 0.8rem; border-radius: 20px; }
    .status-badge .dot { width: 8px; height: 8px; border-radius: 50%; }
    .status-badge.available { background-color: var(--status-available-bg); color: var(--status-available-text); }
    .status-badge.available .dot { background-color: var(--status-available-text); }
    .status-badge.outofstock { background-color: var(--status-unavailable-bg); color: var(--status-unavailable-text); }
    .status-badge.outofstock .dot { background-color: var(--status-unavailable-text); }
    
    .action-btn { background: transparent; border: none; color: var(--text-muted); }
    .dropdown-menu { box-shadow: 0 8px 30px rgba(0,0,0,0.1); border-radius: 0.75rem; border: 1px solid var(--border-color); }
    .dropdown-item { font-weight: 500; }
    
    .modal-content { border-radius: 1rem; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.1); }
    .modal-header { background-color: var(--light-bg); border-top-left-radius: 1rem; border-top-right-radius: 1rem; border-bottom: 1px solid var(--border-color); }

    @media (max-width: 992px) { .sidebar { display: none; } .content { margin-left: 0; padding: 20px; } }
  </style>
</head>
<body>

<div class="sidebar d-none d-md-flex">
  <div>
    <div class="logo">Staff Dashboard</div>
    <nav>
      <a href="<?php echo site_url('staff/dashboard'); ?>"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
      <a href="<?php echo site_url('staff/menu'); ?>" class="active"><i class="bi bi-journal-text"></i> Menu</a>
      <a href="<?php echo site_url('staff/order'); ?>" ><i class="bi bi-basket-fill"></i> Orders</a>
      <a href="<?php echo site_url('SettingsController'); ?>"><i class="bi bi-gear-fill"></i> Settings</a>
    </nav>
  </div>
  <a href="<?php echo site_url('auth/logout'); ?>" class="logout-btn"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<div class="content">
    <div class="page-header">
        <div class="title">
            <h1>Menu Management</h1>
            <p>Browse, add, and manage all menu items.</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="search-bar">
                <i class="bi bi-search"></i>
                <input type="text" class="form-control" id="menuSearchInput" placeholder="Search items...">
            </div>
            <button id="addNewItemBtn" class="btn btn-primary" type="button"><i class="bi bi-plus-lg me-2"></i>Add New Item</button>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 row-cols-xxl-4 g-4" id="menu-grid">
        <?php if (!empty($menu_items) && is_array($menu_items)): ?>
            <?php foreach ($menu_items as $item): ?>
                <div class="col menu-item-col" data-menu-id="<?php echo $item['menu_id']; ?>" data-name="<?php echo strtolower(htmlspecialchars($item['item_name'])); ?>" data-category="<?php echo strtolower(htmlspecialchars($item['category'])); ?>">
                    <div class="menu-card">
                        <div class="card-img-container">
                            <img src="<?php echo base_url(!empty($item['image']) ? htmlspecialchars($item['image']) : 'assets/placeholder.png'); ?>" alt="<?php echo htmlspecialchars($item['item_name']); ?>">
                            <div class="card-category"><?php echo !empty($item['category']) ? htmlspecialchars($item['category']) : 'Uncategorized'; ?></div>
                        </div>
                        <div class="card-body">
                            <div class="card-title-line">
                                <h5><?php echo htmlspecialchars($item['item_name']); ?></h5>
                                <span class="card-price">₱<?php echo number_format($item['price'], 2); ?></span>
                            </div>
                            <p class="card-text mt-2"><?php echo !empty($item['description']) ? htmlspecialchars($item['description']) : 'No description available.'; ?></p>
                        </div>
                        <div class="card-footer">
                            <?php
                                $stock_qty = isset($item['stock_quantity']) ? (int)$item['stock_quantity'] : null;
                                if ($stock_qty === null) {
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
                            <div class="dropdown">
                                <button class="btn action-btn dropdown-toggle" type="button" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item btn-edit" href="#" data-id="<?php echo $item['menu_id']; ?>"><i class="bi bi-pencil-fill me-2"></i>Edit</a></li>
                                    <li><a class="dropdown-item text-danger btn-delete" href="#" data-id="<?php echo $item['menu_id']; ?>"><i class="bi bi-trash3-fill me-2"></i>Delete</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center p-5">
                    <h4>No menu items found.</h4>
                    <p>Click "Add New Item" to get started.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Add/Edit Modals -->
<div class="modal fade" id="addItemModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Add New Menu Item</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form id="addItemForm" method="post" enctype="multipart/form-data" action="<?php echo site_url('owner/menu/add'); ?>">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
        <div class="modal-body">
          <div class="mb-3"><label class="form-label">Item Name</label><input type="text" class="form-control" name="name" required></div>
          <div class="mb-3"><label class="form-label">Price</label><input type="number" step="0.01" class="form-control" name="price" required></div>
          <div class="mb-3"><label class="form-label">Category</label><select class="form-select" name="category" required><option value="" disabled selected>Select category</option><option value="Waffles">Waffles</option><option value="Cakes">Cakes</option><option value="Beverages">Beverages</option></select></div>
          <div class="mb-3"><label class="form-label">Image (optional)</label><input type="file" class="form-control" name="image" accept="image/*"></div>
          <div class="mb-3"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="3"></textarea></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Save Item</button></div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="editItemModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Edit Menu Item</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form id="editItemForm" method="post" enctype="multipart/form-data" action="<?php echo site_url('owner/menu/update'); ?>">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" id="csrf_token_field_edit">
        <input type="hidden" name="menu_id" id="edit_menu_id">
        <div class="modal-body">
          <div class="mb-3"><label for="editItemName" class="form-label">Item Name</label><input type="text" class="form-control" id="editItemName" name="name" required></div>
          <div class="mb-3"><label for="editItemPrice" class="form-label">Price</label><input type="number" step="0.01" class="form-control" id="editItemPrice" name="price" required></div>
          <div class="mb-3"><label for="editItemCategory" class="form-label">Category</label><select class="form-select" id="editItemCategory" name="category" required><option value="" disabled>Select category</option><option value="Waffles">Waffles</option><option value="Cakes">Cakes</option><option value="Beverages">Beverages</option></select></div>
          <div class="mb-3"><label for="editItemImage" class="form-label">Image (optional)</label><input type="file" class="form-control" name="image" accept="image/*"></div>
          <div class="mb-3"><label for="editItemDesc" class="form-label">Description</label><textarea class="form-control" id="editItemDesc" name="description" rows="3"></textarea></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Save Changes</button></div>
      </form>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>

<script>
    // --- Flashdata Notifications ---
    <?php if($this->session->flashdata('success')): ?>
      alertify.success("<?= $this->session->flashdata('success'); ?>");
    <?php endif; ?>
    <?php if($this->session->flashdata('error')): ?>
      alertify.error("<?= strip_tags($this->session->flashdata('error')); ?>");
    <?php endif; ?>

    // --- MAIN LOGIC ---
    document.addEventListener('DOMContentLoaded', function() {
        // --- Modal Initialization ---
        const addModalEl = document.getElementById('addItemModal');
        const editModalEl = document.getElementById('editItemModal');
        if (!addModalEl || !editModalEl) return;
        const addModal = new bootstrap.Modal(addModalEl);
        const editModal = new bootstrap.Modal(editModalEl);

        // --- Event Listener for "Add New Item" Button ---
        document.getElementById('addNewItemBtn').addEventListener('click', function() {
            document.getElementById('addItemForm').reset();
            addModal.show();
        });

        // --- AJAX Form Submission for ADDING a new item ---
        const addForm = document.getElementById('addItemForm');
        if (addForm) {
            addForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const submitBtn = addForm.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                const formData = new FormData(addForm);

                fetch(addForm.action, { method: 'POST', body: formData })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            addModal.hide();
                            alertify.success('Menu item added successfully!');
                            // Reload after a delay to show the message
                            setTimeout(() => window.location.reload(), 1000);
                        } else {
                            alertify.error(result.message || 'Failed to add item.');
                            // Re-enable button on failure
                            submitBtn.disabled = false;
                        }
                    }).catch(() => {
                        alertify.error('Network error. Could not add item.');
                        submitBtn.disabled = false;
                    });
            });
        }

        // --- AJAX Form Submission for EDITING an item ---
        const editForm = document.getElementById('editItemForm');
        if (editForm) {
            editForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const submitBtn = editForm.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                const formData = new FormData(editForm);

                fetch(editForm.action, { method: 'POST', body: formData })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            editModal.hide();
                            alertify.success('Item updated successfully!');
                            // Reload after a delay to see changes
                            setTimeout(() => window.location.reload(), 1000);
                        } else {
                            alertify.error(result.message || 'Failed to update item.');
                            submitBtn.disabled = false;
                        }
                    }).catch(() => {
                        alertify.error('Network error. Could not update item.');
                        submitBtn.disabled = false;
                    });
            });
        }

        // --- Event Delegation for Edit and Delete ---
        document.getElementById('menu-grid').addEventListener('click', function(e) {
            const editBtn = e.target.closest('.btn-edit');
            const deleteBtn = e.target.closest('.btn-delete');

            if (editBtn) {
                e.preventDefault();
                const id = editBtn.dataset.id;
                fetch(`<?php echo site_url('owner/menu/get'); ?>?id=${id}`)
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            const item = result.item;
                            const form = document.getElementById('editItemForm');
                            form.querySelector('#edit_menu_id').value = item.menu_id;
                            form.querySelector('#editItemName').value = item.item_name;
                            form.querySelector('#editItemPrice').value = item.price;
                            form.querySelector('#editItemCategory').value = item.category;
                            form.querySelector('#editItemDesc').value = item.description;
                            if (result.csrf_hash) form.querySelector('#csrf_token_field_edit').value = result.csrf_hash;
                            editModal.show();
                        } else {
                            alertify.error(result.message || 'Failed to fetch item details.');
                        }
                    }).catch(() => alertify.error('Network error. Could not fetch item.'));
            }

            if (deleteBtn) {
                e.preventDefault();
                const id = deleteBtn.dataset.id;
                alertify.confirm('Delete Item', 'Are you sure you want to delete this menu item?', 
                    function() { // On OK
                        const formData = new FormData();
                        const csrfField = document.querySelector('input[name="<?= $this->security->get_csrf_token_name(); ?>"]');
                        formData.append(csrfField.name, csrfField.value);
                        formData.append('menu_id', id);

                        fetch('<?php echo site_url('owner/menu/delete'); ?>', { method: 'POST', body: formData })
                            .then(response => response.json())
                            .then(result => {
                                if (result.success) {
                                    alertify.success('Item deleted successfully.');
                                    document.querySelector(`.menu-item-col[data-menu-id='${id}']`).remove();
                                } else {
                                    alertify.error(result.message || 'Failed to delete item.');
                                }
                            }).catch(() => alertify.error('Network error. Could not delete item.'));
                    }, 
                    null // No action on cancel
                ).set('labels', {ok:'Delete', cancel:'Cancel'});
            }
        });

        // --- Live Search Functionality ---
        const searchInput = document.getElementById('menuSearchInput');
        const menuItems = document.querySelectorAll('.menu-item-col');
        searchInput.addEventListener('keyup', function() {
            const searchTerm = searchInput.value.toLowerCase();
            menuItems.forEach(item => {
                const itemName = item.dataset.name;
                const itemCategory = item.dataset.category;
                if (itemName.includes(searchTerm) || itemCategory.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
</script>

</body>
</html>
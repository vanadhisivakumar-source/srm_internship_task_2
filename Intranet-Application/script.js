// Navigation Management (Single Page App Emulation Engine)
function showView(viewId) {
    document.querySelectorAll('.view-section').forEach(section => {
        section.classList.add('hidden');
    });

    if (viewId === 'loginPage' || viewId === 'registrationPage' || viewId === 'forgotPassword') {
        document.getElementById(viewId).classList.remove('hidden');
    } else {
        document.getElementById('appLayout').classList.remove('hidden');
        
        if (viewId === 'adminDashboard') {
            setDashboardRole('admin');
        } else if (viewId === 'userDashboard') {
            setDashboardRole('user');
        }
    }
}

// Configures Navigation UI context parameters depending on access clear levels
function setDashboardRole(role) {
    const userMenu = document.getElementById('userMenu');
    const adminMenu = document.getElementById('adminMenu');
    
    if (role === 'admin') {
        userMenu.classList.add('hidden');
        adminMenu.classList.remove('hidden');
        document.getElementById('currentViewTitle').innerText = "Admin Dashboard";
        switchDashboardTab('admin-home');
    } else {
        adminMenu.classList.add('hidden');
        userMenu.classList.remove('hidden');
        document.getElementById('currentViewTitle').innerText = "User Dashboard";
        switchDashboardTab('user-home');
    }
}

// Switch interior dashboard tab views dynamic visibility engine
function switchDashboardTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
    });
    
    const activeTab = document.getElementById(tabId);
    if(activeTab) {
        activeTab.classList.remove('hidden');
    }

    // Dynamic Top Navbar title mapper mapping definition rules
    const titleMapping = {
        'user-home': 'Dashboard Tools',
        'user-products': 'My Authorized Products',
        'user-profile': 'User Profile Settings',
        'admin-home': 'Admin Control Space',
        'product-management': 'Product Inventory Management',
        'activity-logs': 'System Activity Audit Logs',
        'settings': 'Global Configuration Parameters',
        'create-product': 'Create Product',
        'product-details': 'Product Details View',
        'tool-launch': 'Tool Launch Status'
    };

    if (titleMapping[tabId]) {
        document.getElementById('currentViewTitle').innerText = titleMapping[tabId];
    }

    // Synchronize clicked menu tab highlight states
    document.querySelectorAll('.sidebar-menu a').forEach(link => {
        link.classList.remove('active');
    });
    
    const clickedLink = document.querySelector(`[onclick="switchDashboardTab('${tabId}')"]`);
    if (clickedLink) clickedLink.classList.add('active');
}

// Authentication Forms Redirect interception handler routines
function handleRoute(event, targetView) {
    event.preventDefault();
    showView(targetView);
}

// Terminate user active session session wrapper logout callback
function logout() {
    showView('loginPage');
}

// Password visual text visibility presentation switch toggler
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    input.type = (input.type === "password") ? "text" : "password";
}

// Launch platform tools module routing mechanism emulation sequence
function launchTool(toolName) {
    document.getElementById('detailProductBreadcrumb').innerText = toolName;
    document.getElementById('detailProductName').innerText = toolName;
    switchDashboardTab('product-details');
}

// --- NEW CODE: INTEGRATED SUB-COMPONENT UTILITY CONTROLLERS ---

// User Authorized Tools filter execution
function filterUserProducts() {
    const searchValue = document.getElementById('userProductSearch').value.toLowerCase();
    const items = document.querySelectorAll('.user-prod-item');

    items.forEach(item => {
        const titleText = item.querySelector('.prod-title').innerText.toLowerCase();
        item.style.display = titleText.includes(searchValue) ? "" : "none";
    });
}

// Client Side Form submission mock logic processor for User Profile updates
function saveProfileSettings(event) {
    event.preventDefault();
    alert("Profile configurations have been synchronized successfully.");
}

// Client Side Form submission mock logic processor for Admin System Settings configuration
function saveGlobalSettings(event) {
    event.preventDefault();
    alert("Global system core variable metrics have been updated.");
}

// Audit Logs realtime multi-tier logic query routing sub routine
function filterLogs() {
    const searchInputValue = document.getElementById('logSearch').value.toLowerCase();
    const severitySelectValue = document.getElementById('severityFilter').value;
    const tableRows = document.querySelectorAll('#activityLogsTable tbody tr');

    tableRows.forEach(row => {
        const textContent = row.textContent.toLowerCase();
        const rowSeverity = row.getAttribute('data-severity');

        const matchesSearch = textContent.includes(searchInputValue);
        const matchesSeverity = (severitySelectValue === 'all' || rowSeverity === severitySelectValue);

        row.style.display = (matchesSearch && matchesSeverity) ? "" : "none";
    });
}

// Logs data reload simulation spinner visual execution callback block
function refreshLogs() {
    const refreshBtn = document.querySelector('[onclick="refreshLogs()"]');
    refreshBtn.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> Refreshing...`;
    refreshBtn.disabled = true;

    setTimeout(() => {
        refreshBtn.innerHTML = `<i class="fa-solid fa-rotate-right"></i> Refresh Logs`;
        refreshBtn.disabled = false;
        alert("Activity log sequence indices successfully re-indexed.");
    }, 900);
}
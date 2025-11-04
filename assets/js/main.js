// Main JavaScript file for LMS

// Confirm delete actions
document.addEventListener('DOMContentLoaded', function() {
    // Delete confirmation
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('آیا از حذف این آیتم اطمینان دارید؟ این عمل غیرقابل بازگشت است.')) {
                e.preventDefault();
            }
        });
    });

    // Return confirmation
    const returnButtons = document.querySelectorAll('.btn-return');
    returnButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('آیا می‌خواهید این امانت را بازگردانید؟')) {
                e.preventDefault();
            }
        });
    });

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert && alert.parentNode) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    });

    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });

    // Add loading state to submit buttons
    const submitButtons = document.querySelectorAll('form button[type="submit"]');
    submitButtons.forEach(button => {
        button.closest('form').addEventListener('submit', function() {
            button.disabled = true;
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>در حال پردازش...';
        });
    });

    // Tooltip initialization
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Popover initialization
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
});

// Format date for display
function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('fa-IR');
}

// Format currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('fa-IR', {
        style: 'currency',
        currency: 'IRR'
    }).format(amount);
}

// Format file size
function formatFileSize(bytes) {
    if (!bytes) return '-';
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

// Number formatter for Persian
function formatNumber(num) {
    return new Intl.NumberFormat('fa-IR').format(num);
}

// Search functionality (can be enhanced)
function performSearch(query, tableId) {
    const table = document.getElementById(tableId);
    if (!table) return;
    
    const rows = table.getElementsByTagName('tr');
    const queryLower = query.toLowerCase();
    
    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const text = row.textContent.toLowerCase();
        if (text.includes(queryLower)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    }
}

// Export table to CSV
function exportTableToCSV(tableId, filename) {
    const table = document.getElementById(tableId);
    if (!table) return;
    
    let csv = [];
    const rows = table.querySelectorAll('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = [];
        const cols = rows[i].querySelectorAll('td, th');
        
        for (let j = 0; j < cols.length; j++) {
            let data = cols[j].innerText.replace(/,/g, '');
            row.push(data);
        }
        
        csv.push(row.join(','));
    }
    
    const csvFile = new Blob([csv.join('\n')], { type: 'text/csv' });
    const downloadLink = document.createElement('a');
    downloadLink.download = filename;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = 'none';
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}


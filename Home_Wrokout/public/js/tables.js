// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    // Initialize delete forms confirmation
    const deleteForms = document.querySelectorAll('form[action*="/delete"]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', (e) => {
            if (!confirm('Are you sure you want to delete this item?')) {
                e.preventDefault();
                return false;
            }
            sessionStorage.setItem('scrollPosition', window.scrollY);
        });
    });

    // Initialize scroll position for edit forms
    const editForms = document.querySelectorAll('form[action*="/update"]');
    editForms.forEach(form => {
        form.addEventListener('submit', () => {
            sessionStorage.setItem('scrollPosition', window.scrollY);
        });
    });

    // Restore scroll position
    const savedPosition = sessionStorage.getItem('scrollPosition');
    if (savedPosition) {
        window.scrollTo(0, parseInt(savedPosition));
        sessionStorage.removeItem('scrollPosition');
    }
});

// Make functions available globally
window.toggleAddForm = function(button) {
    console.log('toggleAddForm called'); // Debug log
    const container = button.closest('.add-button-container').nextElementSibling;
    const buttonText = button.querySelector('span');
    
    console.log('Container:', container); // Debug log
    console.log('Container classes:', container.classList); // Debug log
    
    if (container.classList.contains('show')) {
        container.classList.remove('show');
        buttonText.textContent = buttonText.textContent.replace('Hide Form', '+ Add New');
        console.log('Form hidden'); // Debug log
    } else {
        container.classList.add('show');
        buttonText.textContent = buttonText.textContent.replace('+ Add New', 'Hide Form');
        console.log('Form shown'); // Debug log
    }
};

window.toggleEdit = function(id) {
    const row = document.getElementById('edit-row-' + id);
    if (!row) return;
    row.style.display = row.style.display === 'none' ? '' : 'none';
};

window.filterUsers = function() {
    const selectedLevel = document.getElementById('levelFilter').value;
    const rows = document.querySelectorAll('.user-row');
    let visibleCount = 0;
    
    // Remove any existing no-results row first
    const existingNoResultsRow = document.querySelector('.no-results-row');
    if (existingNoResultsRow) {
        existingNoResultsRow.remove();
    }
    
    rows.forEach((row, index) => {
        const rowLevel = row.getAttribute('data-level');
        // Skip edit rows in counting and filtering
        if (row.id && row.id.startsWith('edit-row-')) {
            row.style.display = 'none';
            return;
        }
        
        if (selectedLevel === '' || rowLevel === selectedLevel) {
            row.style.display = '';
            visibleCount++;
            // Update row number
            row.querySelector('td:first-child').textContent = visibleCount;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Update counter
    document.getElementById('usersCount').textContent = visibleCount;
    
    // Show message if no results
    if (visibleCount === 0) {
        const tbody = document.querySelector('tbody');
        const newRow = document.createElement('tr');
        newRow.className = 'no-results-row';
        newRow.innerHTML = '<td colspan="8" class="muted">No users found for selected level.</td>';
        tbody.appendChild(newRow);
    }
};

window.filterPlans = function() {
    const selectedLevel = document.getElementById('levelFilter').value;
    const rows = document.querySelectorAll('.plan-row');
    let visibleCount = 0;
    
    // Remove any existing no-results row first
    const existingNoResultsRow = document.querySelector('.no-results-row');
    if (existingNoResultsRow) {
        existingNoResultsRow.remove();
    }
    
    rows.forEach((row, index) => {
        const rowLevel = row.getAttribute('data-level');
        // Skip edit rows in counting and filtering
        if (row.id && row.id.startsWith('edit-row-')) {
            row.style.display = 'none';
            return;
        }
        
        if (selectedLevel === '' || rowLevel === selectedLevel) {
            row.style.display = '';
            visibleCount++;
            // Update row number
            row.querySelector('td:first-child').textContent = visibleCount;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Update counter
    document.getElementById('plansCount').textContent = visibleCount;
    
    // Show message if no results
    if (visibleCount === 0) {
        const tbody = document.querySelector('tbody');
        const newRow = document.createElement('tr');
        newRow.className = 'no-results-row';
        newRow.innerHTML = '<td colspan="8" class="muted">No plans found for selected level.</td>';
        tbody.appendChild(newRow);
    }
};

// Prevent scrolling to top when editing or deleting
window.preventScrollOnSubmit = function() {
    // Prevent scrolling to top when editing
    const editForms = document.querySelectorAll('form[action*="/update"]');
    editForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Save current scroll position
            sessionStorage.setItem('scrollPosition', window.scrollY);
        });
    });

    // Prevent scrolling to top when deleting
    const deleteForms = document.querySelectorAll('form[action*="/delete"]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Save current scroll position
            sessionStorage.setItem('scrollPosition', window.scrollY);
        });
    });

    // Restore scroll position after page reload
    const savedPosition = sessionStorage.getItem('scrollPosition');
    if (savedPosition) {
        window.scrollTo(0, parseInt(savedPosition));
        sessionStorage.removeItem('scrollPosition');
    }
};

function toggleAddForm() {
    const container = document.getElementById('addFormContainer');
    const buttonText = document.getElementById('addButtonText');
    
    if (container.classList.contains('show')) {
        container.classList.remove('show');
        buttonText.textContent = '+ Add New Exercise Level';
    } else {
        container.classList.add('show');
        buttonText.textContent = 'Hide Form';
    }
}

function filterExerciseLevels() {
    const selectedLevel = document.getElementById('levelFilter').value;
    const rows = document.querySelectorAll('.exercise-level-row');
    let visibleCount = 0;
    
    const existingNoResultsRow = document.querySelector('.no-results-row');
    if (existingNoResultsRow) {
        existingNoResultsRow.remove();
    }
    
    rows.forEach((row, index) => {
        const rowLevel = row.getAttribute('data-level');
        if (row.id && row.id.startsWith('edit-row-')) {
            row.style.display = 'none';
            return;
        }
        
        if (selectedLevel === '' || rowLevel === selectedLevel) {
            row.style.display = '';
            visibleCount++;
            row.querySelector('td:first-child').textContent = visibleCount;
        } else {
            row.style.display = 'none';
        }
    });
    
    document.getElementById('exerciseLevelsCount').textContent = visibleCount;
    
    if (visibleCount === 0) {
        const tbody = document.querySelector('tbody');
        const newRow = document.createElement('tr');
        newRow.className = 'no-results-row';
        newRow.innerHTML = '<td colspan="7" class="muted">No exercise levels found for selected level.</td>';
        tbody.appendChild(newRow);
    }
}

function toggleEdit(id) {
    var row = document.getElementById('edit-row-' + id);
    if (!row) return;
    row.style.display = row.style.display === 'none' ? '' : 'none';
}

document.addEventListener('DOMContentLoaded', function() {
    const editForms = document.querySelectorAll('form[action*="/update"]');
    editForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            sessionStorage.setItem('scrollPosition', window.scrollY);
        });
    });

    const deleteForms = document.querySelectorAll('form[action*="/delete"]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            sessionStorage.setItem('scrollPosition', window.scrollY);
        });
    });

    const savedPosition = sessionStorage.getItem('scrollPosition');
    if (savedPosition) {
        window.scrollTo(0, parseInt(savedPosition));
        sessionStorage.removeItem('scrollPosition');
    }
});

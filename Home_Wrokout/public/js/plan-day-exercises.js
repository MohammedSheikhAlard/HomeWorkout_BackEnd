function toggleAddForm() {
    const container = document.getElementById('addFormContainer');
    const buttonText = document.getElementById('addButtonText');
    
    if (container.classList.contains('show')) {
        container.classList.remove('show');
        buttonText.textContent = '+ Add New Exercise';
    } else {
        container.classList.add('show');
        buttonText.textContent = 'Hide Form';
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

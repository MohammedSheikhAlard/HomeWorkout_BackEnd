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

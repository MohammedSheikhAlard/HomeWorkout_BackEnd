document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('login-form');
    form.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') { 
            form.submit(); 
        }
    });
});

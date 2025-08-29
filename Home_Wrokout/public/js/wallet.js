// Wallet Management JavaScript
let currentUserId = null;
let currentBalance = 0;

function editWallet(userId, balance) {
    currentUserId = userId;
    currentBalance = parseFloat(balance);
    
    document.getElementById('currentBalance').value = '$' + balance.toFixed(2);
    document.getElementById('walletAction').value = '';
    document.getElementById('walletAmount').value = '';
    document.getElementById('newBalance').value = '';
    
    // Update form action
    document.getElementById('walletForm').action = `/admin/users/${userId}/wallet/update`;
    
    document.getElementById('walletModal').style.display = 'block';
}

function createWallet(userId) {
    currentUserId = userId;
    currentBalance = 0;
    
    document.getElementById('currentBalance').value = '$0.00';
    document.getElementById('walletAction').value = 'set';
    document.getElementById('walletAmount').value = '';
    document.getElementById('newBalance').value = '';
    
    // Update form action
    document.getElementById('walletForm').action = `/admin/users/${userId}/wallet/create`;
    
    document.getElementById('walletModal').style.display = 'block';
}

function closeWalletModal() {
    document.getElementById('walletModal').style.display = 'none';
    currentUserId = null;
    currentBalance = 0;
}

// Calculate new balance when action or amount changes
function calculateNewBalance() {
    const action = document.getElementById('walletAction').value;
    const amount = parseFloat(document.getElementById('walletAmount').value) || 0;
    let result = 0;
    
    switch(action) {
        case 'add':
            result = currentBalance + amount;
            break;
        case 'subtract':
            result = currentBalance - amount;
            break;
        case 'set':
            result = amount;
            break;
        default:
            result = currentBalance;
    }
    
    document.getElementById('newBalance').value = '$' + result.toFixed(2);
}

// Initialize wallet functionality
document.addEventListener('DOMContentLoaded', function() {
    const walletAction = document.getElementById('walletAction');
    const walletAmount = document.getElementById('walletAmount');
    
    if (walletAction && walletAmount) {
        walletAction.addEventListener('change', calculateNewBalance);
        walletAmount.addEventListener('input', calculateNewBalance);
    }
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('walletModal');
        if (event.target === modal) {
            closeWalletModal();
        }
    });
    
    // Handle form submission
    const walletForm = document.getElementById('walletForm');
    if (walletForm) {
        walletForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const action = this.action;
            
            fetch(action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => {
                if (response.ok) {
                    window.location.reload();
                } else {
                    throw new Error('Network response was not ok');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        });
    }
});

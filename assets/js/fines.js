document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('paymentModal');
    const payButtons = document.querySelectorAll('.pay-btn-single, .pay-all-btn');
    const closeBtn = document.querySelector('.close-modal');
    const paymentForm = document.getElementById('paymentForm');
    const toast = document.getElementById('successToast');

    // 1. Open modal when any pay button is clicked
    payButtons.forEach(button => {
        button.addEventListener('click', () => {
            modal.style.display = 'block';
        });
    });

    // 2. Close modal when clicking 'X'
    closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    // 3. Close modal when clicking outside the box
    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    // 4. Handle Form Submission
    paymentForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        // Hide modal
        modal.style.display = 'none';
        
        // Show Success Toast
        toast.style.display = 'block';
        
        // Hide toast after 3 seconds
        setTimeout(() => {
            toast.style.display = 'none';
            // Optional: Refresh or update UI here
            // location.reload(); 
        }, 3000);
        
        paymentForm.reset();
    });
});
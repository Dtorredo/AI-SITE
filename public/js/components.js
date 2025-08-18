// Function to include components
function includeComponents() {
    // Include footer
    fetch('components/footer.html')
        .then(response => response.text())
        .then(data => {
            document.getElementById('footer-container').innerHTML = data;
        })
        .catch(error => {
            console.error('Error loading the footer:', error);
        });
}

// Call the function when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', includeComponents);

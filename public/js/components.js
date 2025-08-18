// Function to include components
function includeComponents() {
    // Include header if container exists
    const headerContainer = document.getElementById('header-container');
    if (headerContainer) {
        fetch('components/header.html')
            .then(response => response.text())
            .then(data => {
                headerContainer.innerHTML = data;
            })
            .catch(error => {
                console.error('Error loading the header:', error);
            });
    }

    // Include footer if container exists
    const footerContainer = document.getElementById('footer-container');
    if (footerContainer) {
        fetch('components/footer.html')
            .then(response => response.text())
            .then(data => {
                footerContainer.innerHTML = data;
            })
            .catch(error => {
                console.error('Error loading the footer:', error);
            });
    }
}

// Call the function when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', includeComponents);

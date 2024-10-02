document.addEventListener('DOMContentLoaded', function () {
    var form = document.querySelector('form');
    form.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault(); // Prevent the default behavior of Enter (like form submission)
            var saveButton = document.createElement('input');
            saveButton.type = 'hidden';
            saveButton.name = 'save';
            form.appendChild(saveButton);
            form.submit(); // Submit the form as if the save button was clicked
        }
    });
});


document.addEventListener('DOMContentLoaded', function () {
    const selects = document.querySelectorAll('select');
    selects.forEach(select => {
        updateCellBackground(select); // Call on page load

        select.addEventListener('change', function () {
            updateCellBackground(select); // Update when selection changes
        });
    });
    
    function updateCellBackground(select) {
        const selectedOption = select.options[select.selectedIndex];
        if (selectedOption.value === '') { // Check if "Leeg" is selected
            select.closest('select').style.color = '#e4e2e2'; // Apply gray background
        } else {
            select.closest('select').style.color = ''; // Remove gray background
        }
    }
});

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

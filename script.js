function changeStatus(userId, isChecked, event) {
    event.preventDefault();
    let isActive = isChecked ? 1 : 0; 
    let button = document.getElementById("statusButton_" + userId);

    // Disable the button to prevent further clicks
    if (button) {
        button.disabled = true;
    }

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "./ajax/update_status.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            try {
                let response = JSON.parse(xhr.responseText);

                console.log("Status Updated");

                if (!response.success) {
                    alert("Failed to update status");
                    location.reload(); 
                } else {
                    // Update the button state here
                    if (button) {
                        button.checked = isChecked;
                    }
                }
                
            } catch (e) {
                console.error("Failed to parse JSON response:", e);
                alert("An error occurred while processing the request.");
            } finally {
                // Re-enable the button
                if (button) {
                    button.disabled = false;
                }
            }
        }
    };

    xhr.send("id=" + userId + "&isactive=" + isActive);
}
function changeStatus(userId, isChecked, event) {
    event.preventDefault();
    let isActive = isChecked ? 1 : 0;
    let button = document.getElementById("statusButton_" + userId);

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

                // console.log("Status Updated");

                if (!response.success) {
                    alert("Failed to update status");
                    location.reload();
                } else {
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

function searchUsers() {
    const query = document.getElementById('searchQuery').value;
    fetch(`./views/search.php?query=${query}`)
        .then(response => response.json())
        .then(users => {
            const tableBody = document.querySelector('table tbody');
            tableBody.innerHTML = '';

            users.forEach(user => {
                const row = document.createElement('tr');
                row.id = `user-${user.id}`;
                row.innerHTML = `
                    <td>${user.id}</td>
                    <td>${user.name}</td>
                    <td>
                        <label class="switch">
                            <input type="checkbox" id="statusButton_${user.id}" ${user.isactive ? 'checked' : ''}
                                onclick="changeStatus(${user.id}, this.checked ? 1 : 0, event)">
                            <span class="slider round"></span>
                        </label>
                    </td>
                    <td>
                        <a href="./views/show.php?id=${user.id}" class="btn btn-show">Show</a> | 
                        <a href="./views/update.php?id=${user.id}" class="btn btn-edit">Edit</a> | 
                        <a href="./views/delete.php?id=${user.id}" class="btn btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        });
}

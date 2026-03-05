document.addEventListener("DOMContentLoaded", () => {
    const overlay = document.getElementById("appointmentFormOverlay");
    const form = document.getElementById("appointmentForm");
    const submitBtn = form.querySelector(".submit-btn");
    const tbody = document.querySelector("table tbody");
    const addAppointmentBtn = document.querySelector(".add-appointment");
    const closeBtn = document.querySelector(".close-appointment");
    const filterDropdown = document.querySelector(".appointment--filter");
    const deleteBtn = document.querySelector(".delete-appointment");
    const deleteForm = document.getElementById("deleteForm");
    const deleteIdsInput = document.getElementById("deleteIds");

    let deleteMode = false;
    let selectedRows = new Set();


    // -------------------- Add Appointment --------------------
addAppointmentBtn.addEventListener("click", (e) => {
    e.preventDefault();
    overlay.style.display = "flex";
    form.reset();
    document.getElementById("isEdit").value = "0";
    submitBtn.textContent = "Add";
    document.getElementById("appointmentId").removeAttribute("readonly");
});


// -------------------- Close Appointment Form --------------------
closeBtn.addEventListener("click", (e) => {
    e.preventDefault();
    overlay.style.display = "none";
    form.reset();
    document.getElementById("isEdit").value = "0";
    submitBtn.textContent = "Add";
    document.getElementById("appointmentId").removeAttribute("readonly");
});

// -------------------- Row Click for Delete --------------------
    tbody.addEventListener("click", (e) => {
        const row = e.target.closest("tr");
        if (!row) return;

        // Ignore clicks on edit buttons
        if (e.target.closest("button") || e.target.closest(".edit-icon")) return;

        if (deleteMode) {
            // Toggle row selection
            if (selectedRows.has(row)) {
                selectedRows.delete(row);
                row.classList.remove("selected");
            } else {
                selectedRows.add(row);
                row.classList.add("selected");
            }
        }
    });

    // -------------------- Edit Appointment Button Handler (Event Delegation) --------------------
tbody.addEventListener("click", (e) => {
    if (!e.target.closest(".edit-button")) return;
    e.stopPropagation(); 
    const row = e.target.closest("tr");

    document.getElementById("appointmentId").value = row.cells[0].textContent.trim();
    document.getElementById("patientName").value = row.cells[1].textContent.trim();
    document.getElementById("doctor").value = row.cells[2].textContent.trim();
    document.getElementById("department").value = row.cells[3].textContent.trim();
    document.getElementById("appointmentDate").value = row.cells[4].textContent.trim();
    document.getElementById("time").value = row.cells[5].textContent.trim();
    document.getElementById("type").value = row.cells[6].textContent.trim();
    document.getElementById("status").value = row.cells[7].textContent.trim();

    document.getElementById("isEdit").value = row.cells[0].textContent.trim();
    submitBtn.textContent = "Update Appointment";
    overlay.style.display = "flex";
});


// -------------------- Filter Appointments --------------------
if (filterDropdown) {
    filterDropdown.addEventListener("change", function () {
        const value = this.value;
        tbody.querySelectorAll("tr").forEach(row => {
            row.style.display = "table-row"; // reset
            if (value !== "All" && row.cells[7].textContent.trim() !== value) {
                row.style.display = "none";
            }
        });
    });
}


// -------------------- Delete Button Logic --------------------
    deleteBtn.addEventListener("click", () => {
        if (!deleteMode) {
            deleteMode = true;
            selectedRows.clear();
            tbody.querySelectorAll("tr").forEach(row => row.classList.add("delete-mode"));
            deleteBtn.innerHTML = '<i class="bx bx-trash"></i> Cancel/Confirm';
        } else {
            if (selectedRows.size === 0) {
                exitDeleteMode();
            } else {
                if (confirm("Are you sure you want to delete the selected patient(s)?")) {
                    const ids = Array.from(selectedRows).map(row => row.cells[0].textContent.trim());
                    deleteIdsInput.value = ids.join(",");
                    deleteForm.submit();
                }
            }
        }
    });

    function exitDeleteMode() {
        deleteMode = false;
        selectedRows.clear();
        tbody.querySelectorAll("tr").forEach(row => row.classList.remove("delete-mode", "selected"));
        deleteBtn.innerHTML = '<i class="bx bx-trash"></i> Delete Appointment';
    }

    // Apply styles to Status cell
function colorCells(row) {
    const statusCell = row.children[7]; // Status column for patients 
    const status = statusCell.textContent.trim();

    if (status === "Rejected") {
        statusCell.style.color = "red";
        statusCell.style.fontWeight = "600";
    } else if (status === "Confirmed") {
        statusCell.style.color = "lightgreen";
        statusCell.style.fontWeight = "600";
    } else if (status === "Pending") {
        statusCell.style.color = "orange";
        statusCell.style.fontWeight = "600";
    } else {
        statusCell.style.color = "black";
        statusCell.style.fontWeight = "600";
    }
}

// ✅ Call this after DOM load
tbody.querySelectorAll("tr").forEach(row => {
    colorCells(row);
});









})
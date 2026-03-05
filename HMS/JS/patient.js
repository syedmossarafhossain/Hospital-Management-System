document.addEventListener("DOMContentLoaded", () => {
    const overlay = document.getElementById("patientFormOverlay");
    const form = document.getElementById("patientForm");
    const submitBtn = form.querySelector(".submit-btn");
    const tbody = document.querySelector("table tbody");
    const addPatientBtn = document.querySelector(".add-patient");
    const closeBtn = document.querySelector(".close-patient");
    const filterDropdown = document.querySelector(".patient--filter");
    const deleteBtn = document.querySelector(".delete-patient");
    const deleteForm = document.getElementById("deleteForm");
    const deleteIdsInput = document.getElementById("deleteIds");

    let deleteMode = false;
    let selectedRows = new Set();

    // -------------------- Add Patient --------------------
    addPatientBtn.addEventListener("click", (e) => {
        e.preventDefault();
        overlay.style.display = "flex";
        form.reset();
        document.getElementById("isEdit").value = "0";
        submitBtn.textContent = "Add";
        document.getElementById("patientId").removeAttribute("readonly");
    });

    // -------------------- Close Patient Form --------------------
    closeBtn.addEventListener("click", (e) => {
        e.preventDefault();
        overlay.style.display = "none";
        form.reset();
        document.getElementById("isEdit").value = "0";
        submitBtn.textContent = "Add";
        document.getElementById("patientId").removeAttribute("readonly");
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

    // -------------------- Edit Button Handler (Event Delegation) --------------------
    tbody.addEventListener("click", (e) => {
        if (!e.target.closest(".edit-button")) return;
        e.stopPropagation(); 
        const row = e.target.closest("tr");

        document.getElementById("patientId").value = row.cells[0].textContent.trim();
        document.getElementById("patientName").value = row.cells[1].textContent.trim();
        document.getElementById("date_in").value = row.cells[2].textContent.trim();
        document.getElementById("age").value = row.cells[3].textContent.trim();
        document.getElementById("gender").value = row.cells[4].textContent.trim();
        document.getElementById("contact").value = row.cells[5].textContent.trim();
        document.getElementById("doctor").value = row.cells[6].textContent.trim();
        document.getElementById("ward").value = row.cells[7].textContent.trim();
        document.getElementById("status").value = row.cells[8].textContent.trim();
        
        document.getElementById("isEdit").value = row.cells[0].textContent.trim();
        submitBtn.textContent = "Update Patient";
        overlay.style.display = "flex";
    });

    // -------------------- Filter Patients --------------------
    if (filterDropdown) {
        filterDropdown.addEventListener("change", function () {
            const value = this.value;
            tbody.querySelectorAll("tr").forEach(row => {
                row.style.display = "table-row"; // reset
                if (value !== "All" && row.cells[8].textContent.trim() !== value) {
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
        deleteBtn.innerHTML = '<i class="bx bx-trash"></i> Delete Patient';
    }

    // Apply styles to Status cell
function colorCells(row) {
    const statusCell = row.children[8]; // Status column for patients
    const status = statusCell.textContent.trim();

    if (status === "Critical Cases") {
        statusCell.style.color = "red";
        statusCell.style.fontWeight = "600";
    } else if (status === "Discharged") {
        statusCell.style.color = "lightgreen";
        statusCell.style.fontWeight = "600";
    } else if (status === "Admitted") {
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

});

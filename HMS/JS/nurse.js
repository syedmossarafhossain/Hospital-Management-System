document.addEventListener("DOMContentLoaded", () => {
    const overlay = document.getElementById("nurseFormOverlay");
    const form = document.getElementById("nurseForm");
    const submitBtn = form.querySelector(".submit-btn");
    const tbody = document.querySelector("table tbody");
    const addNurseBtn = document.querySelector(".add-nurse");
    const closeBtn = document.querySelector(".close-nurse");
    const filterDropdown = document.querySelector(".nurse--filter");
    const deleteBtn = document.querySelector(".delete-nurse");
    const deleteForm = document.getElementById("deleteForm");
    const deleteIdsInput = document.getElementById("deleteIds");

    let deleteMode = false;
    let selectedRows = new Set();

    // -------------------- Add Nurse --------------------
    addNurseBtn.addEventListener("click", (e) => {
        e.preventDefault();
        overlay.style.display = "flex";
        form.reset();
        document.getElementById("isEdit").value = "0";
        submitBtn.textContent = "Add";
        document.getElementById("nurseId").removeAttribute("readonly");
    });

    // -------------------- Close Nurse Form --------------------
    closeBtn.addEventListener("click", (e) => {
        e.preventDefault();
        overlay.style.display = "none";
        form.reset();
        document.getElementById("isEdit").value = "0";
        submitBtn.textContent = "Add";
        document.getElementById("nurseId").removeAttribute("readonly");
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

    // -------------------- Edit Button Handler --------------------
    tbody.querySelectorAll(".edit-button").forEach(btn => {
        btn.addEventListener("click", (e) => {
            e.stopPropagation(); // prevent row click
            const row = e.target.closest("tr");
            document.getElementById("nurseId").value = row.cells[0].textContent.trim();
            document.getElementById("name").value = row.cells[1].textContent.trim();
            document.getElementById("gender").value = row.cells[2].textContent.trim();
            document.getElementById("contact").value = row.cells[3].textContent.trim();
            document.getElementById("ward").value = row.cells[4].textContent.trim();
            document.getElementById("shift").value = row.cells[5].textContent.trim();
            document.getElementById("status").value = row.cells[6].textContent.trim();
            document.getElementById("isEdit").value = row.cells[0].textContent.trim();
            document.getElementById("nurseId").setAttribute("readonly", true);
            submitBtn.textContent = "Update";
            overlay.style.display = "flex";
        });
    });

    // -------------------- Filter Nurses --------------------
    if (filterDropdown) {
        filterDropdown.addEventListener("change", function () {
            const value = this.value;
            tbody.querySelectorAll("tr").forEach(row => {
                row.style.display = "table-row"; // reset
                if (value === "On_Duty_Nurses" && row.cells[6].textContent.trim() !== "On Duty") row.style.display = "none";
                if (value === "General_Ward_Nurses" && row.cells[4].textContent.trim() !== "General") row.style.display = "none";
                if (value === "ICU_Ward_Nurses" && row.cells[4].textContent.trim() !== "ICU") row.style.display = "none";
            });
        });
    }

    // -------------------- Delete Button Logic --------------------
    deleteBtn.addEventListener("click", () => {
        if (!deleteMode) {
            // Enter delete mode
            deleteMode = true;
            selectedRows.clear();
            tbody.querySelectorAll("tr").forEach(row => row.classList.add("delete-mode"));
            deleteBtn.innerHTML = '<i class="bx bx-trash"></i> Cancel/Confirm';
        } else {
            if (selectedRows.size === 0) {
                // Cancel delete mode
                exitDeleteMode();
            } else {
                // Confirm delete
                if (confirm("Are you sure you want to delete the selected nurse(s)?")) {
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
        deleteBtn.innerHTML = '<i class="bx bx-trash"></i> Delete Nurse';
    }

    // Apply styles to Ward and Status cells
function colorCells(row) {
    const statusCell = row.children[6]; // Status column
    const wardCell = row.children[4];   // Ward/Role column
    const status = statusCell.textContent.trim();
    const ward = wardCell.textContent.trim();

    // ✅ Apply styles for Status
    if (status === "On Duty") {
        statusCell.style.color = "orange";
        statusCell.style.fontWeight = "600";
    } else {
        statusCell.style.color = "black";
        statusCell.style.fontWeight = "600";
    }

    // ✅ Apply styles for Ward
    if (ward === "ICU") {
        wardCell.style.color = "red";
        wardCell.style.fontWeight = "600";
    } else if (ward === "General") {
        wardCell.style.color = "lightgreen";
        wardCell.style.fontWeight = "600";
    } else {
        wardCell.style.color = "black";
        wardCell.style.fontWeight = "600";
    }
}

// -------------------- Apply colors on page load --------------------
tbody.querySelectorAll("tr").forEach(row => colorCells(row));

});

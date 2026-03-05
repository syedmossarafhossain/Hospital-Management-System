document.addEventListener("DOMContentLoaded", () => {
    const overlay = document.getElementById("staffFormOverlay");
    const form = document.getElementById("staffForm");
    const submitBtn = form.querySelector(".submit-btn");
    const tbody = document.querySelector("table tbody");
    const addStaffBtn = document.querySelector(".add-staff");
    const closeBtn = document.querySelector(".close-staff");
    const filterDropdown = document.querySelector(".staff--filter");
    const deleteBtn = document.querySelector(".delete-staff");
    const deleteForm = document.getElementById("deleteForm");
    const deleteIdsInput = document.getElementById("deleteIds");

    let deleteMode = false;
    let selectedRows = new Set();

    // -------------------- Add Staff --------------------
    addStaffBtn.addEventListener("click", (e) => {
        e.preventDefault();
        overlay.style.display = "flex";
        form.reset();
        document.getElementById("isEdit").value = "0";
        submitBtn.textContent = "Add";
        document.getElementById("staffId").removeAttribute("readonly");
    });

    // -------------------- Close Nurse Form --------------------
    closeBtn.addEventListener("click", (e) => {
        e.preventDefault();
        overlay.style.display = "none";
        form.reset();
        document.getElementById("isEdit").value = "0";
        submitBtn.textContent = "Add";
        document.getElementById("staffId").removeAttribute("readonly");
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
            document.getElementById("staffId").value = row.cells[0].textContent.trim();
            document.getElementById("name").value = row.cells[1].textContent.trim();
            document.getElementById("gender").value = row.cells[2].textContent.trim();
            document.getElementById("contact").value = row.cells[3].textContent.trim();
            document.getElementById("role").value = row.cells[4].textContent.trim();
            document.getElementById("shift").value = row.cells[5].textContent.trim();
            document.getElementById("status").value = row.cells[6].textContent.trim();
            document.getElementById("isEdit").value = row.cells[0].textContent.trim();
            document.getElementById("staffId").setAttribute("readonly", true);
            submitBtn.textContent = "Update";
            overlay.style.display = "flex";
        });
    });

    // -------------------- Filter Staffs --------------------
    if (filterDropdown) {
        filterDropdown.addEventListener("change", function () {
            const value = this.value;
            tbody.querySelectorAll("tr").forEach(row => {
                row.style.display = "table-row"; // reset
                if (value === "support_Staffs" && row.cells[4].textContent.trim() !== "Support Staff") row.style.display = "none";
                if (value === "administrative_Staffs" && row.cells[4].textContent.trim() !== "Administrative Staff") row.style.display = "none";
                if (value === "technical_Staffs" && row.cells[4].textContent.trim() !== "Technical Staff") row.style.display = "none";
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
                if (confirm("Are you sure you want to delete the selected staff(s)?")) {
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
        deleteBtn.innerHTML = '<i class="bx bx-trash"></i> Delete Staff';
    }

        // Apply styles to Role cell
        function colorCells(row) {
            const roleCell = row.children[4]; // Role column
            const role = roleCell.textContent.trim();

            if (role === "Technical Staff") {
                roleCell.style.color = "red";
                roleCell.style.fontWeight = "600";
            } else if (role === "Administrative Staff") {
                roleCell.style.color = "lightgreen";
                roleCell.style.fontWeight = "600";
            } else if (role === "Support Staff") {
                roleCell.style.color = "orange";
                roleCell.style.fontWeight = "600";
            } else {
                roleCell.style.color = "black";
                roleCell.style.fontWeight = "600";
            }
        }

        // ✅ Call this after DOM load
        tbody.querySelectorAll("tr").forEach(row => {
            colorCells(row);
        });


    });


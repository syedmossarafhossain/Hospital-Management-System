document.addEventListener("DOMContentLoaded", function () {
    const doctorFilterDropdown = document.querySelector(".doctor--filter");
    const patientFilterDropdown = document.querySelector(".patient--filter");
    const patientTableBody = document.querySelector(".recent--patients table tbody");

    // -------------------- Filter Doctors --------------------
    if (doctorFilterDropdown) {
        doctorFilterDropdown.addEventListener("change", function () {
            const value = this.value;
            document.querySelectorAll(".doctor--card").forEach(card => {
                card.style.display = "block";
                if (value === "available_Doctors" && card.dataset.availability !== "available") card.style.display = "none";
                if (value === "surgical_Doctors" && card.dataset.type !== "surgical") card.style.display = "none";
                if (value === "specialist_Doctors" && card.dataset.type !== "specialist") card.style.display = "none";
            });
        });
    }

    // -------------------- Filter Patients --------------------
    if (patientFilterDropdown && patientTableBody) {
        patientFilterDropdown.addEventListener("change", function () {
            const value = this.value.toLowerCase();
            patientTableBody.querySelectorAll("tr").forEach(row => {
                row.style.display = "table-row"; // reset
                const status = row.cells[8].textContent.trim().toLowerCase(); // 9th column = index 8
                if (value !== "all" && status !== value) {
                    row.style.display = "none";
                }
            });
        });
    }

    // -------------------- Color Status Cells --------------------
    function colorCells(row) {
        const statusCell = row.children[8]; // 9th column = index 8
        const status = statusCell.textContent.trim().toLowerCase();

        if (status === "critical" || status === "critical cases") {
            statusCell.style.color = "red";
            statusCell.style.fontWeight = "600";
        } else if (status === "discharged" || status === "discharaged") {
            statusCell.style.color = "lightgreen";
            statusCell.style.fontWeight = "600";
        } else if (status === "admitted") {
            statusCell.style.color = "orange";
            statusCell.style.fontWeight = "600";
        } else {
            statusCell.style.color = "black";
            statusCell.style.fontWeight = "600";
        }
    }

    if (patientTableBody) {
        patientTableBody.querySelectorAll("tr").forEach(row => {
            colorCells(row);
        });
    }
});

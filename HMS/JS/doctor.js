//START THE DOCTOR.PHP JAVASCRIPT PART-------------------------------------------------//

document.addEventListener("DOMContentLoaded", function () {
    const addDoctorBtn = document.querySelector(".add-doctor");
    const doctorFormOverlay = document.getElementById("doctorFormOverlay");
    const closeBtn = document.querySelector(".close-doctor");
    const filterDropdown = document.querySelector(".doctor--filter");
    const deleteDoctorBtn = document.querySelector(".delete-doctor"); 
    const doctorCardsContainer = document.querySelector(".doctors--cards");
    const deleteForm = document.getElementById("deleteForm");
    const deleteDoctorId = document.getElementById("deleteDoctorId");

    let deleteMode = false;

    // Open Add Doctor Form
    if (addDoctorBtn && doctorFormOverlay) {
        addDoctorBtn.addEventListener("click", () => {
            doctorFormOverlay.style.display = "flex";
        });
    }

    // Close Add Doctor Form
    if (closeBtn && doctorFormOverlay) {
        closeBtn.addEventListener("click", () => {
            doctorFormOverlay.style.display = "none";
            const form = document.getElementById("doctorForm");
            if (form) form.reset();
        });
    }

    // Filter doctors
    if (filterDropdown) {
        filterDropdown.addEventListener("change", function () {
            const value = this.value;
            document.querySelectorAll(".doctor--card").forEach(card => {
                card.style.display = "block";
                if (value === "available_Doctors" && card.dataset.availability !== "available") card.style.display = "none";
                if (value === "surgical_Doctors" && card.dataset.type !== "surgical") card.style.display = "none";
                if (value === "specialist_Doctors" && card.dataset.type !== "specialist") card.style.display = "none";
            });
        });
    }

    // ✅ Toggle delete mode
if (deleteDoctorBtn && deleteForm && deleteDoctorId) {
    deleteDoctorBtn.addEventListener("click", () => {
        deleteMode = !deleteMode; // toggle mode

        if (deleteMode) {
            // ON: highlight doctors
            doctorCardsContainer.querySelectorAll(".doctor--card").forEach(card => {
                card.classList.add("delete-mode");
            });
            deleteDoctorBtn.innerHTML = '<i class="bx bx-x"></i> Cancel Delete';
        } else {
            // OFF: reset selection + UI
            doctorCardsContainer.querySelectorAll(".doctor--card").forEach(card => {
                card.classList.remove("delete-mode", "selected");
            });
            deleteDoctorBtn.innerHTML = '<i class="bx bx-trash"></i> Delete Doctor';
        }
    });
}

// ✅ Handle card click in delete mode
if (doctorCardsContainer) {
    doctorCardsContainer.addEventListener("click", function (e) {
        const card = e.target.closest(".doctor--card");
        if (!card) return;

        if (deleteMode) {
            // select clicked card
            doctorCardsContainer.querySelectorAll(".doctor--card").forEach(c => c.classList.remove("selected"));
            card.classList.add("selected");

            const id = card.dataset.id;
            if (id && confirm("Are you sure you want to delete this doctor?")) {
                deleteDoctorId.value = id;
                deleteForm.submit(); // ✅ normal form submit, no AJAX
            } else {
                // cancel → exit delete mode
                deleteMode = false;
                doctorCardsContainer.querySelectorAll(".doctor--card").forEach(c => c.classList.remove("delete-mode", "selected"));
                deleteDoctorBtn.innerHTML = '<i class="bx bx-trash"></i> Delete Doctor';
            }
        }
    });
}


});

//END THE DOCTOR.PHP JAVASCRIPT PART-------------------------------------------------//

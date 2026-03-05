<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "hospitaldb";

// Connect to DB
$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// -------------------- Overview counts --------------------//
$totalPatients = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM patients"));
$admittedPatients = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM patients WHERE status='Admitted'"));
$dischargedPatients = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM patients WHERE status='Discharged'"));
$criticalCases = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM patients WHERE status='Critical Cases'"));


// Handle form submission (insert or update patient)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['patientId'])) {
    $patientId = mysqli_real_escape_string($conn, $_POST['patientId']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $date_in = mysqli_real_escape_string($conn, $_POST['date_in']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $doctor = mysqli_real_escape_string($conn, $_POST['doctor']);
    $ward = mysqli_real_escape_string($conn, $_POST['ward']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // ✅ Get hidden field for edit mode
    $isEdit = isset($_POST['isEdit']) ? mysqli_real_escape_string($conn, $_POST['isEdit']) : "0";

    if ($isEdit && $isEdit != "0") {
        // Update existing patient
        $sql = "UPDATE patients 
                SET name='$name', date_in='$date_in', age='$age', gender='$gender', 
                    contact='$contact', doctor='$doctor', ward='$ward', status='$status'
                WHERE patientId='$isEdit'";
    } else {
        // Insert new patient
        $sql = "INSERT INTO patients (patientId, name, date_in, age, gender, contact, doctor, ward, status) 
                VALUES ('$patientId', '$name', '$date_in', '$age', '$gender', '$contact', '$doctor', '$ward', '$status')";
    }

    mysqli_query($conn, $sql);
    header("Location: patient.php");
    exit;
}


// Handle Delete Request (for Patients)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_selected'])) {
    if (isset($_POST['ids']) && !empty($_POST['ids'])) {
        $ids = explode(",", $_POST['ids']);
        $ids = array_map(fn($id) => mysqli_real_escape_string($conn, $id), $ids);
        $idsList = "'" . implode("','", $ids) . "'";
        
        mysqli_query($conn, "DELETE FROM patients WHERE patientId IN ($idsList)");
    }
    
    header("Location: patient.php");
    exit;
}



// Fetch nurses
$result = mysqli_query($conn, "SELECT * FROM patients");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/patient.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Patients</title>
</head>
<body>

    <section class="header">
        <div class="logo">
            <i class="bx bx-menu icon-0 menu"></i>
            <h2>H <span>MS</span></h2>
        </div>
        <div class="search--notification--profile">
            <div class="search">
                <input type="text" placeholder="search scdule...">
                <button><i class="bx bx-search"></i></button>
            </div>
            <div class="notification--profile">
                <div class="picon lock">
                    <i class="bx bx-lock"></i>
                </div>
                <div class="picon bell">
                    <i class="bx bx-bell"></i>
                </div>
                <div class="picon chat">
                    <i class="bx bx-chat"></i>
                </div>
                <div class="picon profile">
                    <i class="bx bx-user-circle"></i>
                </div> 
            </div>
        </div>
    </section>
    <section class="main">
        <div class="sidebar">
            <ul class="sidebar--items">
                <li>
                    <a href="dashboard.php">
                        <span class="icon icon-1"><i class="bx bxs-dashboard"></i></span>
                        <span class="sidebar--item">Dashboard</span>
                    </a>
                </li>
                
                <li>
                    <a href="doctor.php">
                        <span class="icon icon-3"><i class="fa-solid fa-user-doctor"></i></span>
                        <span class="sidebar--item" style="white-space: nowrap;">Doctors</span>
                    </a>
                </li>

                <li>
                    <a href="nurse.php">
                        <span class="icon icon-8"><i class="fa-solid fa-user-nurse"></i></span>
                        <span class="sidebar--item" style="white-space: nowrap;">Nurses</span>
                    </a>
                </li>
                <li>
                    <a href="staff.php">
                        <span class="icon icon-4"><i class="bx bxs-group"></i></span>
                        <span class="sidebar--item">Staff</span>
                    </a>
                </li>
                <li>
                    <a href="patient.php" id="active--link">
                        <span class="icon icon-5"><i class="bx bxs-user"></i></span>
                        <span class="sidebar--item">Patients</span>
                    </a>
                </li>
                <li>
                    <a href="appointment.php">
                        <span class="icon icon-2"><i class="bx bxs-book-add"></i></span>
                        <span class="sidebar--item">Appointments</span>
                    </a>
                </li>
                <li>
                    <a href="laboratory.php">
                        <span class="icon icon-6"><i class="fa-solid fa-vial"></i></span>
                        <span class="sidebar--item">Laboratory</span>
                    </a>
                </li>
            </ul>
            <ul class="sidebar--bottom-items">
                <li>
                    <a href="logout.php">
                        <span class="icon icon-7"><i class="bx bx-log-out"></i></span>
                        <span class="sidebar--item">Log&nbsp;out</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="main-content">
        <div class="overview">
                <div class="title">
                    <h2 class="section--title">Overview</h2>
                </div>
                <div class="cards">
                    <div class="card card-1">
                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--title">Total Patients</h5>
                                <h1><?php echo $totalPatients; ?></h1>
                            </div>
                            <i class="bx bxs-user card--icon--lg"></i>
                        </div>
                        <div class="card--stats">
                            <span><i class="bx bxs-bar-chart-alt-2 card--icon stst--icon"></i>87%</span>
                            <span><i class="bx bx-caret-up card--icon up--arrow"></i>75</span>
                            <span><i class="bx bxs-down-arrow-alt card--icon down--arrow"></i>25</span>
                        </div>
                    </div>
                    <div class="card card-2">
                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--title">Admitted Patients</h5>
                                <h1><?php echo $admittedPatients; ?></h1>
                            </div>
                            <i class="bx bxs-hotel card--icon--lg"></i>
                        </div>
                        <div class="card--stats">
                            <span><i class="bx bxs-bar-chart-alt-2 card--icon stst--icon"></i>75%</span>
                            <span><i class="bx bx-caret-up card--icon up--arrow"></i>90</span>
                            <span><i class="bx bxs-down-arrow-alt card--icon down--arrow"></i>10</span>
                        </div>
                    </div>
                    <div class="card card-3">
                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--title">Discharged Patients</h5>
                                <h1><?php echo $dischargedPatients; ?></h1>
                            </div>
                            <i class="bx bxs-calendar card--icon--lg"></i>
                        </div>
                        <div class="card--stats">
                            <span><i class="bx bxs-bar-chart-alt-2 card--icon stst--icon"></i>25%</span>
                            <span><i class="bx bx-caret-up card--icon up--arrow"></i>60</span>
                            <span><i class="bx bxs-down-arrow-alt card--icon down--arrow"></i>40</span>
                        </div>
                    </div>
                    <div class="card card-4">
                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--title">Critical Cases</h5>
                                <h1><?php echo $criticalCases; ?></h1>
                            </div>
                            <i class="fa-solid fa-bed-pulse card--icon--lg"></i>
                        </div>
                        <div class="card--stats">
                            <span><i class="bx bxs-bar-chart-alt-2 card--icon stst--icon"></i>10%</span>
                            <span><i class="bx bx-caret-up card--icon up--arrow"></i>2</span>
                            <span><i class="bx bxs-down-arrow-alt card--icon down--arrow"></i>8</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="patients">
                <div class="title">
                        <h2 class="section--title">Patients</h2>
                        <div class="patients--right--btns">
                    <select name="date" id="date" class="dropdown patient--filter">
                       <option value="All">All</option>
                       <option value="Admitted">Admitted</option>
                       <option value="Discharged">Discharged</option>
                       <option value="Critical Cases">Critical Cases</option>
                    </select>
                    <button class="add-patient" onclick="document.getElementById('patientFormOverlay').style.display='flex'"><i class="bx bx-plus"></i>Add Patient</button>
                    <button class="delete-patient"><i class="bx bx-trash"></i> <span>Delete Patient</span></button>
                    </div>
                </div>
                <div class="table-patient-page-scroll">
                <div class="table-patient-page">
                <table>
                    <thead>
                        <tr>
                            <th>Patient ID</th>
                            <th>Name</th>
                            <th>Date in</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Contact</th>
                            <th>Doctor</th>
                            <th>Ward</th>
                            <th>Status</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = mysqli_query($conn, "SELECT * FROM patients");
                        while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>
                            <td>'.$row['patientId'].'</td>
                            <td>'.$row['name'].'</td>
                            <td>'.$row['date_in'].'</td>
                            <td>'.$row['age'].'</td>
                            <td>'.$row['gender'].'</td>
                            <td>'.$row['contact'].'</td>
                            <td>'.$row['doctor'].'</td>
                            <td>'.$row['ward'].'</td>
                            <td>'.$row['status'].'</td>
                            <td>
                                <button type="button" class="edit-button">
                                    <i class="bx bx-edit edit-icon" style="cursor:pointer; color:blue; font-size:18px;"></i>
                                </button>
                            </td>
                        </tr>';
                        }
                        ?>

                    </tbody>
                </table>
                </div>
                </div>
                <!-- Patient Form Overlay -->
                <div class="patient-form-overlay" id="patientFormOverlay" style="display:none;">
                    <div class="patient-form">
                        <h2>Add Patient</h2>
                        <form id="patientForm"  method="POST" action="patient.php">
                            <input type="hidden" id="isEdit" name="isEdit" value="0">

                            <label for="patientId">Patient ID</label>
                            <input type="text" id="patientId" name="patientId" placeholder="Enter Patient ID (e.g., P001)" required>
                            
                            <label for="name">Name</label>
                            <input type="text" id="patientName" name="name" placeholder="Enter Patient Name" required>
                            
                            <label for="date_in">Date In</label>
                            <input type="date" id="date_in" name="date_in" required>

                            <label for="age">Age</label>
                            <input type="number" id="age" name="age" placeholder="Enter Age" required>

                            <label for="gender">Gender</label>
                            <select id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="Female">Female</option>
                                <option value="Male">Male</option>
                            </select>

                            <label for="contact">Contact</label>
                            <input type="text" id="contact" name="contact" placeholder="Enter Contact Number" required>

                            <label for="doctor">Doctor</label>
                            <input type="text" id="doctor" name="doctor" placeholder="Assign Doctor" required>

                            <label for="ward">Ward</label>
                            <select id="ward" name="ward" required>
                                <option value="">Select Ward</option>
                                <option value="General">General Ward</option>
                                <option value="ICU">ICU Ward</option>
                            </select>

                            <label for="status">Status</label>
                            <select id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="Admitted">Admitted</option>
                                <option value="Discharged">Discharged</option>
                                <option value="Critical Cases">Critical Cases</option>
                            </select>


                            <div class="form-buttons">
                                <button type="submit" class="submit-btn">Add Patient</button>
                                <button type="button" class="close-patient" onclick="document.getElementById('patientFormOverlay').style.display='none'">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
    </section>
    <!-- Hidden from of delete staff-->
    <form id="deleteForm" method="POST" action="patient.php" style="display:none;">
        <input type="hidden" name="delete_selected" value="1">
        <input type="hidden" name="ids" id="deleteIds">
    </form>

    

    <script src="JS/patient.js"></script>
</body>
</html>

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

// -------------------- Overview counts -------------------- //
$totalAppointments    = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM appointments"));
$pendingAppointments  = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM appointments WHERE status='Pending'"));
$confirmedAppointments = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM appointments WHERE status='Confirmed'"));
$rejectedAppointments = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM appointments WHERE status='Rejected'"));


// -------------------- Handle form submission (Insert / Update) -------------------- //
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['appointmentId'])) {
    $appointmentId = mysqli_real_escape_string($conn, $_POST['appointmentId']);
    $patientName   = mysqli_real_escape_string($conn, $_POST['patientName']);
    $doctor        = mysqli_real_escape_string($conn, $_POST['doctor']);
    $department    = mysqli_real_escape_string($conn, $_POST['department']);
    $date          = mysqli_real_escape_string($conn, $_POST['date']);
    $time          = mysqli_real_escape_string($conn, $_POST['time']);
    $type          = mysqli_real_escape_string($conn, $_POST['type']);
    $status        = mysqli_real_escape_string($conn, $_POST['status']);

    // Hidden field for edit mode
    $isEdit = isset($_POST['isEdit']) ? mysqli_real_escape_string($conn, $_POST['isEdit']) : "0";

    if ($isEdit && $isEdit != "0") {
        // ✅ Update existing appointment
        $sql = "UPDATE appointments 
                SET patientName='$patientName', doctor='$doctor', department='$department', 
                    date='$date', time='$time', type='$type', status='$status'
                WHERE appointmentId='$isEdit'";
    } else {
        // ✅ Insert new appointment
        $sql = "INSERT INTO appointments (appointmentId, patientName, doctor, department, date, time, type, status) 
                VALUES ('$appointmentId', '$patientName', '$doctor', '$department', '$date', '$time', '$type', '$status')";
    }

    mysqli_query($conn, $sql);
    header("Location: appointment.php");
    exit;
}


// -------------------- Handle Delete Request -------------------- //
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_selected'])) {
    if (isset($_POST['ids']) && !empty($_POST['ids'])) {
        $ids = explode(",", $_POST['ids']);
        $ids = array_map(fn($id) => mysqli_real_escape_string($conn, $id), $ids);
        $idsList = "'" . implode("','", $ids) . "'";

        mysqli_query($conn, "DELETE FROM appointments WHERE appointmentId IN ($idsList)");
    }

    header("Location: appointment.php");
    exit;
}


// -------------------- Fetch all appointments -------------------- //
$result = mysqli_query($conn, "SELECT * FROM appointments");
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/appointment.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Appointments</title>
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
                    <a href="patient.php">
                        <span class="icon icon-5"><i class="bx bxs-user"></i></span>
                        <span class="sidebar--item">Patients</span>
                    </a>
                </li>
                <li>
                    <a href="appointment.php" id="active--link">
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
                                <h5 class="card--title">Total Appointments</h5>
                                <h1><?php echo $totalAppointments; ?></h1>
                            </div>
                            <i class="bx bxs-book-add card--icon--lg"></i>
                        </div>
                        <div class="card--stats">
                            <span><i class="bx bxs-bar-chart-alt-2 card--icon stst--icon"></i>91%</span>
                            <span><i class="bx bx-caret-up card--icon up--arrow"></i>11</span>
                            <span><i class="bx bxs-down-arrow-alt card--icon down--arrow"></i>2</span>
                        </div>
                    </div>
                    <div class="card card-2">
                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--title">Pending Appointments</h5>
                                <h1><?php echo $pendingAppointments; ?></h1>
                            </div>
                            <i class="bx bxs-hourglass card--icon--lg"></i>
                        </div>
                        <div class="card--stats">
                            <span><i class="bx bxs-bar-chart-alt-2 card--icon stst--icon"></i>30%</span>
                            <span><i class="bx bx-caret-up card--icon up--arrow"></i>65</span>
                            <span><i class="bx bxs-down-arrow-alt card--icon down--arrow"></i>4</span>
                        </div>
                    </div>
                    <div class="card card-3">
                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--title">Confirmed Appointments</h5>
                                <h1><?php echo $confirmedAppointments; ?></h1>
                            </div>
                            <i class="bx bxs-badge-check card--icon--lg"></i>
                        </div>
                        <div class="card--stats">
                            <span><i class="bx bxs-bar-chart-alt-2 card--icon stst--icon"></i>60%</span>
                            <span><i class="bx bx-caret-up card--icon up--arrow"></i>10</span>
                            <span><i class="bx bxs-down-arrow-alt card--icon down--arrow"></i>2</span>
                        </div>
                    </div>
                    <div class="card card-4">
                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--title">Rejected Appointments</h5>
                                <h1><?php echo $rejectedAppointments; ?></h1>
                            </div>
                            <i class="bx bxs-x-circle card--icon--lg"></i>
                        </div>
                        <div class="card--stats">
                            <span><i class="bx bxs-bar-chart-alt-2 card--icon stst--icon"></i>5%</span>
                            <span><i class="bx bx-caret-up card--icon up--arrow"></i>0</span>
                            <span><i class="bx bxs-down-arrow-alt card--icon down--arrow"></i>30</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="recent--appointments">
                <div class="title">
                    <h2 class="section--title">Appointments</h2>
                    <div class="appointments--right--btns">
                    <select name="date" id="filterDate" class="dropdown appointment--filter">
                       <option value="All">All</option>
                       <option value="Pending">Pending Appointments</option>
                       <option value="Confirmed">Confirmed Appointments</option>
                       <option value="Rejected">Rejected Appointments</option>
                    </select>
                    <button class="add-appointment"  onclick="document.getElementById('appointmentFormOverlay').style.display='flex'"><i class="bx bx-plus"></i>Add Appointment</button>
                    <button class="delete-appointment"><i class="bx bx-trash"></i> <span>Delete Appointment</span></button>
                    </div>
                </div>
                <div class="table-appointment-page-scroll">
                <div class="table-appointment-page">
                <table>
                    <thead>
                        <tr>
                            <th>Appointment ID</th>
                            <th>Patient Name</th>
                            <th>Doctor</th>
                            <th>Department</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = mysqli_query($conn, "SELECT * FROM appointments");
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr>
                                <td>'.$row['appointmentId'].'</td>
                                <td>'.$row['patientName'].'</td>
                                <td>'.$row['doctor'].'</td>
                                <td>'.$row['department'].'</td>
                                <td>'.$row['date'].'</td>
                                <td>'.$row['time'].'</td>
                                <td>'.$row['type'].'</td>
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
               <!-- Appointment Form Overlay -->
                    <div class="appointment-form-overlay" id="appointmentFormOverlay" style="display:none;">
                        <div class="appointment-form">
                            <h2>Add Appointment</h2>
                            <form id="appointmentForm"  method="POST" action="appointment.php">
                                 <input type="hidden" id="isEdit" name="isEdit" value="0">

                                <label for="appointmentId">Appointment ID</label>
                                <input type="text" id="appointmentId" name="appointmentId" placeholder="Enter Appointment ID (e.g., A001)" required>
                                
                                <label for="patientName">Patient Name</label>
                                <input type="text" id="patientName" name="patientName" placeholder="Enter Patient Name" required>
                                
                                <label for="doctor">Doctor</label>
                                <input type="text" id="doctor" name="doctor" placeholder="Enter Doctor Name" required>

                                <label for="department">Department</label>
                                <select id="department" name="department" required>
                                    <option value="">Select Department</option>
                                    <option value="Cardiology">Cardiology</option>
                                    <option value="Neurology">Neurology</option>
                                    <option value="Orthopedics">Orthopedics</option>
                                    <option value="General Medicine">General Medicine</option>
                                </select>

                                <label for="date">Date</label>
                                <input type="date" id="appointmentDate" name="date" required>

                                <label for="time">Time</label>
                                <input type="time" id="time" name="time" required>

                                <label for="type">Type</label>
                                <select id="type" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="Consultation">Consultation</option>
                                    <option value="Follow-up">Follow-up</option>
                                    <option value="Emergency">Emergency</option>
                                </select>

                                <label for="status">Status</label>
                               <select id="status" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Confirmed">Confirmed</option>
                                    <option value="Rejected">Rejected</option>
                                </select>

                                <div class="form-buttons">
                                    <button type="submit" class="submit-btn">Add</button>
                                    <button type="button" class="close-appointment" onclick="document.getElementById('appointmentFormOverlay').style.display='none'">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
    </section>
    <!-- Hidden from of delete staff-->
    <form id="deleteForm" method="POST" action="appointment.php" style="display:none;">
        <input type="hidden" name="delete_selected" value="1">
        <input type="hidden" name="ids" id="deleteIds">
    </form>

    <script src="JS/appointment.js"></script>
</body>
</html>
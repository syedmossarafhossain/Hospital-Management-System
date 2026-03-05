<?php
// Connect to DB
$servername = "localhost";
$username = "root";
$password = "";
$database = "hospitaldb";

$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


$totalDoctors = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM doctors"));
$availableDoctors = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM doctors WHERE availability='available'"));
$surgicalDoctors = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM doctors WHERE type='surgical'"));
$specialistDoctors = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM doctors WHERE type='specialist'"));

// Handle form submission (insert doctor)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $availability = mysqli_real_escape_string($conn, $_POST['availability']);
    $image = mysqli_real_escape_string($conn, $_POST['image']);

    if (empty($image)) {
        $image = "images/doctor1.jpg";
    }

    $sql = "INSERT INTO doctors (name, type, availability, image)
            VALUES ('$name', '$type', '$availability', '$image')";
    mysqli_query($conn, $sql);
    header("Location: doctor.php");
    exit;
}

// Handle Delete Doctor
if (isset($_GET['delete'])) {
    $doctor_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM doctors WHERE doctor_id=$doctor_id");
    header("Location: doctor.php");
    exit;
}

// Fetch doctors
$result = mysqli_query($conn, "SELECT * FROM doctors");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/doctor.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Doctors</title>
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
                    <a href="doctor.php" id="active--link">
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
                        <div class="card--data"><div class="card--content">
                            <h5 class="card--title">Total Doctors</h5>
                            <h1 id="totalDoctors"><?php echo mysqli_num_rows($result); ?></h1>
                        </div>
                        <i class="fa-solid fa-user-doctor card--icon--lg"></i>
                    </div>
                    <div class="card--stats">
                            <span><i class="bx bxs-bar-chart-alt-2 card--icon stst--icon"></i>65%</span>
                            <span><i class="bx bx-caret-up card--icon up--arrow"></i>10</span>
                            <span><i class="bx bxs-down-arrow-alt card--icon down--arrow"></i>2</span>
                    </div>
                </div>
                    <div class="card card-2">
                        <div class="card--data"><div class="card--content">
                            <h5 class="card--title">Available Doctors</h5>
                            <h1 id="availableDoctors"><?php echo mysqli_num_rows(mysqli_query($conn,"SELECT * FROM doctors WHERE availability='available'")); ?></h1>
                        </div>
                        <i class="fa-solid fa-user-check card--icon--lg"></i>
                    </div>
                    <div class="card--stats">
                            <span><i class="bx bxs-bar-chart-alt-2 card--icon stst--icon"></i>94%</span>
                            <span><i class="bx bx-caret-up card--icon up--arrow"></i>89</span>
                            <span><i class="bx bxs-down-arrow-alt card--icon down--arrow"></i>11</span>
                        </div>
                </div>
                    <div class="card card-3">
                        <div class="card--data"
                        ><div class="card--content">
                            <h5 class="card--title">Surgical Doctors</h5>
                            <h1 id="surgicalDoctors"><?php echo mysqli_num_rows(mysqli_query($conn,"SELECT * FROM doctors WHERE type='surgical'")); ?></h1>
                        </div>
                        <i class="bx bxs-first-aid card--icon--lg"></i>
                    </div>
                    <div class="card--stats">
                            <span><i class="bx bxs-bar-chart-alt-2 card--icon stst--icon"></i>79%</span>
                            <span><i class="bx bx-caret-up card--icon up--arrow"></i>72</span>
                            <span><i class="bx bxs-down-arrow-alt card--icon down--arrow"></i>25</span>
                        </div>
                </div>
                    <div class="card card-4">
                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--title">Specialist Doctors</h5>
                                <h1 id="specialistDoctors"><?php echo mysqli_num_rows(mysqli_query($conn,"SELECT * FROM doctors WHERE type='specialist'")); ?></h1>
                            </div>
                            <i class="bx bxs-medal card--icon--lg"></i>
                        </div>
                        <div class="card--stats">
                            <span><i class="bx bxs-bar-chart-alt-2 card--icon stst--icon"></i>8%</span>
                            <span><i class="bx bx-caret-up card--icon up--arrow"></i>11</span>
                            <span><i class="bx bxs-down-arrow-alt card--icon down--arrow"></i>3</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="doctors">
                <div class="title">
                    <h2 class="section--title">Doctors</h2>
                    <div class="doctors--right--btns">
                        <select name="data" id="data" class="dropdown doctor--filter">
                            <option>Filter</option>
                            <option value="available_Doctors">Available Doctors</option>
                            <option value="surgical_Doctors">Surgical Doctors</option>
                            <option value="specialist_Doctors">Specialist Doctors</option>
                        </select>
                        <button class="add-doctor" onclick="document.getElementById('doctorFormOverlay').style.display='flex'"><i class="bx bx-plus"></i>Add Doctor</button>
                       <button class="delete-doctor"><i class="bx bx-trash"></i> Delete Doctor</button>
                    </div>
                </div>
                <div class="doctors-scroll">
                    <!-- Add Doctor Form -->
                    <div class="doctor-form-overlay" id="doctorFormOverlay" style="display:none;">
                        <div class="doctor-form">
                            <h2>Add Doctor</h2>
                            <form id="doctorForm" method="POST" action="">
                                <label for="name">Doctor Name</label>
                                <input type="text" id="name" name="name" required>

                                <label for="type">Doctor Type</label>
                                <select id="type" name="type" required>
                                    <option value="specialist">Specialist</option>
                                    <option value="surgical">Surgical</option>
                                </select>

                                <label for="availability">Availability</label>
                                <select id="availability" name="availability" required>
                                    <option value="available">Available</option>
                                    <option value="unavailable">Unavailable</option>
                                </select>

                                <label for="image">Image URL</label>
                                <input type="text" id="image" name="image" placeholder="Enter image URL (optional)">

                                <div class="form-buttons">
                                    <button type="submit" class="submit-btn">Add</button>
                                    <button type="button" class="close-doctor" onclick="document.getElementById('doctorFormOverlay').style.display='none'">Cancel</button>
                        
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Display doctors -->
                    <div class="doctors--cards">
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <a href="#" class="doctor--card" 
                                data-id="<?php echo $row['doctor_id']; ?>" 
                                data-type="<?php echo strtolower($row['type']); ?>" 
                                data-availability="<?php echo strtolower($row['availability']); ?>">

                                <div class="img--box--cover">
                                    <div class="img--box">
                                        <img src="<?php echo $row['image']; ?>" alt="doctor">
                                    </div>
                                </div>
                                <p class="name">Dr. <?php echo htmlspecialchars($row['name']); ?></p>
                                <p class="<?php echo strtolower($row['type']); ?>">(<?php echo ucfirst($row['type']); ?>)</p>
                                <p class="availability <?php echo strtolower($row['availability']); ?>">[<?php echo ucfirst($row['availability']); ?>]</p>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Hidden delete form -->
<form id="deleteForm" method="GET" action="doctor.php" style="display:none;">
    <input type="hidden" name="delete" id="deleteDoctorId">
</form>
    <script src="JS/doctor.js"></script>
</body>
</html>

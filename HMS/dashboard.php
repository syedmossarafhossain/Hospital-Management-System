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
//Over view card//
$totalCapacity = 2000;
$totalDoctors = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM doctors"));
$totalPatients = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM patients"));
$totalNurses = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM nurses"));
$totalBeds = $totalCapacity - $totalPatients;

// Fetch doctors and patients
$doctorsResult = mysqli_query($conn, "SELECT * FROM doctors");
$patientsResult = mysqli_query($conn, "SELECT * FROM patients");



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="CSS/dashboard.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                    <a href="dashboard.php" id="active--link">
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
                                <h5 class="card--title">Total Doctors</h5>
                                <h1><?php echo $totalDoctors; ?></h1>
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
                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--title">Total Patients</h5>
                                <h1><?php echo $totalPatients; ?></h1>
                            </div>
                            <i class="bx bxs-user card--icon--lg"></i>
                        </div>
                        <div class="card--stats">
                            <span><i class="bx bxs-bar-chart-alt-2 card--icon stst--icon"></i>82%</span>
                            <span><i class="bx bx-caret-up card--icon up--arrow"></i>230</span>
                            <span><i class="bx bxs-down-arrow-alt card--icon down--arrow"></i>45</span>
                        </div>
                    </div>
                    <div class="card card-3">
                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--title">Total Nurses</h5>
                                <h1><?php echo $totalNurses; ?></h1>
                            </div>
                            <i class="bx bxs-calendar card--icon--lg"></i>
                        </div>
                        <div class="card--stats">
                            <span><i class="bx bxs-bar-chart-alt-2 card--icon stst--icon"></i>27%</span>
                            <span><i class="bx bx-caret-up card--icon up--arrow"></i>31</span>
                            <span><i class="bx bxs-down-arrow-alt card--icon down--arrow"></i>23</span>
                        </div>
                    </div>
                    <div class="card card-4">
                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--title">Beds Available</h5>
                                <h1><?php echo $totalBeds; ?></h1>
                            </div>
                            <i class="fa-solid fa-bed card--icon--lg"></i>
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
                    </div>
                </div>
                <div class="doctors-scroll-1">
                <div class="doctors--cards">
                   <?php while ($row = mysqli_fetch_assoc($doctorsResult)) { ?>
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
            <div class="recent--patients">
                <div class="title">
                    <h2 class="section--title">Patients</h2>
                    <div class="patients--right--btns">
                    <select name="date" id="date" class="dropdown patient--filter">
                       <option value="All">All</option>
                       <option value="Admitted">Admitted</option>
                       <option value="Discharged">Discharged</option>
                       <option value="Critical Cases">Critical Cases</option>
                    </select>
                    </div>
                </div>
                <div class="table">
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
                        </tr>';
                        }
                        ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </section>


<script src="JS/dashboard.js"></script>
</body>
</html>
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

// -------------------- Overview counts --------------------
$totalStaffs = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM staffs"));
$supportStaffs = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM staffs WHERE role='Support Staff'"));
$administrativeStaffs = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM staffs WHERE role='Administrative Staff'"));
$technicalStaffs = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM staffs WHERE role='Technical Staff'"));


// Handle form submission (insert or update staff)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['staffId'])) {
    $staffId = mysqli_real_escape_string($conn, $_POST['staffId']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $shift = mysqli_real_escape_string($conn, $_POST['shift']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // ✅ Get hidden field for edit mode
    $isEdit = isset($_POST['isEdit']) ? mysqli_real_escape_string($conn, $_POST['isEdit']) : "0";

    if ($isEdit && $isEdit != "0") {
        // Update existing staff
        $sql = "UPDATE staffs 
                SET name='$name', gender='$gender', contact='$contact', role='$role', shift='$shift', status='$status' 
                WHERE staffId='$isEdit'";
    } else {
        // Insert new staff
        $sql = "INSERT INTO staffs (staffId, name, gender, contact, role, shift, status) 
                VALUES ('$staffId', '$name', '$gender', '$contact', '$role', '$shift', '$status')";
    }

    mysqli_query($conn, $sql);
    header("Location: staff.php");
    exit;
}


// Handle Delete Request (for Staffs)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_selected'])) {
    $ids = explode(",", $_POST['ids']);
    $ids = array_map(fn($id) => mysqli_real_escape_string($conn, $id), $ids);
    $idsList = "'" . implode("','", $ids) . "'";
    
    mysqli_query($conn, "DELETE FROM staffs WHERE staffId IN ($idsList)");
    
    header("Location: staff.php");
    exit;
}


// Fetch nurses
$result = mysqli_query($conn, "SELECT * FROM staffs");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/staff.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Staff</title>
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
                    <a href="staff.php" id="active--link">
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
                                <h5 class="card--title">Total Staff</h5>
                                <h1><?php echo $totalStaffs; ?></h1>
                            </div>
                            <i class="fa-solid fa-users card--icon--lg"></i>
                        </div>
                        <div class="card--stats">
                            <span><i class="bx bxs-bar-chart-alt-2 card--icon stst--icon"></i>99%</span>
                            <span><i class="bx bx-caret-up card--icon up--arrow"></i>89</span>
                            <span><i class="bx bxs-down-arrow-alt card--icon down--arrow"></i>8</span>
                        </div>
                    </div>
                    <div class="card card-2">
                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--title">Support Staff</h5>
                                <h1><?php echo $supportStaffs; ?></h1>
                            </div>
                            <i class="fa-solid fa-id-card card--icon--lg"></i>
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
                                <h5 class="card--title">Administrative Staff</h5>
                                <h1><?php echo $administrativeStaffs; ?></h1>
                            </div>
                            <i class="bx bxs-briefcase card--icon--lg"></i>
                        </div>
                        <div class="card--stats">
                            <span><i class="bx bxs-bar-chart-alt-2 card--icon stst--icon"></i>90%</span>
                            <span><i class="bx bx-caret-up card--icon up--arrow"></i>81</span>
                            <span><i class="bx bxs-down-arrow-alt card--icon down--arrow"></i>9</span>
                        </div>
                    </div>
                    <div class="card card-4">
                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--title">Technical Staff</h5>
                                <h1><?php echo $technicalStaffs; ?></h1>
                            </div>
                            <i class="bx bxs-wrench card--icon--lg"></i>
                        </div>
                        <div class="card--stats">
                            <span><i class="bx bxs-bar-chart-alt-2 card--icon stst--icon"></i>78%</span>
                            <span><i class="bx bx-caret-up card--icon up--arrow"></i>70</span>
                            <span><i class="bx bxs-down-arrow-alt card--icon down--arrow"></i>8</span>
                        </div>
                    </div>
                </div>
            </div>
             <div class="staffs">
        <div class="title">
             <h2 class="section--title">Staffs</h2>
            <div class="staffs--right--btns">
                <select name="date" id="date" class="dropdown staff--filter">
                    <option>Filter</option>
                    <option value="support_Staffs">Supporting Staffs</option>
                    <option value="administrative_Staffs">Administrative Staffs</option>
                    <option value="technical_Staffs">Technical Staffs</option>
                </select>
                <button class="add-staff" onclick="document.getElementById('staffFormOverlay').style.display='flex'"><i class="bx bx-plus"></i>Add Staff</button>
                <button class="delete-staff"><i class="bx bx-trash"></i> <span>Delete Staff</span></button>
            </div>
        </div>
        <div class="table-staff-page-scroll">
            <div class="table-staff-page">
                <table>
                    <thead>
                        <tr>
                            <th>Staff ID</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Contact</th>
                            <th>Role</th>
                            <th>Shift</th>
                            <th>Status</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = mysqli_query($conn, "SELECT * FROM staffs");
                        while($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>
                            <td>'.$row['staffId'].'</td>
                            <td>'.$row['name'].'</td>
                            <td>'.$row['gender'].'</td>
                            <td>'.$row['contact'].'</td>
                            <td>'.$row['role'].'</td>
                            <td>'.$row['shift'].'</td>
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

        <!-- Staff Form Overlay -->
        <div class="staff-form-overlay" id="staffFormOverlay" style="display:none;">
            <div class="staff-form">
                <h2>Add Staff</h2>
                <form id="staffForm" method="POST" action="">
                    <input type="hidden" name="isEdit" id="isEdit" value="0">
                    <label for="staffId">Staff ID</label>
                    <input type="text" id="staffId" name="staffId" placeholder="Enter Staff ID (e.g., STF001)" required>
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter Name" required>
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender" required>
                        <option value="Female">Female</option>
                        <option value="Male">Male</option>
                    </select>

                    <label for="contact">Contact</label>
                    <input type="text" id="contact" name="contact" placeholder="Enter Contact Number" required>

                    <label for="role">Role</label>
                    <select id="role" name="role" required>
                        <option value="Support Staff">Supporting Staff</option>
                        <option value="Administrative Staff">Administrative Staff</option>
                        <option value="Technical Staff">Technical Staff</option>
                    </select>

                    <label for="shift">Shift</label>
                    <select id="shift" name="shift" required>
                        <option value="Day">Day</option>
                        <option value="Night">Night</option>
                    </select>

                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>

                    <div class="form-buttons">
                        <button type="submit" class="submit-btn">Add</button>
                        <button type="button" class="close-staff" onclick="document.getElementById('staffFormOverlay').style.display='none'">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
        <!-- Hidden from of delete staff-->
    <form id="deleteForm" method="POST" action="staff.php" style="display:none;">
        <input type="hidden" name="delete_selected" value="1">
        <input type="hidden" name="ids" id="deleteIds">
    </form>

    <!-- ✅ separate JS file -->
    <script src="JS/staff.js"></script>
</body>
</html>
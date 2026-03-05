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

// Overview counts
$totalNurses = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM nurses"));
$ondutyNurses = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM nurses WHERE status='On Duty'"));
$generalwardNurses = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM nurses WHERE ward='General'"));
$icuwardNurses = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM nurses WHERE ward='ICU'"));


// Handle form submission (insert or update nurse)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nurseId'])) {
    $nurseId = mysqli_real_escape_string($conn, $_POST['nurseId']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $ward = mysqli_real_escape_string($conn, $_POST['ward']);
    $shift = mysqli_real_escape_string($conn, $_POST['shift']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // ✅ Get the hidden isEdit field
    $isEdit = isset($_POST['isEdit']) ? mysqli_real_escape_string($conn, $_POST['isEdit']) : "0";

    if ($isEdit && $isEdit != "0") {
        // Update existing nurse
        $sql = "UPDATE nurses 
                SET name='$name', gender='$gender', contact='$contact', ward='$ward', shift='$shift', status='$status' 
                WHERE nurseId='$isEdit'";
    } else {
        // Insert new nurse
        $sql = "INSERT INTO nurses (nurseId, name, gender, contact, ward, shift, status) 
                VALUES ('$nurseId', '$name', '$gender', '$contact', '$ward', '$shift', '$status')";
    }

    mysqli_query($conn, $sql);
    header("Location: nurse.php");
    exit;
}

// Handle Delete Request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_selected'])) {
    $ids = explode(",", $_POST['ids']);
    $ids = array_map(fn($id) => mysqli_real_escape_string($conn, $id), $ids);
    $idsList = "'" . implode("','", $ids) . "'";
    mysqli_query($conn, "DELETE FROM nurses WHERE nurseId IN ($idsList)");
    header("Location: nurse.php");
    exit;
}

// Fetch nurses
$result = mysqli_query($conn, "SELECT * FROM nurses");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nurses</title>
    <link rel="stylesheet" href="CSS/nurse.css">
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
                    <a href="dashboard.php" >
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
                    <a href="nurse.php" id="active--link">
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
                                <h5 class="card--title">Total Nurses</h5>
                                <h1 id="totalNurses"><?php echo $totalNurses; ?></h1>
                            </div>
                            <i class="fa-solid fa-user-nurse card--icon--lg"></i>
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
                                <h5 class="card--title">On Duty Nurses</h5>
                                <h1 id="ondutyNurses"><?php echo $ondutyNurses; ?></h1>
                            </div>
                            <i class="fas fa-clock card--icon--lg"></i>
                        </div>
                        <div class="card--stats">
                            <span><i class="bx bxs-bar-chart-alt-2 card--icon stst--icon"></i>94%</span>
                            <span><i class="bx bx-caret-up card--icon up--arrow"></i>89</span>
                            <span><i class="bx bxs-down-arrow-alt card--icon down--arrow"></i>11</span>
                        </div>
                    </div>
                    <div class="card card-3">
                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--title"> General Ward</h5>
                                <h1 id="generalwardNurses"><?php echo $generalwardNurses; ?></h1>
                            </div>
                            <i class="fa-solid fa-hospital card--icon--lg"></i>
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
                                <h5 class="card--title">ICU Ward</h5>
                                <h1 id="icuwardNurses"><?php echo $icuwardNurses; ?></h1>
                            </div>
                            <i class="fas fa-heartbeat card--icon--lg"></i>
                        </div>
                        <div class="card--stats">
                            <span><i class="bx bxs-bar-chart-alt-2 card--icon stst--icon"></i>8%</span>
                            <span><i class="bx bx-caret-up card--icon up--arrow"></i>11</span>
                            <span><i class="bx bxs-down-arrow-alt card--icon down--arrow"></i>3</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="nurses">
                <div class="title">
                    <h2 class="section--title">Nurses</h2>
                    <div class="nurses--right--btns">
                        <select name="date" id="date" class="dropdown nurse--filter">
                            <option>Filter</option>
                            <option value="On_Duty_Nurses">On Duty Nurses</option>
                            <option value="General_Ward_Nurses">General Ward Nurses</option>
                            <option value="ICU_Ward_Nurses">ICU Ward Nurses</option>
                        </select>
                        <button class="add-nurse" onclick="document.getElementById('nurseFormOverlay').style.display='flex'"><i class="bx bx-plus"></i>Add Nurse</button>
                        <button class="delete-nurse"><i class="bx bx-trash"></i> <span>Delete Nurse</span></button>
                    </div>
                </div>
                <div class="table-nurse-page-scroll">
                <div class="table-nurse-page">
                <table>
                    <thead>
                        <tr>
                            <th>Nurse ID</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Contact</th>
                            <th>Ward</th>
                            <th>Shift</th>
                            <th>Status</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = mysqli_query($conn, "SELECT * FROM nurses");
                        while($row = mysqli_fetch_assoc($result)) {
                            echo '<tr>
                                <td>'.$row['nurseId'].'</td>
                                <td>'.$row['name'].'</td>
                                <td>'.$row['gender'].'</td>
                                <td>'.$row['contact'].'</td>
                                <td>'.$row['ward'].'</td>
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

                    <!-- Nurse Form Overlay -->
                <div class="nurse-form-overlay" id="nurseFormOverlay" style="display:none;">
                    <div class="nurse-form">
                        <h2>Add Nurse</h2>
                            <form id="nurseForm" method="POST" action="">
                                <input type="hidden" name="isEdit" id="isEdit" value="0">
                                <label for="nurseId">Nurse ID</label>
                                <input type="text" id="nurseId" name="nurseId" placeholder="Enter Nurse ID" required>
                                <label for="name">Name</label>
                                <input type="text" id="name" name="name" placeholder="Enter Name" required>
                                <label for="gender">Gender</label>
                                <select id="gender" name="gender" required>
                                    <option value="Female">Female</option>
                                    <option value="Male">Male</option>
                                </select>

                                <label for="contact">Contact</label>
                                <input type="text" id="contact" name="contact" placeholder="Enter Contact" required>

                                <label for="ward">Ward</label>
                                <select id="ward" name="ward" required>
                                    <option value="General">General Ward</option>
                                    <option value="ICU">ICU Ward</option>
                                </select>

                                <label for="shift">Shift</label>
                                <select id="shift" name="shift" required>
                                    <option value="Day">Day</option>
                                    <option value="Night">Night</option>
                                </select>

                                <label for="status">Status</label>
                                <select id="status" name="status" required>
                                    <option value="On Duty">On Duty</option>
                                    <option value="Off Duty">Off Duty</option>
                                </select>

                                <div class="form-buttons">
                                    <button type="submit" class="submit-btn">Add</button>
                                    <button type="button" class="close-nurse" onclick="document.getElementById('nurseFormOverlay').style.display='none'">Cancel</button>
                                </div>
                      </form>
                 </div>
             </div>
    </section>
                    <!-- Hidden from of delete nurse-->
 <form id="deleteForm" method="POST" action="nurse.php" style="display:none;">
    <input type="hidden" name="delete_selected" value="1">
    <input type="hidden" name="ids" id="deleteIds">
</form>

<script src="JS/nurse.js"></script>
</body>
</html>


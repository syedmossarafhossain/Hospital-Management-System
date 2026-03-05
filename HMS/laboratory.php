<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "hospitaldb";

// ✅ Connect to DB
$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// -------------------- Overview counts -------------------- //
$totalTests      = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM laboratorys"));
$pendingTests    = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM laboratorys WHERE status='Pending'"));
$completedTests  = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM laboratorys WHERE status='Completed'"));
$analysisTests   = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM laboratorys WHERE status='Analysis'"));


// -------------------- Handle form submission (Insert / Update) -------------------- //
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['testId'])) {
    $testId      = mysqli_real_escape_string($conn, $_POST['testId']);
    $patientName = mysqli_real_escape_string($conn, $_POST['patientName']);
    $gender      = mysqli_real_escape_string($conn, $_POST['gender']);
    $contact     = mysqli_real_escape_string($conn, $_POST['contact']);
    $testName    = mysqli_real_escape_string($conn, $_POST['testName']);
    $requestedBy = mysqli_real_escape_string($conn, $_POST['requestedBy']);
    $date        = mysqli_real_escape_string($conn, $_POST['date']);
    $charges     = mysqli_real_escape_string($conn, $_POST['charges']);
    $status      = mysqli_real_escape_string($conn, $_POST['status']);

    // Hidden field for edit mode
    $isEdit = isset($_POST['isEdit']) ? mysqli_real_escape_string($conn, $_POST['isEdit']) : "0";

    if ($isEdit && $isEdit != "0") {
        // ✅ Update existing lab test
        $sql = "UPDATE laboratorys 
                SET patientName='$patientName', gender='$gender', contact='$contact', 
                    testName='$testName', requestedBy='$requestedBy', date='$date', 
                    charges='$charges', status='$status'
                WHERE testId='$isEdit'";
    } else {
        // ✅ Insert new lab test
        $sql = "INSERT INTO laboratorys (testId, patientName, gender, contact, testName, requestedBy, date, charges, status) 
                VALUES ('$testId', '$patientName', '$gender', '$contact', '$testName', '$requestedBy', '$date', '$charges', '$status')";
    }

    mysqli_query($conn, $sql);
    header("Location: laboratory.php");
    exit;
}


// -------------------- Handle Delete Request -------------------- //
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_selected'])) {
    if (isset($_POST['ids']) && !empty($_POST['ids'])) {
        $ids = explode(",", $_POST['ids']);
        $ids = array_map(fn($id) => mysqli_real_escape_string($conn, $id), $ids);
        $idsList = "'" . implode("','", $ids) . "'";

        mysqli_query($conn, "DELETE FROM laboratorys WHERE testId IN ($idsList)");
    }

    header("Location: laboratory.php");
    exit;
}


// -------------------- Fetch all laboratory tests -------------------- //
$result = mysqli_query($conn, "SELECT * FROM laboratorys");
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/laboratory.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Laboratory</title>
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
                    <a href="appointment.php">
                        <span class="icon icon-2"><i class="bx bxs-book-add"></i></span>
                        <span class="sidebar--item">Appointments</span>
                    </a>
                </li>
                <li>
                    <a href="laboratory.php" id="active--link">
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
                                <h5 class="card--title">Total Tests Conducted</h5>
                                <h1><?php echo $totalTests; ?></h1>
                            </div>
                            <i class="bx bxs-flask card--icon--lg"></i>
                        </div>
                        <div class="card--stats">
                            <span><i class="bx bxs-bar-chart-alt-2 card--icon stst--icon"></i>87%</span>
                            <span><i class="bx bx-caret-up card--icon up--arrow"></i>200</span>
                            <span><i class="bx bxs-down-arrow-alt card--icon down--arrow"></i>30</span>
                        </div>
                    </div>
                    <div class="card card-2">
                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--title">Pending Tests</h5>
                                <h1><?php echo $pendingTests; ?></h1>
                            </div>
                            <i class="bx bx-loader-circle card--icon--lg"></i>
                        </div>
                        <div class="card--stats">
                            <span><i class="bx bxs-bar-chart-alt-2 card--icon stst--icon"></i>100%</span>
                            <span><i class="bx bx-caret-up card--icon up--arrow"></i>96</span>
                            <span><i class="bx bxs-down-arrow-alt card--icon down--arrow"></i>8</span>
                        </div>
                    </div>
                    <div class="card card-3">
                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--title">Completed Tests</h5>
                                <h1><?php echo $completedTests; ?></h1>
                            </div>
                            <i class="bx bxs-check-circle card--icon--lg"></i>
                        </div>
                        <div class="card--stats">
                            <span><i class="bx bxs-bar-chart-alt-2 card--icon stst--icon"></i>97%</span>
                            <span><i class="bx bx-caret-up card--icon up--arrow"></i>93</span>
                            <span><i class="bx bxs-down-arrow-alt card--icon down--arrow"></i>3</span>
                        </div>
                    </div>
                    <div class="card card-4">
                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--title">Analysis Report</h5>
                                <h1><?php echo $analysisTests; ?></h1>
                            </div>
                            <i class="bx bx-line-chart card--icon--lg"></i>
                        </div>
                        <div class="card--stats">
                            <span><i class="bx bxs-bar-chart-alt-2 card--icon stst--icon"></i>87%</span>
                            <span><i class="bx bx-caret-up card--icon up--arrow"></i>78</span>
                            <span><i class="bx bxs-down-arrow-alt card--icon down--arrow"></i>13</span>
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="recent--laboratorys">
                <div class="title">
                    <h2 class="section--title">Tests</h2>
                   <div class="laboratorys--right--btns">
                    <select name="date" id="statusFilter" class="dropdown laboratory--filter">
                       <option value="All">All</option>
                       <option value="Pending">Pending Tests</option>
                       <option value="Completed">Completed Tests</option>
                       <option value="Analysis">Analysis Reports</option>
                    </select>
                    <button class="add-laboratory" onclick="document.getElementById('laboratoryFormOverlay').style.display='flex'"><i class="bx bx-plus"></i>Add Report/Test</button>
                    <button class="delete-laboratory"><i class="bx bx-trash"></i> <span>Delete Report/Test</span></button>
                    </div>
                </div>
                <div class="table-laboratory-page-scroll">
                <div class="table-laboratory-page">
                <table>
                    <thead>
                        <tr>
                            <th>Test ID</th>
                            <th>Patient Name</th>
                            <th>Gender</th>
                            <th>Contact</th>
                            <th>Test Name</th>
                            <th>Requested By</th>
                            <th>Date</th>
                            <th>Charges</th>
                            <th>Status</th>
                            <th>Edit</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = mysqli_query($conn, "SELECT * FROM laboratorys");
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr>
                                <td>'.$row['testId'].'</td>
                                <td>'.$row['patientName'].'</td>
                                <td>'.$row['gender'].'</td>
                                <td>'.$row['contact'].'</td>
                                <td>'.$row['testName'].'</td>
                                <td>'.$row['requestedBy'].'</td>
                                <td>'.$row['date'].'</td>
                                <td>'.$row['charges'].'</td>
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
           <!-- Test Form Overlay -->
                <div class="laboratory-form-overlay" id="laboratoryFormOverlay" style="display:none;">
                    <div class="laboratory-form">
                        <h2 id="formTitle">Add Test</h2>
                        <form id="laboratoryForm" method="POST" action="laboratory.php">
                            <input type="hidden" id="isEdit" name="isEdit" value="0">


                            <label for="testId">Test ID</label>
                            <input type="text" id="testId" name="testId" placeholder="Enter Test ID (e.g., T001)" required>

                            <label for="patientName">Patient Name</label>
                            <input type="text" id="patientName" name="patientName" placeholder="Enter Patient Name" required>

                            <label for="gender">Gender</label>
                            <select id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>

                            <label for="contact">Contact</label>
                            <input type="text" id="contact" name="contact" placeholder="Enter Contact Number" required>

                           <label for="testName">Test Name</label>
                            <select id="testName" name="testName" required>
                                <option value="">Select Test</option>

                                <!-- Blood Tests -->
                                <optgroup label="🩸 Blood Tests">
                                    <option value="Complete Blood Count (CBC)">Complete Blood Count (CBC)</option>
                                    <option value="Hemoglobin (Hb)">Hemoglobin (Hb)</option>
                                    <option value="Hematocrit (HCT)">Hematocrit (HCT)</option>
                                    <option value="Blood Sugar (FBS)">Blood Sugar (FBS)</option>
                                    <option value="Blood Sugar (RBS)">Blood Sugar (RBS)</option>
                                    <option value="HbA1c">HbA1c</option>
                                    <option value="Lipid Profile">Lipid Profile (Cholesterol, HDL, LDL, Triglycerides)</option>
                                    <option value="Liver Function Test (LFT)">Liver Function Test (LFT)</option>
                                    <option value="Kidney Function Test (KFT)">Kidney Function Test (KFT, Urea, Creatinine, Uric Acid)</option>
                                    <option value="Thyroid Function Test">Thyroid Function Test (T3, T4, TSH)</option>
                                    <option value="Electrolytes">Electrolytes (Na, K, Cl, Ca, Mg)</option>
                                    <option value="Coagulation Profile">Coagulation Profile (PT, INR, APTT)</option>
                                    <option value="Blood Grouping & Cross Matching">Blood Grouping & Cross Matching</option>
                                </optgroup>

                                <!-- Urine Tests -->
                                <optgroup label="💧 Urine Tests">
                                    <option value="Routine Urine Examination (R/E)">Routine Urine Examination (R/E)</option>
                                    <option value="Urine Culture & Sensitivity (C/S)">Urine Culture & Sensitivity (C/S)</option>
                                    <option value="24-Hour Urine Protein">24-Hour Urine Protein</option>
                                    <option value="Urine Pregnancy Test (UPT)">Urine Pregnancy Test (UPT)</option>
                                </optgroup>

                                <!-- Stool Tests -->
                                <optgroup label="🧪 Stool Tests">
                                    <option value="Routine Stool Examination (R/E)">Routine Stool Examination (R/E)</option>
                                    <option value="Stool Occult Blood Test">Stool Occult Blood Test</option>
                                    <option value="Stool Culture">Stool Culture</option>
                                </optgroup>

                                <!-- Microbiology / Infection Tests -->
                                <optgroup label="🦠 Microbiology / Infection Tests">
                                    <option value="Blood Culture">Blood Culture</option>
                                    <option value="Sputum Culture">Sputum Culture</option>
                                    <option value="Throat Swab Culture">Throat Swab Culture</option>
                                    <option value="Widal Test (Typhoid)">Widal Test (Typhoid)</option>
                                    <option value="Dengue Test">Dengue Test</option>
                                    <option value="Malaria Test">Malaria Test</option>
                                    <option value="Chikungunya Test">Chikungunya Test</option>
                                    <option value="HIV Test">HIV Test</option>
                                    <option value="Hepatitis B & C Tests">Hepatitis B & C Tests</option>
                                    <option value="VDRL (Syphilis)">VDRL (Syphilis)</option>
                                </optgroup>

                                <!-- Immunology & Special Tests -->
                                <optgroup label="🧬 Immunology & Special Tests">
                                    <option value="ANA (Antinuclear Antibody)">ANA (Antinuclear Antibody)</option>
                                    <option value="CRP (C-Reactive Protein)">CRP (C-Reactive Protein)</option>
                                    <option value="ESR (Erythrocyte Sedimentation Rate)">ESR (Erythrocyte Sedimentation Rate)</option>
                                    <option value="Vitamin D">Vitamin D</option>
                                    <option value="Vitamin B12">Vitamin B12</option>
                                    <option value="Ferritin">Ferritin</option>
                                    <option value="Iron Studies">Iron Studies</option>
                                    <option value="Tumor Markers (PSA, CA-125, CEA, AFP)">Tumor Markers (PSA, CA-125, CEA, AFP)</option>
                                </optgroup>

                                <!-- Radiology & Imaging -->
                                <optgroup label="🖼 Radiology & Imaging">
                                    <option value="X-Ray">X-Ray</option>
                                    <option value="Ultrasound (USG)">Ultrasound (USG)</option>
                                    <option value="CT Scan">CT Scan</option>
                                    <option value="MRI Scan">MRI Scan</option>
                                    <option value="ECG (Electrocardiogram)">ECG (Electrocardiogram)</option>
                                    <option value="Echocardiography">Echocardiography</option>
                                    <option value="Mammography">Mammography</option>
                                </optgroup>
                            </select>


                            <label for="requestedBy">Requested By</label>
                            <input type="text" id="requestedBy" name="requestedBy" placeholder="Requested By Doctor/Staff" required>

                            <label for="date">Date</label>
                            <input type="date" id="testDate" name="date" required>

                            <label for="charges">Charges</label>
                            <input type="number" id="charges" name="charges" placeholder="Enter Charges" required>

                            <label for="status">Status</label>
                               <select id="status" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Completed">Completed</option>
                                    <option value="Analysis">Analysis</option>
                                </select>

                            <div class="form-buttons">
                                <button type="submit" class="submit-btn">Add Test</button>
                                <button type="button" class="close-laboratory" onclick="document.getElementById('laboratoryFormOverlay').style.display='none'">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
    </section>

    <!-- Hidden from of delete staff-->
    <form id="deleteForm" method="POST" action="laboratory.php" style="display:none;">
        <input type="hidden" name="delete_selected" value="1">
        <input type="hidden" name="ids" id="deleteIds">
    </form>

<script src="JS/laboratory.js"></script>
</body>
</html>
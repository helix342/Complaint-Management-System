<?php
include("db.php");
session_start();
if(isset($_POST['faculty_id'])){
    $faculty_id = $_SESSION['faculty_id']; //'faculty_id' is stored in session
}
if(isset($_POST['hod_id'])){
    $hod_id =  $_session['hod_id'];//hod id in session
}
if(isset($_POST['eo_id'])){
    $eo_id = $_session['eo_id'];//eo id in session
}
if(isset($_POST['worker_id'])){
    $worker_id = $_SESSION['worker_id'];//worker id in session
}



$action = $_GET['action'] ?? '';

switch($action){
    //viewing complaint description in modal
    case'view_complaint':

    $complain_id = $_POST['user_id'];
    $fac_id = $_POST['fac_id'];

    // First query
    $query = "
        SELECT cd.*, faculty_details.faculty_name, faculty_details.faculty_contact, 
               faculty_details.faculty_mail, faculty_details.department, cd.block_venue
        FROM complaints_detail cd
        JOIN faculty_details ON cd.faculty_id = faculty_details.faculty_id
        WHERE cd.id = ?
    ";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $complain_id);
    mysqli_stmt_execute($stmt);
    $User_data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);

    // Second query
    $query1 = "SELECT * FROM faculty WHERE id = ?";
    $stmt1 = mysqli_prepare($conn, $query1);
    mysqli_stmt_bind_param($stmt1, "s", $fac_id);
    mysqli_stmt_execute($stmt1);
    $fac_data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt1));
    mysqli_stmt_close($stmt1);

    // Response
    if ($User_data && $fac_data) {
        echo json_encode([
            'status' => 200,
            'message' => 'Details fetched successfully by ID',
            'data' => $User_data,
            'data1' => $fac_data
        ]);
    } else {
        echo json_encode([
            'status' => 404,
            'message' => 'Details not found'
        ]);
    }
    break;

    case'get_aimage':

        $problem_id = isset($_POST['problem2_id']) ? $_POST['problem2_id'] : '';

    // Validate problem_id
    if (empty($problem_id)) {
        echo json_encode(['status' => 400, 'message' => 'Problem ID not provided']);
        exit;
    }

    // Log the received problem_id for debugging
    error_log("Problem ID received: " . $problem_id);

    // First, fetch the task_id from the manager table using the problem_id
    $query = "SELECT task_id FROM manager WHERE problem_id = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        echo json_encode(['status' => 500, 'message' => 'Prepare statement failed: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param('i', $problem_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $task_id = $row['task_id'];

        // Log the fetched task_id for debugging
        error_log("Task ID fetched: " . $task_id);

        $stmt->close();

        // Now, fetch the after_photo using the retrieved task_id
        $query = "SELECT after_photo FROM worker_taskdet WHERE task_id = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            echo json_encode(['status' => 500, 'message' => 'Prepare statement failed: ' . $conn->error]);
            exit;
        }

        $stmt->bind_param('i', $task_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $image_filename = basename($row['after_photo']); // Get the filename
            $image_path = 'imgafter/' . $image_filename; // Path to the image

            echo json_encode(['status' => 200, 'data' => ['after_photo' => $image_path]]);
        } else {
            echo json_encode(['status' => 404, 'message' => 'No image found for the provided task ID']);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 404, 'message' => 'No task found for the provided problem ID']);
    }
    break;

    case'get_image':
        $problem_id = isset($_POST['problem_id']) ? $_POST['problem_id'] : ''; // Ensure problem_id is set
    // Validate problem_id
    if (empty($problem_id)) {
        echo json_encode(['status' => 400, 'message' => 'Problem ID not provided']);
        exit;
    }
    // Query to fetch the image based on problem_id
    $query = "SELECT images FROM complaints_detail WHERE id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo json_encode(['status' => 500, 'message' => 'Prepare statement failed: ' . $conn->error]);
        exit;
    }
    $stmt->bind_param('i', $problem_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $response = json_encode(['status' => 200, 'data' => ['images' => $row['images']]]);
        // Log response to debug if the JSON is correctly formed
        error_log("Response: " . $response);
        echo $response;
    } else {
        // Return 404 if no image is found for the given problem_id
        echo json_encode(['status' => 404, 'message' => 'Image not found']);
    }
    $stmt->close();
    $conn->close();
    exit;
    break;
    
}

?>
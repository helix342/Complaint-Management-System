<?php
include("db.php");
session_start();
if (isset($_POST['faculty_id'])) {
    $faculty_id = $_SESSION['faculty_id']; //'faculty_id' is stored in session
}
if (isset($_POST['hod_id'])) {
    $hod_id =  $_session['hod_id']; //hod id in session
}
if (isset($_POST['eo_id'])) {
    $eo_id = $_session['eo_id']; //eo id in session
}


$action = $_GET['action'] ?? '';

switch ($action) {

        //Common for all files
        //viewing complaint description in modal
    case 'view_complaint':
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

    case 'get_image':
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

    case 'get_aimage':
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

    case 'get_worker_phone':
        $complain_id = mysqli_real_escape_string($conn, $_POST['prblm_id']);
        $query = "
        SELECT w.* 
        FROM complaints_detail cd
        INNER JOIN manager m ON cd.id = m.problem_id
        INNER JOIN worker_details w ON m.worker_id = w.worker_id
        WHERE cd.id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $complain_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $User_data = $result->fetch_assoc();
        if ($User_data) {
            echo json_encode(['status' => 200, 'message' => 'Details fetched successfully.', 'data' => $User_data]);
        } else {
            echo json_encode(['status' => 500, 'message' => 'Details not found.']);
        }
        break;


        //Manager Backend
        //accapt complaint
    case 'manager_approve':
        $problem_id = $_POST['problem_id'];
        $worker = $_POST['worker_id'];
        $priority = $_POST['priority'];
        $deadline = $_POST['deadline'];

        $nowdate = date('Y-m-d');

        // Insert into manager table
        $insertQuery = "INSERT INTO manager (problem_id, worker_dept, priority) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param('sss', $problem_id, $worker, $priority);
        if ($stmt->execute()) {
            // Update status in complaints_detail table
            $updateQuery = "UPDATE complaints_detail SET days_to_complete = ?,manager_approve = ?,status = '9' WHERE id = ?";
            $stmtUpdate = $conn->prepare($updateQuery);
            $stmtUpdate->bind_param('ssi', $deadline, $nowdate, $problem_id);
            if ($stmtUpdate->execute()) {
                $response = ['status' => 200, 'message' => 'Complaint accepted and status updated successfully!'];
            } else {
                $response = ['status' => 500, 'message' => 'Failed to update complaint status.'];
            }
            $stmtUpdate->close();
        } else {
            $response = ['status' => 500, 'message' => 'Failed to insert data into manager table.'];
        }
        $stmt->close();
        echo json_encode($response);
        break;


    case 'reject_complaint':
        try {
            $id = $_POST['id'];
            $reason = $_POST['feedback'];

            $query = "UPDATE complaints_detail SET feedback = ?, status = '20' WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ss", $reason, $id);
            $query_obj = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            if ($query_obj) {
                echo json_encode(['status' => 200]);
            } else {
                throw new Exception('Failed to execute query');
            }
        } catch (Exception $e) {
            echo json_encode([
                'status' => 500,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
        break;

        //to get approval from principal
    case 'principal_complaint':
        $problem_id = $_POST['id'];
        $reason = $_POST['reason'];
        $insertQuery = "INSERT INTO comments (problem_id, reason) VALUES (?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param('is', $problem_id, $reason);
        if ($stmt->execute()) {
            $updateQuery = "UPDATE complaints_detail SET status = '6' WHERE id = ?";
            $stmtUpdate = $conn->prepare($updateQuery);
            $stmtUpdate->bind_param('i', $problem_id);
            if ($stmtUpdate->execute()) {
                echo json_encode(['status' => 200, 'message' => 'Complaint accepted and status updated.']);
            } else {
                echo json_encode(['status' => 500, 'message' => 'Failed to update complaint status.']);
            }
            $stmtUpdate->close();
        } else {
            echo json_encode(['status' => 500, 'message' => 'Failed to add comment.']);
        }
        $stmt->close();
        break;

        //get rejected reason from principal
    case 'get_reject_reason':
        $complain_id = mysqli_real_escape_string($conn, $_POST['problem_id']);
        $query = "SELECT feedback FROM complaints_detail WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $complain_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $User_data = $result->fetch_assoc();
        if ($User_data) {
            echo json_encode(['status' => 200, 'message' => 'Details fetched successfully.', 'data' => $User_data]);
        } else {
            echo json_encode(['status' => 500, 'message' => 'Details not found.']);
        }
        break;

        //to view feedback from faculty
    case 'facfeedview':
        $student_id = mysqli_real_escape_string($conn, $_POST['user_id']);
        $query = "SELECT * FROM complaints_detail WHERE id = ? AND status IN ('13', '14')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $User_data = $result->fetch_assoc();
        if ($User_data) {
            echo json_encode(['status' => 200, 'message' => 'Details fetched successfully.', 'data' => $User_data]);
        } else {
            echo json_encode(['status' => 500, 'message' => 'Details not found.']);
        }
        break;

        //add new workers
    case 'addworker':
        $name = $_POST['w_name'];
        $contact = $_POST['w_phone'];
        $gender = $_POST['w_gender'];
        $dept = $_POST['w_dept'];
        $role = $_POST['w_role'];

        $dept_prefix = strtoupper(substr($dept, 0, 3));

        $checkQuery = "SELECT SUBSTRING(worker_id, 4) AS id_number 
                   FROM worker_details 
                   WHERE worker_id LIKE CONCAT(?, '%') 
                   ORDER BY CAST(SUBSTRING(worker_id, 4) AS UNSIGNED) DESC LIMIT 1";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param('s', $dept_prefix);
        $stmt->execute();
        $result = $stmt->get_result();

        $number = ($row = $result->fetch_assoc()) ? intval($row['id_number']) + 1 : 1;
        $worker_id = $dept_prefix . str_pad($number, 2, '0', STR_PAD_LEFT);

        $insertQuery = "INSERT INTO worker_details (worker_id, worker_first_name, worker_dept, worker_mobile, worker_gender, usertype) 
                    VALUES (?, ?, ?, ?, ?, ?)";
        $stmtInsert = $conn->prepare($insertQuery);
        $stmtInsert->bind_param('ssssss', $worker_id, $name, $dept, $contact, $gender, $role);
        if ($stmtInsert->execute()) {
            echo json_encode(['status' => 200, 'message' => "Worker added with ID $worker_id!"]);
        } else {
            echo json_encode(['status' => 500, 'message' => 'Error: Could not insert worker details.']);
        }
        break;

        //add new user for raise complaints
    case 'add_user':
        try {
            $name = $_POST["name"];
            $id = $_POST["userid"];
            $phone = $_POST["phone"];
            $email = $_POST["email"];
            $dept = $_POST["u_dept"];
            $role = $_POST["u_role"];

            $query = "INSERT INTO faculty_details (faculty_id, faculty_name, department, faculty_contact, faculty_mail, role, password)
                      VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('sssssss', $id, $name, $dept, $phone, $email, $role, $id);

            if ($stmt->execute()) {
                echo json_encode(['status' => 200, 'msg' => 'Successfully stored']);
            } else {
                throw new Exception('Query Failed: ' . $stmt->error);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 500, 'message' => 'Error: ' . $e->getMessage()]);
        }
        break;

        //delete workers
    case 'delete_worker':
        $id = $_POST['id'];

        $query = "DELETE FROM worker_details WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $id);
        $query_obj = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        echo json_encode([
            'status' => $query_obj ? 200 : 500
        ]);
        break;

        //delete users
    case 'delete_user':
        $id = $_POST['id'];

        $query = "DELETE FROM faculty_details WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $id);
        $query_obj = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        echo json_encode([
            'status' => $query_obj ? 200 : 500
        ]);
        break;

    case 'submit_comment_reply':
        $task_id = $_POST['task_id'];
        $comment_reply = $_POST['comment_reply'];
        $reply_date = date('Y-m-d');

        // Update the comment_reply and reply_date fields for the corresponding task_id
        $query = "UPDATE manager SET comment_reply = ?, reply_date = ? WHERE task_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssi', $comment_reply, $reply_date, $task_id);
        if ($stmt->execute()) {
            $response = ['status' => 200, 'message' => 'Reply submitted successfully!'];
        } else {
            $response = ['status' => 500, 'message' => 'Failed to submit reply.'];
        }
        $stmt->close();
        echo json_encode($response);
        break;

    case 'reassign_work':
        $id = $_POST['complaintfeed_id'];
        $status = $_POST['status'];
        $current_date = date('Y-m-d');
        $reassign_deadline = $_POST['reassign_deadline'] ?? null;

        if ($status == 15 && $reassign_deadline) {
            // Status '15' for reassign with deadline
            $sql = "UPDATE complaints_detail SET status = ?, reassign_date = ?, days_to_complete = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('issi', $status, $current_date, $reassign_deadline, $id);
        } else {
            // Other statuses without deadline
            $sql = "UPDATE complaints_detail SET status = ?, reassign_date = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('isi', $status, $current_date, $id);
        }

        if ($stmt->execute()) {
            echo json_encode([
                'status' => 200,
                'message' => "Status and updates saved successfully."
            ]);
        } else {
            echo json_encode([
                'status' => 500,
                'message' => "Error updating status: " . $stmt->error
            ]);
        }
        $stmt->close();
        break;

    case 'extend_deadlinedate':
        try {
            $id = $_POST['id'];
            $dead_date = $_POST['extend_deadline'];
            $reason = $_POST['reason'];
    
            $query = "UPDATE complaints_detail SET days_to_complete = ?, extend_date = '1', extend_reason = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ssi', $dead_date, $reason, $id);
    
            if ($stmt->execute()) {
                echo json_encode(['status' => 200]);
            } else {
                throw new Exception('Query Failed: ' . $stmt->error);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 500, 'message' => 'Error: ' . $e->getMessage()]);
        }
        break;

    case 'reassign_complaint':
        try {
            $id = $_POST['user_id'];
            $worker_dept = $_POST['worker'];
    
            $query = "UPDATE manager SET worker_dept = ? WHERE problem_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('si', $worker_dept, $id);
    
            if ($stmt->execute()) {
                echo json_encode(['status' => 200]);
            } else {
                throw new Exception('Query Failed: ' . $stmt->error);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 500, 'message' => 'Error: ' . $e->getMessage()]);
        }
        break;

    case 'manager_feedbacks':
        try {
            $id = $_POST['id'];
            $feedback = $_POST['feedback12'];
            $rating = $_POST['ratings'];
    
            $query = "UPDATE complaints_detail SET mfeedback = ?, mrating = ?, status = '16' WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ssi', $feedback, $rating, $id);
    
            if ($stmt->execute()) {
                echo json_encode(['status' => 200]);
            } else {
                throw new Exception('Query Failed: ' . $stmt->error);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 500, 'message' => 'Error: ' . $e->getMessage()]);
        }
       break;    

}

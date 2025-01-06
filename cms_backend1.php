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
    //Worker backend
    //accept complaint by head
    case 'wacceptcomp':
        $problem_id = $_POST['user_id'] ?? null;

        if ($problem_id) {
            // Prepare the SQL query
            $updateQuery = "UPDATE complaints_detail SET status = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $updateQuery);
    
            if ($stmt) {
                // Bind parameters to the prepared statement
                $status = 10;
                mysqli_stmt_bind_param($stmt, "ii", $status, $problem_id);
    
                // Execute the prepared statement
                if (mysqli_stmt_execute($stmt)) {
                    echo "Success: Complaint accepted and status updated successfully!";
                } else {
                    echo "Error: Failed to update complaint status.";
                }
    
                // Close the statement
                mysqli_stmt_close($stmt);
            } else {
                echo "Error: Failed to prepare the update query.";
            }
        } else {
            echo "Error: Problem ID is missing.";
        }
        break;

        //view complaint in head
        case 'whviewcomp':
            $complain_id = $_POST['user_id'];

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

   

    // Response
    if ($User_data) {
        echo json_encode([
            'status' => 200,
            'message' => 'Details fetched successfully by ID',
            'data' => $User_data,
        ]);
    } else {
        echo json_encode([
            'status' => 404,
            'message' => 'Details not found'
        ]);
    }
    break;

    //bacnkend for workers
    case 'wviewcomp':
        $task_id = isset($_POST['task_id']) ? intval($_POST['task_id']) : null;

    if ($task_id === null) {
        die(json_encode(['error' => 'Task ID not provided']));
    }

    $sql = "SELECT 
        f.faculty_name, 
        f.faculty_contact, 
        cd.block_venue, 
        cd.venue_name, 
        cd.problem_description, 
        cd.days_to_complete
    FROM 
        complaints_detail AS cd
    JOIN 
        faculty_details AS f ON cd.faculty_id = f.faculty_id
    WHERE 
        cd.id = (SELECT problem_id FROM manager WHERE task_id = ?)
";


    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $task_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $response = array();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $response = array(
            'faculty_name' => $row['faculty_name'],
            'faculty_contact' => $row['faculty_contact'],
            'block_venue' => $row['block_venue'],
            'venue_name' => $row['venue_name'],
            'problem_description' => $row['problem_description'],
            'days_to_complete' => $row['days_to_complete']
        );
        echo json_encode($response);
    } else {
        $response['error'] = 'No details found for this complaint.';
    }

  

    $stmt->close();
    break;


    //work completion status update
    case 'workcompletion':
        $taskId = $_POST['task_id'];
    $completionStatus = $_POST['completion_status'];
    $reason = $_POST['reason'];
    $p_id = $_POST['p_id'];
    $oname = $_POST['o_name'];
    $wname = $_POST['w_name'];
    $amt = $_POST['amt'];
    $name = current(array_filter([$oname, $wname]));

    $insertQuery = "UPDATE manager SET worker_id='$name' WHERE task_id='$taskId'";
    if (mysqli_query($conn, $insertQuery)) {
          
        
            $updateComplaintSql = "UPDATE complaints_detail 
                                   SET status = 11,worker_id='$name',amount_spent='$amt', task_completion = ?,reason = ?,date_of_completion = NOW()
                                   WHERE id = (SELECT problem_id FROM manager WHERE task_id = ?)";
            if ($stmt = $conn->prepare($updateComplaintSql)) {
                $stmt->bind_param("ssi", $completionStatus,$reason,$taskId);
                if (!$stmt->execute()) {
                    echo "Update failed: (" . $stmt->errno . ") " . $stmt->error;
                } else {
                    echo "Complaint status and task completion updated successfully.";
                }
                $stmt->close();
            } else {
                echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
            }
        
            $imgAfterName = null;
            if (isset($_FILES['img_after']) && $_FILES['img_after']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'imgafter/';
                $imgAfterName = basename($_FILES['img_after']['name']); 
                $uploadFile = $uploadDir . $imgAfterName; 
            
                if (move_uploaded_file($_FILES['img_after']['tmp_name'], $uploadFile)) {
                    echo "File successfully uploaded: " . $imgAfterName;
            
                    $insertTaskDetSql = "INSERT INTO worker_taskdet (task_id, task_completion, after_photo, work_completion_date) 
                                         VALUES (?, ?, ?, NOW())";
                    if ($stmt = $conn->prepare($insertTaskDetSql)) {
                        $stmt->bind_param("sss", $taskId, $completionStatus, $imgAfterName);
                        if (!$stmt->execute()) {
                            echo "Insertion into worker_taskdet failed: (" . $stmt->errno . ") " . $stmt->error;
                        } else {
                            echo "Record inserted successfully into worker_taskdet.";
                        }
                        $stmt->close();
                    } else {
                        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
                    }
                } else {
                    echo "File upload failed.";
                }
            } else {
                echo "No file uploaded or file upload error.";
            }   

        
    }
    break;



    //show before image for workers
    case 'wbeforeimg':
        $task_id = isset($_POST['task_id']) ? $_POST['task_id'] : ''; 

    if (empty($task_id)) {
        echo json_encode(['status' => 400, 'message' => 'Task ID not provided']);
        exit;
    }

    $query = "SELECT images FROM complaints_detail WHERE id = (SELECT problem_id FROM manager WHERE task_id = ?)";
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
        
        $image_filename = basename($row['images']); 
        $image_path = 'uploads/' . $image_filename; 
        
        echo json_encode(['status' => 200, 'data' => ['after_photo' => $image_path]]);
    } else {
        echo json_encode(['status' => 500, 'message' => 'No image found']);
    }

    $stmt->close();
    break;


    //after image for workers
    case 'wafterimage':
        $task_id = isset($_POST['task_id']) ? $_POST['task_id'] : ''; 

        if (empty($task_id)) {
            echo json_encode(['status' => 400, 'message' => 'Task ID not provided']);
            exit;
        }
    
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
            
            $image_filename = basename($row['after_photo']); 
            $image_path = 'imgafter/' . $image_filename; 
            
            echo json_encode(['status' => 200, 'data' => ['after_photo' => $image_path]]);
        } else {
            echo json_encode(['status' => 500, 'message' => 'No image found']);
        }
    
        $stmt->close();
        exit;



        //worker assign in completion
        case 'wworkerassign':
            $work = $_POST['worker_dept'];  
    $sql8 = "SELECT worker_id, worker_first_name FROM worker_details WHERE worker_dept = ? AND usertype = 'worker'";
    $stmt = $conn->prepare($sql8);
    $stmt->bind_param("s",$work);
    $stmt->execute();
    $result8 = $stmt->get_result();


   
    $options = '';


    while ($row = mysqli_fetch_assoc($result8)) {
        $options .= '<option value="' . $row['worker_id'] . '">' . $row['worker_id'] . ' - ' . $row['worker_first_name'] . '</option>';

    }


    echo $options;
    break; 
}
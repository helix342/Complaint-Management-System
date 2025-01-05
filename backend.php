<?php
 
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('db.php');


//fetchignavailable worker for task completion
if (isset($_POST['work'])) {
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
    exit(); 
}

//viewing complaint description in modal
if (isset($_POST['fetch_details'])) {
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


}  


//click start work in new table
if (isset($_POST['start_work'])) {
    $id = $_POST['task_id'];

   

    $sql = "UPDATE complaints_detail 
            SET status = 10 
            WHERE id = (SELECT problem_id FROM manager WHERE task_id = ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$query_run = $stmt->get_result();


if($query_run){
    $res =[
        "status" => 200,
        "message" => "Work started successfully"
    ];
    echo json_encode($res);
}
else{
    $res =[
        "status" => 500,
        "message" => "Work could not be started"
    ];
    echo json_encode($res);
}
}

   




//work completion backend updating after image assigning worker etc.......
if (isset($_POST['update'])) {
    $taskId = $_POST['task_id'];
    $completionStatus = $_POST['completion_status'];
    $reason = $_POST['reason'];
    $p_id = $_POST['p_id'];
    $oname = $_POST['o_name'];
    $wname = $_POST['w_name'];
    $name = current(array_filter([$oname, $wname]));

    $insertQuery = "UPDATE manager SET worker_id='$name' WHERE task_id='$taskId'";
    if (mysqli_query($conn, $insertQuery)) {
          
        
            $updateComplaintSql = "UPDATE complaints_detail 
                                   SET status = 11,worker_id='$name', task_completion = ?,reason = ?,date_of_completion = NOW()
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
   


  
    
}




//viewing before image
if (isset($_POST['get_bef'])) {
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
    exit;
}


//viewing after image
if (isset($_POST['get_image'])) {
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
}



?>
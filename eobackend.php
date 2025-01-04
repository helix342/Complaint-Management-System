<?php
include("db.php");
session_start();
$_session['eo_id'] = 12345;
$eo_id = $_session['eo_id'];

if (isset($_POST['approvefacbtn'])) {
    try {
        $id = mysqli_real_escape_string($conn, $_POST['approvefac']);
        
        $query = "UPDATE complaints_detail SET status = '22' WHERE id='$id'";
        
        if (mysqli_query($conn, $query))    {
            $res = [
                'status' => 200,
                'message' => 'Details Updated Successfully'
            ];
            echo json_encode($res);
        } else {
            throw new Exception('Query Failed: ' . mysqli_error($conn));
        }
    } catch (Exception $e) {
        $res = [
            'status' => 500,
            'message' => 'Error: ' . $e->getMessage()
        ];
        echo json_encode($res);
    }
}
//Rejected Feedback
if (isset($_POST['rejfeed'])) {
    try {
        $id = $_POST['reject_id'];
        $feedback = $_POST['rejfeed'];

        // Prepare the SQL statement
        $query = "UPDATE complaints_detail SET feedback = ?, status = ? WHERE id = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            throw new Exception('Prepare statement failed: ' . $conn->error);
        }

        // Bind parameters
        $status = 5;
        $stmt->bind_param('sii', $feedback, $status, $id);

        // Execute the statement
        if ($stmt->execute()) {
            $res = [
                'status' => 200,
                'message' => 'Details Updated Successfully'
            ];
            echo json_encode($res);
        } else {
            throw new Exception('Execution failed: ' . $stmt->error);
        }

        // Close the statement
        $stmt->close();
    } catch (Exception $e) {
        $res = [
            'status' => 500,
            'message' => 'Error: ' . $e->getMessage()
        ];
        echo json_encode($res);
    }
}


//Problem Description
if (isset($_POST['seedetails'])) {
    try {
        $student_id1 = $_POST['user_id'];

        // Prepare the SQL statement
        $query = "SELECT * FROM complaints_detail WHERE id = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            throw new Exception('Prepare statement failed: ' . $conn->error);
        }

        // Bind the parameter
        $stmt->bind_param('i', $student_id1);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();
        $User_data = $result->fetch_assoc();

        if ($User_data) {
            $res = [
                'status' => 200,
                'message' => 'Details fetched successfully by ID',
                'data' => $User_data
            ];
        } else {
            $res = [
                'status' => 404,
                'message' => 'No details found for the given ID'
            ];
        }

        echo json_encode($res);
    } catch (Exception $e) {
        $res = [
            'status' => 500,
            'message' => 'Error: ' . $e->getMessage()
        ];
        echo json_encode($res);
    } finally {
        if (isset($stmt) && $stmt instanceof mysqli_stmt) {
            $stmt->close();
        }
    }
}

//Faculty Details
if (isset($_POST['facultydetails'])) {
    try {
        $student_id1 = $_POST['user_id'];
        $fac_id = $_POST['fac_id'];

        // Query 1: Fetch data from faculty table
        $query1 = "SELECT * FROM faculty WHERE id = ?";
        $stmt1 = $conn->prepare($query1);

        if (!$stmt1) {
            throw new Exception('Prepare statement for faculty failed: ' . $conn->error);
        }

        $stmt1->bind_param('i', $fac_id);
        $stmt1->execute();
        $result1 = $stmt1->get_result();
        $fac_data = $result1->fetch_assoc();

        // Query 2: Fetch data by joining complaints_detail and faculty_details tables
        $query = "SELECT cd.*, faculty_details.faculty_name, faculty_details.department, faculty_details.faculty_contact, faculty_details.faculty_mail
                  FROM complaints_detail cd
                  JOIN faculty_details ON cd.faculty_id = faculty_details.faculty_id
                  WHERE cd.id = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            throw new Exception('Prepare statement for complaints_detail failed: ' . $conn->error);
        }

        $stmt->bind_param('i', $student_id1);
        $stmt->execute();
        $result = $stmt->get_result();
        $User_data = $result->fetch_assoc();

        if ($User_data || $fac_data) {
            $res = [
                'status' => 200,
                'message' => 'Details fetched successfully by ID',
                'data' => $User_data,
                'data1' => $fac_data
            ];
        } else {
            $res = [
                'status' => 404,
                'message' => 'No details found for the given IDs'
            ];
        }

        echo json_encode($res);

    } catch (Exception $e) {
        $res = [
            'status' => 500,
            'message' => 'Error: ' . $e->getMessage()
        ];
        echo json_encode($res);
    } finally {
        // Close prepared statements
        if (isset($stmt1) && $stmt1 instanceof mysqli_stmt) {
            $stmt1->close();
        }
        if (isset($stmt) && $stmt instanceof mysqli_stmt) {
            $stmt->close();
        }
    }
}


//Rejected Reason
if (isset($_POST['seefeedback'])) {
    try {
        $student_id5 = $_POST['user_idrej'];

        // Prepare the query
        $query = "SELECT * FROM complaints_detail WHERE id = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            throw new Exception('Prepare statement failed: ' . $conn->error);
        }

        // Bind the parameter
        $stmt->bind_param('i', $student_id5);
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();
        $User_data = $result->fetch_assoc();

        if ($User_data) {
            $res = [
                'status' => 200,
                'message' => 'Details fetched successfully by ID',
                'data' => $User_data
            ];
        } else {
            $res = [
                'status' => 404,
                'message' => 'No details found for the given ID'
            ];
        }

        echo json_encode($res);
    } catch (Exception $e) {
        $res = [
            'status' => 500,
            'message' => 'Error: ' . $e->getMessage()
        ];
        echo json_encode($res);
    } finally {
        // Close the prepared statement
        if (isset($stmt) && $stmt instanceof mysqli_stmt) {
            $stmt->close();
        }
    }
}

// Get Image
if (isset($_POST['get_image'])) {
    try {
        $task_id = $_POST['task_id'];

        // Validate the task ID
        if (empty($task_id) || !is_numeric($task_id)) {
            echo json_encode(['status' => 400, 'message' => 'Task ID not provided or invalid']);
            exit;
        }

        // Prepare the SQL query
        $query = "SELECT images FROM complaints_detail WHERE id = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            throw new Exception('Failed to prepare the statement: ' . $conn->error);
        }

        // Bind and execute
        $stmt->bind_param('i', $task_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the image was found
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $image_path = $row['images'];
            $res = [
                "status" => 200,
                "message" => "success",
                "data" => $image_path
            ];
            echo json_encode($res);
        } else {
            echo json_encode(['status' => 404, 'message' => 'No image found']);
        }

    } catch (Exception $e) {
        echo json_encode(['status' => 500, 'message' => 'Error: ' . $e->getMessage()]);
    } finally {
        // Close the statement if it was successfully created
        if (isset($stmt) && $stmt instanceof mysqli_stmt) {
            $stmt->close();
        }
    }
    exit;
}


// Get After Image
if (isset($_POST['after_image'])) {
    $task_id = isset($_POST['task_id']) ? intval($_POST['task_id']) : '';

    if ($task_id == 0) {
        echo json_encode(['status' => 400, 'message' => 'Task ID not provided or invalid']);
        exit;
    }

    $query = "SELECT after_photo FROM worker_taskdet WHERE id = ?";
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
        $image_path = 'imgafter/' . $row['after_photo'];

        if (file_exists($image_path)) {
            echo json_encode(['status' => 200, 'data' => ['after_photo' => $image_path]]);
        } else {
            echo json_encode(['status' => 404, 'message' => 'Image file not found on the server']);
        }
    } else {
        echo json_encode(['status' => 404, 'message' => 'No image found']);
    }

    $stmt->close();
    $conn->close();
    exit;
}

// Define the counter file path
$counterFilePath = './uploads/counter.txt';

// Function to get the next file number
function getNextFileNumber($counterFilePath)
{
    if (file_exists($counterFilePath)) {
        $file = fopen($counterFilePath, 'r');
        $lastNumber = (int)fgets($file);
        fclose($file);
        $nextNumber = $lastNumber + 1;
    } else {
        $nextNumber = 1;
    }
    $file = fopen($counterFilePath, 'w');
    fwrite($file, $nextNumber);
    fclose($file);
    return $nextNumber;
}


if (isset($_POST['hod'])) {
    try {
        $hod = $hod_id;
        $block_venue = mysqli_real_escape_string($conn, $_POST['block_venue']);
        $venue_name = mysqli_real_escape_string($conn, $_POST['venue_name']);
        $type_of_problem = mysqli_real_escape_string($conn, $_POST['type_of_problem']);
        $problem_description = mysqli_real_escape_string($conn, $_POST['problem_description']);
        $date_of_reg = mysqli_real_escape_string($conn, $_POST['date_of_reg']);
        $status = 4; // Fixed status value

        // Handle file upload
        $images = "";
        $uploadFileDir = './uploads/';

        // Ensure the upload directory exists
        if (!is_dir($uploadFileDir) && !mkdir($uploadFileDir, 0755, true)) {
            throw new Exception('Failed to create upload directory.');
        }

        if (isset($_FILES['images']) && $_FILES['images']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['images']['tmp_name'];
            $fileNameCmps = explode(".", $_FILES['images']['name']);
            $fileExtension = strtolower(end($fileNameCmps));

            // Validate file extension
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            if (!in_array($fileExtension, $allowedExtensions)) {
                throw new Exception('Invalid file extension. Allowed: jpg, jpeg, png.');
            }

            // Generate a unique filename
            $newFileName = uniqid('img_', true) . '.' . $fileExtension;
            $dest_path = $uploadFileDir . $newFileName;

            // Move the uploaded file
            if (!move_uploaded_file($fileTmpPath, $dest_path)) {
                throw new Exception('Error moving the uploaded file.');
            }

            $images = $newFileName;
        }

        // Insert data into the database
        $query = "INSERT INTO complaints_detail (faculty_id, fac_id, block_venue, venue_name, type_of_problem, problem_description, images, date_of_reg, status) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception('Failed to prepare statement: ' . $conn->error);
        }

        // Bind parameters and execute
        $stmt->bind_param('iissssssi', $hod, $hod, $block_venue, $venue_name, $type_of_problem, $problem_description, $images, $date_of_reg, $status);
        if ($stmt->execute()) {
            echo json_encode(['status' => 200, 'message' => 'Success']);
        } else {
            throw new Exception('Error inserting data: ' . $stmt->error);
        }

    } catch (Exception $e) {
        echo json_encode(['status' => 500, 'message' => 'Error: ' . $e->getMessage()]);
    }
}

//Approve Button for Faculty Infra


?>

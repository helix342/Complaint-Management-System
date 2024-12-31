<?php
include('db.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = "cragulraja2004@gmail.com";
    $email1 = "saransoundhar06@gmail.com";
    $email2 = "samiisboss1574@gmail.com";
    $email3 = "rabinsmith27124s@gmail.com";    


    // Check if the email exists in the database
    $query = "SELECT * FROM complaints_detail WHERE status = 9";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 1) {
        // Email exists in the database, send the email
        $mail = new PHPMailer(true);
        /*
            $query = "SELECT * FROM faculty WHERE id = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param('si',$id); // 'si' means string and integer
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $pass = $row['pass'];

*/
$currentDate = date("Y-m-d");

        $query2 = "SELECT * FROM complaints_detail WHERE DATEDIFF('$currentDate',m_date_of_reg) > 1 AND status = '9' ";
        $query_run2 = mysqli_query($conn, $query2);
        if (mysqli_num_rows($query_run2) > 0) {
            $row2 = mysqli_fetch_assoc($query_run2);
            $id= $row2['id'];
            $rdate = $row2['date_of_reg']; 
            $f_id = $row2['fac_id'];
        }
        try {
            // SMTP settings

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'mkceinfocorner@gmail.com'; // Your Gmail email address
            $mail->Password = 'npdllnbipximwvnq'; // Your Gmail password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Sender and recipient
            $mail->setFrom('mkceinfocorner@gmail.com', 'MKCE_INFO_CORNER');
            $mail->addAddress($email);
            $mail->addAddress($email1);
            $mail->addAddress($email2);
            $mail->addAddress($email3);



            // Email content
            $mail->Subject = 'Complaint not Accepted';
            $mail->isHTML(true);
            $mail->Body = "
            <p>Dear Manager,</p>
            <p>The complaint with ID <strong>$id</strong>, raised on <strong>$rdate</strong>,From the faculty_ID <strong>$f_id</strong> 
            has not been accepted by the worker.</p>
            <p>Please take necessary action.</p>
            <p>Regards,<br>Complaint Management Team</p>";


            // Send the email
            $mail->send();

            $res = [
                'status' => 200,
                'message' => 'Password sent successfully to your Email!'
            ];
            echo json_encode($res);
            return;
        } catch (Exception $e) {
            echo 'Email could not be sent. Error: ', $mail->ErrorInfo;
        }
    } else {
        // Email not found in the database
        $res = [
            'status' => 500,
            'message' => 'Email not found. Kindly Check your Email and Faculty ID!'
        ];
        echo json_encode($res);
        return;
    }
} else {
    echo 'Invalid request';
}

// Close the database connection
$conn->close();

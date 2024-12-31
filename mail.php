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

$query2 = "SELECT * FROM complaints_detail WHERE DATEDIFF('$currentDate',manager_approve) > 1 AND status = '9'";
$query_run2 = mysqli_query($conn, $query2);

if (mysqli_num_rows($query_run2) > 0) {
    // Initialize the email body
    $emailBody = "
    <p>Dear Manager,</p>
    <p>The following complaints have not been accepted by the worker:</p>
    <table border='1' cellpadding='5' cellspacing='0'>
        <tr>
            <th>Complaint ID</th>
            <th>Registration Date</th>
            <th>Faculty ID</th>
        </tr>";

    while ($row2 = mysqli_fetch_assoc($query_run2)) {
        $id = $row2['id'];
        $rdate = $row2['date_of_reg'];
        $f_id = $row2['fac_id'];

        $emailBody .= "
        <tr>
            <td>$id</td>
            <td>$rdate</td>
            <td>$f_id</td>
        </tr>";
    }

    $emailBody .= "
    </table>
    <p>Please take necessary action.</p>
    <p>Regards,<br>Complaint Management Team</p>";

    try {
        // SMTP settings
        $mail = new PHPMailer(true);
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
        $mail->Subject = 'Pending Complaints Not Accepted';
        $mail->isHTML(true);
        $mail->Body = $emailBody;

        // Send the email
        $mail->send();

        $res = [
            'status' => 200,
            'message' => 'Email sent successfully with all pending complaints!'
        ];
        echo json_encode($res);
    } catch (Exception $e) {
        echo 'Email could not be sent. Error: ', $mail->ErrorInfo;
    }
} else {
    $res = [
        'status' => 500,
        'message' => 'No complaints found with status 9 and manager approval delay!'
    ];
    echo json_encode($res);
}
    }
} 
    
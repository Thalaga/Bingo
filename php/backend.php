<?php
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $servername = "sidcc";
    $username = "Thalaga";
    $password = "Karabo@21";
    $dbname = "Siddbs";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare data for insertion
    $name = $_POST['name'];
    $subject = $_POST['subject'];
    $feedback = $_POST['feedback'];

    // File upload handling
    $picture_path = uploadFile('picture');
    $video_path = uploadFile('video');
    $file_path = uploadFile('file');

    // Insert data into database
    $sql = "INSERT INTO feedback (name, subject, feedback, picture_path, video_path, file_path)
            VALUES ('$name', '$subject', '$feedback', '$picture_path', '$video_path', '$file_path')";

    if ($conn->query($sql) === TRUE) {
        echo "Feedback submitted successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}

// Function to handle file upload
function uploadFile($fileInputName) {
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES[$fileInputName]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if file already exists
    if (file_exists($targetFile)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES[$fileInputName]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow only specific file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" && $imageFileType != "mp4" && $imageFileType != "pdf"
        && $imageFileType != "doc" && $imageFileType != "docx" && $imageFileType != "txt") {
        echo "Sorry, only JPG, JPEG, PNG, GIF, MP4, PDF, DOC, DOCX, and TXT files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES[$fileInputName]["tmp_name"], $targetFile)) {
            return $targetFile; // Return the path of the uploaded file
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>

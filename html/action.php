<?php
$servername = "localhost";
$username = "root";
$password = "Secured4231$";
$dbname = "careplus_test";
$tablename = "patients";

// Create connection to database
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check if connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "Connection was successful<br>";

if (isset($_POST["submit"])) {
    // Process file information
    $target_dir = 'uploads/';
    $target_file = $target_dir . basename($_FILES["csv_upload"]["name"]);
    $upload_ok = 1;
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file is of .csv type
    if ($file_type !== "csv") {
        echo "File MUST be a csv type file";
        $upload_ok = 0;
    }
    
    if (!$upload_ok) {
        echo "File wasn't uploaded";
    } else {
        //check if directory exists
        if(!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
            echo "creating directory";
        }
        // Upload file on server
        echo $_FILES["csv_upload"]["tmp_name"] . "<br>";
        if (move_uploaded_file($_FILES["csv_upload"]["tmp_name"], $target_file)) {
            echo "The file " . basename($_FILES["csv_upload"]["name"]) . " has been uploaded successfully<br>";

            // Open file for reading
            $file = fopen($target_file, "r");

            // Loop through each row in the CSV file
            while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
                // Assign values from CSV to variables
                list($pid, $date_reg, $name_title, $name_first, $name_middle, $name_last, 
                     $education, $date_birth, $civil_status, $sex, $ethnic_orig, $home_town, 
                     $place_birth, $religion, $contact_person, $death_date) = $data;

                // Perform SQL query to insert data into database
                $sql = "INSERT INTO $tablename (pid, date_reg, name_title, name_first, name_middle, name_last, education,
                                                date_birth, civil_status, sex, ethnic_orig, home_town, place_birth, religion, contact_person, death_date)
                                                VALUES ('$pid', '$date_reg', '$name_title', '$name_first', '$name_middle', '$name_last','$education', 
                                                '$date_birth', '$civil_status', '$sex', '$ethnic_orig', '$home_town', '$place_birth', '$religion', '$contact_person', '$death_date')";
                
                // Execute SQL query
                if (mysqli_query($conn, $sql)) {
                    $last_id_inserted = mysqli_insert_id($conn);
                    $sql_sel = "SELECT * FROM $tablename WHERE id = $last_id_inserted";
                    $result = mysqli_query($conn, $sql_sel);
                    if($result && mysqli_num_rows($result) > 0) {
                        echo "<table border=1";
                        // Table header
                        echo "<tr>";
                        while ($row = mysqli_fetch_assoc($result)) {
                            foreach ($row as $key => $value) {
                                echo "<th>$key</th>";
                            }
                            break; // Output header row only once
                        }
                        echo "</tr>";
                        // Table data
                        mysqli_data_seek($result, 0); // Reset result pointer to fetch data again
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            foreach ($row as $value) {
                                echo "<td>$value</td>";
                            }
                            echo "</tr>";
                        }
                        echo "</table>";
                    }else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                    }
                }else {
                    echo "error inserting values to database";
                }
            }
            // Close file
            fclose($file);
        }else {
            echo "Encountered error while uploading file";
        }
    }
}

// Close database connection
mysqli_close($conn);
?>

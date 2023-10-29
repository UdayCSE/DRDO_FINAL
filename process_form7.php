<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "cabs";

    // Create a database connection
    $mysqli = new mysqli($servername, $username, $password, $database);

    // Check if the connection was successful
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

   
    $refOfEvidence = $_POST['ref_of_evidence_'];
   
    // Handle table contents (KPI data)
    for ($i = 1; $i <= 2; $i++) {
        $completedTasks = $_POST['completed_tasks_' . $i];
        $actionTaken = $_POST['action_taken_' . $i];

        // Get the KPI name and measurement method for this row
        $kpiData = [
            ["Generation of Log card", "Number of log card generated within time Vs Total number of log card generated *"],
            ["Updation of Log card", "Number of log card updated within Time Vs total Number of log card updated*"],
            
            
        ];
        $kpiName = $kpiData[$i - 1][0];
        $measurementMethod = $kpiData[$i - 1][1];

        // Default target percentage
        $targetPercentage = "100%";
        $achievementPercentage = "NA";

        if (preg_match('/^(\d+)\/(\d+)$/', $completedTasks, $matches)) {
            $completed = $matches[1];
            $total = $matches[2];
            if ($total > 0) {
                $achievementPercentage = ($completed / $total) * 100;
            }
        }

        // Create and execute the SQL query to insert data into the kpi_table_contents table
        $tableSql = "INSERT INTO kpi_table_contents_7 (kpi_name, measurement_method, completed_tasks, target_percentage, achievement_percentage, action_taken, ref_of_evidence) VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $tableStmt = $mysqli->prepare($tableSql);

        $tableStmt->bind_param("ssdssss", $kpiName, $measurementMethod, $completedTasks, $targetPercentage, $achievementPercentage, $actionTaken, $refOfEvidence);

        $tableStmt->execute();
    }

    if ($tableStmt) {
        echo "Data inserted successfully!";
    } else {
        echo "Error: " . $mysqli->error;
    }

    $tableStmt->close();
    $mysqli->close();
} else {
    echo "Form not submitted.";
}
?>

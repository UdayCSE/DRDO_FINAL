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
    for ($i = 1; $i <= 3; $i++) {
        $completedTasks = $_POST['completed_tasks_' . $i];
        $actionTaken = $_POST['action_taken_' . $i];

        // Get the KPI name and measurement method for this row
        $kpiData = [
            ["Part level Inspection (mechanical)", "Number of PFWDR closed in time vs Total number of PFWDR coordinated
            All PFWDR to be scrutinized in time as per conditions below.
            a. Nil work Done - 30 Min
            b.Mission System minor work - 1 Hour*
            c. A/c Major Servicing -4 Hours"],
            ["On time issue of F- 1090**", "Number of F-1090 granted in time vs Total number of F-1090 coordinated (30Min per F-1090)"],
            ["Nil Major observations in PFWDR from DGAQA", "Number of PFWDR with Major observation vs Total number of PFWDR coordinated
            Zero Major observations #"],
            
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
        $tableSql = "INSERT INTO kpi_table_contents_2 (kpi_name, measurement_method, completed_tasks, target_percentage, achievement_percentage, action_taken, ref_of_evidence) VALUES (?, ?, ?, ?, ?, ?, ?)";
        
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

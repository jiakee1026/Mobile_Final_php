<?php
if (!isset($_POST)) {
    $response = array('status' => 'failed', 'data' => null);
    sendJsonResponse($response);
    die();
}
include_once("dbconnect.php");
$results_per_page = 10;
$pageno = (int)$_POST['pageno'];
$search = $_POST['search'];

$page_first_result = ($pageno - 1) * $results_per_page;
$sqlsubject = "SELECT * FROM tbl_subjects WHERE subject_name LIKE '%$search%' ORDER BY subject_id DESC";

$result = $conn->query($sqlsubject);
$number_of_result = $result->num_rows;
$number_of_page = ceil($number_of_result / $results_per_page);
$sqlsubject = $sqlsubject . " LIMIT $page_first_result , $results_per_page";
$result = $conn->query($sqlsubject);
if ($result->num_rows > 0) {
    //do something
    $Course["Course"] = array();
    while ($row = $result->fetch_assoc()) {
        $courselist = array();
        $courselist['subject_id'] = $row['subject_id'];
        $courselist['subject_name'] = $row['subject_name'];
        $courselist['subject_description'] = $row['subject_description'];
        $courselist['subject_price'] = $row['subject_price'];
        $courselist['subject_sessions'] = $row['subject_sessions'];
        $courselist['subject_rating'] = $row['subject_rating'];
        $courselist['tutor_id'] = $row['tutor_id'];
        array_push($Course["Course"],$courselist);
    }
    $response = array('status' => 'success', 'pageno'=>"$pageno",'numofpage'=>"$number_of_page", 'data' => $Course);
    sendJsonResponse($response);
} else {
    $response = array('status' => 'failed', 'pageno'=>"$pageno",'numofpage'=>"$number_of_page",'data' => null);
    sendJsonResponse($response);
}

function sendJsonResponse($sentArray)
{
    header('Content-Type: application/json');
    echo json_encode($sentArray);
}

?>
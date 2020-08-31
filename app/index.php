<?php
// Connecting to the database
$connection = mysqli_connect("localhost", "admin", "password", "test");
if($connection === false){
  die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Building a class for convenience to handle entries
class Entry {
  // Define private vars here
  private $requestor = "";
  private $toolName = "";
  private $type = "";
  private $description = "";
  private $priority = "";
  private $status = "";
  private $fixConfirm = "";
  private $tester = "";

  // Constructor to initiate variables
  function __construct($req, $tool, $type, $desc, $pri, $stat, $fc, $tester) {
    $this->requestor = $req;
    $this->toolName = $tool;
    $this->type = $type;
    $this->description = $desc;
    $this->priority = $pri;
    $this->status = $stat;
    $this->fixConfirm = $fc;
    $this->tester = $tester;
  }

  // Function to perform insert into database
  function insertIntoDatabase($connection) {
    $today = "CURDATE()";
    $sql = "INSERT INTO back_log ";
    $sql .= "(requestor_id, tool_name, type, description, priority, date_filed, status, fix_confirm, date_closed, tester, image_name) ";
    $sql .= "VALUES";
    $sql .= "(";
    $sql .= "'".$this->requestor."', ";
    $sql .= "'".$this->toolName."', ";
    $sql .= "'".$this->type."', ";
    $sql .= "'".$this->description."', ";
    $sql .= "'".$this->priority."', ";
    $sql .= $today.", ";
    $sql .= "'".$this->status."', ";
    $sql .= "'".$this->fixConfirm."', ";
    $sql .= $today.", ";
    $sql .= "'".$this->tester."', ";
    $sql .= "'test.png'";
    $sql .= ")";
    if(mysqli_query($connection, $sql)) {
      return "<p>Successfully added the entry!</p>";
    } else{
      return "ERROR: Could not execute $sql. " . mysqli_error($connection);
    }
  }
}

// Function to retrieve content and populate table string
function getTable($connection) {
  $str = "";
  $sql = "SELECT * FROM back_log";
  if($result = mysqli_query($connection, $sql)){
    if(mysqli_num_rows($result) > 0){
      $str = "<table id='result_table'>";
      $str .= "<thead>";
      $str .= "<tr>";
      $str .= "<th>ID</th>";
      $str .= "<th>Requestor</th>";
      $str .= "<th>Tool Name</th>";
      $str .= "<th>Type</th>";
      $str .= "<th>Description</th>";
      $str .= "<th>Priority</th>";
      $str .= "<th>Date filed</th>";
      $str .= "<th>Status</th>";
      $str .= "<th>Fix Confirm</th>";
      $str .= "<th>Date Closed</th>";
      $str .= "<th>tester</th>";
      $str .= "<th>Image Name</th>";
      $str .= "</tr>";
      $str .= "</thead>";
      $str .= "<tbody>";
      while($row = mysqli_fetch_array($result)) {
        $str .= "<tr>";
        $str .= "<td>" . $row['id'] . "</td>";
        $str .= "<td>" . $row['requestor_id'] . "</td>";
        $str .= "<td>" . $row['tool_name'] . "</td>";
        $str .= "<td>" . $row['type'] . "</td>";
        $str .= "<td>" . $row['description'] . "</td>";
        $str .= "<td>" . $row['priority'] . "</td>";
        $str .= "<td>" . $row['date_filed'] . "</td>";
        $str .= "<td>" . $row['status'] . "</td>";
        $str .= "<td>" . $row['fix_confirm'] . "</td>";
        $str .= "<td>" . $row['date_closed'] . "</td>";
        $str .= "<td>" . $row['tester'] . "</td>";
        $str .= "<td>" . $row['image_name'] . "</td>";
        $str .= "</tr>";
      }
      $str .= "</tbody>";
      $str .= "</table>";
      // Free result set
      mysqli_free_result($result);
    } else {
      $str .= "No entries to display!";
    }
  } else{
    $str .= "ERROR: Could not able to execute $sql. " . mysqli_error($connection);
  }
  return $str;
}

// Function to check whether at least one element is filled in
function isFilled($arr) {
  return (
    !empty($arr['requestor']) ||
    !empty($arr['tool_name']) ||
    !empty($arr['type']) ||
    !empty($arr['description']) ||
    !empty($arr['priority']) ||
    !empty($arr['status']) ||
    !empty($arr['fix_confirm']) ||
    !empty($arr['tester'])
  );
}
?>
<html>
  <head>
    <title>Backlog Tracker</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>
    <script type="text/javascript">
      $(document).ready( function () {
        if($('#result_table').length)
          $('#result_table').DataTable();
      } );
    </script>
  </head>
  <body>
    <h1>Backlog Tracker</h1>
    <a href="add.html">Add New</a><br>
    <?php
    if(isFilled($_POST)) {
      $entry = new Entry(
        $_POST['requestor'],
        $_POST['tool_name'],
        $_POST['type'],
        $_POST['description'],
        $_POST['priority'],
        $_POST['status'],
        $_POST['fix_confirm'],
        $_POST['tester']
      );
      echo $entry->insertIntoDatabase($connection);
    }
    echo getTable($connection);
    ?>
  </body>
</html>
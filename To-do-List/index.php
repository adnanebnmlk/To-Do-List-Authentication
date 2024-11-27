<?php
include 'db.php';

session_start();

if(!isset($_SESSION['user_id'])){
    header("location : login.php");
    exit;
}


if($_SERVER['REQUEST_METHOD']=== 'POST'){
    $task_name=$_POST['task_name'];
    if(!empty($task_name)){
        $stmt=$conn->prepare('INSERT INTO tasks (task_name) VALUES(?)');
        $stmt->bind_param('s',$task_name);
        $stmt->execute();
        $stmt->close();
    }
}

$open_tasks=$conn->query('SELECT * FROM tasks WHERE is_complete = 0');
if (!$open_tasks) {
    die("Error in query: " . $conn->error);
}


$close_tasks=$conn->query('SELECT * FROM tasks WHERE is_complete = 1');
if (!$close_tasks) {
    die("Error in query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To do List</title>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">

    <h3><a class="navbar-brand" href="#">To Do List</a></h3>
    
    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
      <ul class="navbar-nav ms-auto mt-2 mt-lg-0 ">
        <?php if(isset($_SESSION['user_id'])) : ?>

        <li class="nav-item">
          <h6><a class="nav-link text-white" href="logout.php">Log out</a></h6>
        </li>
        <?php else : ?>
        <li class="nav-item">
          <h6><a class="nav-link text-white" href="login.php">Login</a></h6>
        </li>
        <li class="nav-item">
          <h6><a class="nav-link text-white" href="registration.php">Registration</a></h6>
        </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>


<div class="container mt-5">
    <h1 class="text-center">To Do List</h1>   
    <form class="mb-5" action="index.php" method="POST">
        <div class="input-group rounded">
            <input type="text" name="task_name" class="form-control" placeholder="New Task" required>
            <button type="submit" class="btn btn-primary rounded">Add</button>
        </div>

        <div class="row mt-5">
            <div class="col-md-6">
                <h2 class="text-center">Open Tasks</h2>
                <ul class="list-group">
                    <?php if ($open_tasks->num_rows > 0) : ?>
                        <?php while ($row = $open_tasks->fetch_assoc()): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><?php echo $row['task_name']; ?></span>
                                <div>
                                    <a href="complete_task.php?id=<?php echo $row["id"]; ?>" class="btn btn-success btn-sm">Complete</a>
                                    <a href="delete_task.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                </div>
                            </li>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <li class="list-group-item">No Open Tasks Found</li>
                    <?php endif; ?>    
                </ul>
            </div>
            <div class="col-md-6">
                <h2 class="text-center">Closed Tasks</h2>
                <ul class="list-group">
                    <?php if ($close_tasks->num_rows > 0) : ?>
                        <?php while ($row = $close_tasks->fetch_assoc()): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><?php echo $row['task_name']; ?></span>
                                <div>
                                    <a href="delete_task.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                </div>
                            </li>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <li class="list-group-item">No Closed Tasks Found</li>
                    <?php endif; ?>   
                </ul>
            </div>
        </div>
    </form> 
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
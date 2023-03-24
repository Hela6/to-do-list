<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>To do list</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Signika:wght@300;400&display=swap');

        html,
        body {
            font-family: 'Signika', sans-serif;
            width: 100%;
            background: linear-gradient(0deg, #1B1F22, #363E45 80%) no-repeat;
            background-size: cover;
            background-attachment: fixed;
            margin: 0 auto;
            color: silver;
            font-size: 1.2em;
        }

        .add {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 100px;
        }

        .list {
            width: 60%;
            margin: auto;
            display: flex;
            flex-direction: column;
        }

        .task {
            display: flex;
            flex-direction: row;
            margin-bottom: 30px;
        }

        .button {
            padding: 5px;
            margin: 25px;
        }

        form {
            display: flex;
            flex-direction: row;
        }

        input {
            border-radius: 20px;
            padding: 15px;
        }

        /* responsive for smaller screens________________________________________________________________________________*/
        @media screen and (min-width: 768px) {
            .list {
                width: 40%;
            }
        }
    </style>
</head>

<body>
    <div class="add">
        <form action="" method="POST">
            <input type="text" name="new_task" placeholder="enter a new task">
            <input type="submit" value="add to list" name="add_task">
        </form>
    </div>
    <?php
    $pdo = new PDO('sqlite:database.sqlite', null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
    ]);

    $error = null;

    try {

        if (isset($_POST['add_task'])) {
            if (!empty($_POST['new_task'])) {
                if (strlen($_POST['new_task']) < 300) {
                    $newTask = trim(htmlspecialchars($_POST['new_task']));

                    $query = $pdo->prepare('INSERT INTO tasks (task) VALUES (:newtask)');
                    $query->execute([
                        'newtask' => $newTask
                    ]);
                    if ($query->rowCount() > 0) {
                        // echo "<script>alert('new task added');</script>";
                    } else {
                        echo "<script>alert('adding failed');</script>";
                    }
                } else {
                    echo "<script>alert('task name is too long');</script>";
                }
            } else {
                echo "<script>alert('you must add a task');</script>";
            }
        }

        if (isset($_POST['delete'])) {
            if (!empty($_POST['delete'])) {
                $deleteById = $_POST['delete_by_id'];

                $query_delete = $pdo->prepare('DELETE FROM tasks WHERE id = :id');
                $query_delete->execute([
                    'id' => $deleteById
                ]);
                // echo "<script>alert('task deleted');</script>";
            }
        }
    } catch (PDOException $e) {
        $error = $e->getMessage();
    }
    ?>


    <div class="list">
        <?php
        $query_display = $pdo->prepare('SELECT * FROM tasks');
        $query_display->execute();
        $tasks = $query_display->fetchAll();

        foreach ($tasks as $task) :
        ?>


            <div class="task">

                <form action="" method="POST">

                    <input class="text" type="hidden" name="delete_by_id" value="<?php echo $task->id; ?>" />
                    <input class="button" type="submit" value="delete" name="delete">

                </form>
                <p><?php echo $task->task; ?></p>
            </div>

        <?php endforeach; ?>
    </div>

</body>

</html>
<?php
    require_once __DIR__.'/boot.php';

    $user = null;

    if (check_auth()) {
        $stmt = pdo()->prepare("SELECT * FROM `todo_users` WHERE `id` = :id");
        $stmt->execute(['id' => $_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //include 'conn.php';
    $limit = 3;
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $start = ($page - 1) * $limit;

    $page_perv = $page - 1;
    $page_next = $page + 1;

    /*
    $dbh = new PDO('mysql:dbname=db_name;host=localhost', 'логин', 'пароль');
    $sth = $dbh->prepare("SELECT * FROM `tours` ORDER BY `hotel`");
    $sth->execute();
    $list = $sth->fetchAll(PDO::FETCH_ASSOC);
    */

    /* Все варианты сортировки */
    $sort_list = array(
        'sort_user_name_asc'   => '`user_name`',
        'sort_user_name_desc'  => '`user_name` DESC',
        'sort_user_email_asc'  => '`user_email`',
        'sort_user_email_desc' => '`user_email` DESC',
        'sort_completed_asc'   => '`completed`',
        'sort_completed_desc'  => '`completed` DESC',  
    );
    
    /* Проверка GET-переменной */
    $sort = @$_GET['sort'];
    if (array_key_exists($sort, $sort_list)) {
        $sort_sql = $sort_list[$sort];
    } else {
        $sort_sql = reset($sort_list);
    }
    
    /* Запрос в БД */
    $dbh = new PDO('mysql:dbname=todo;host=localhost', 'root', '');
    
    $sth = $dbh->prepare("SELECT * FROM `todo_tasks` ORDER BY {$sort_sql} LIMIT $start, $limit");
    $sth->execute();
    $list = $sth->fetchAll(PDO::FETCH_ASSOC);

    $total = $dbh->prepare("SELECT COUNT(id) FROM `todo_tasks`");
    $total->execute();
    $total = $total->fetchAll(PDO::FETCH_ASSOC);
    //$pages = ceil( $total / $limit );


    $res =  $dbh->query("SELECT COUNT(id) FROM `todo_tasks`");
    $countNum = $res->fetchColumn();
    $pages = ceil( $countNum / $limit );
    
    /* Функция вывода ссылок */
    function sort_link_th($title, $a, $b) {
        $sort = @$_GET['sort'];
        $page = @$_GET['page'];
    
        if ($sort == $a) {
            if(isset($page) && $page >= 1) {
                return '<a class="active" href="?page=' . $page . '&sort=' . $b . '">' . $title . ' <i>▲</i></a>';
            } else {
                return '<a class="active" href="?sort=' . $b . '">' . $title . ' <i>▲</i></a>';
            }
        } elseif ($sort == $b) {
            if(isset($page) && $page >= 1) {
                return '<a class="active" href="?page=' . $page . '&sort=' . $a . '">' . $title . ' <i>▼</i></a>'; 
            } else {
                return '<a class="active" href="?sort=' . $a . '">' . $title . ' <i>▼</i></a>'; 
            } 
        } else {
            if(isset($page) && $page >= 1) {
                return '<a href="?page=' . $page . '&sort=' . $a . '">' . $title . '</a>'; 
            } else {
                return '<a href="?sort=' . $a . '">' . $title . '</a>'; 
            }
        }
    }
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Список задач</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    </head>

    <body>
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-auto me-auto">
                    <h1>Список задач</h1>
                </div>

                <div class="col-auto">
                    <?php if ($user) { ?>
                        <form method="post" action="do_logout.php">
                            <button type="submit" class="btn btn-outline-danger">Выход <i class="bi bi-door-open"></i></button>
                        </form>
                    <?php } else { ?>
                        <form method="post" action="login.php">
                            <button type="submit" class="btn btn-outline-secondary"><i class="bi bi-key"></i> Авторизация</button>
                        </form>
                    <?php } ?>
                </div>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th scope="col"><?php echo sort_link_th('Имя пользователя', 'sort_user_name_asc', 'sort_user_name_desc'); ?></th>
                        <th scope="col"><?php echo sort_link_th('e-mail', 'sort_user_email_asc', 'sort_user_email_desc'); ?></th>
                        <th scope="col">Задача</th>
                        <th scope="col"><?php echo sort_link_th('Статус', 'sort_completed_asc', 'sort_completed_desc'); ?></th>
                        <?php if ($user) { ?>
                            <th scope="col"></th>
                        <?php } ?>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach($list as $task) : ?> 
                        <tr>
                            <td><?= $task['user_name']; ?></td>
                            <td><?= $task['user_email']; ?></td>
                            <td><?= $task['task_description']; ?></td>
                            <td>
                                <?php switch ($task['completed']): case '0': ?>
                                    <span class="badge text-bg-primary">Новая</span>
                                    <?php break;?>
                                <?php case '1': ?>
                                    <span class="badge text-bg-success">Выполнена</span>
                                    <?php break;?>
                                <?php endswitch; ?>
                                
                                <?php if ($task['edited'] == 1) { ?>
                                    <span class="badge text-bg-info">Отредактировано администратором</span>
                                <?php } ?>
                            </td>
                            <?php if ($user) { ?>
                                <td>
                                    <div class="text-end">
                                        <a class="icon-link" href="#" data-bs-toggle="modal" data-bs-target="#taskEdit<?= $task['id']; ?>">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </div>

                                    <div class="modal fade" id="taskEdit<?= $task['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="POST" action="update.php" class="modal-content">
                                                <input type="hidden" name="id" value="<?= $task['id']; ?>">
                                                <input type="hidden" name="edited" value="1">

                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Редактирование задачи</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>

                                                <div class="modal-body">                   
                                                    <div class="mb-3">
                                                        <label for="taskUsrName" class="form-label">Имя пользователя</label>
                                                        <input type="text" class="form-control" id="taskUsrName" name="user_name" value="<?= $task['user_name']; ?>">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="taskUsrEmail" class="form-label">E-mail</label>
                                                        <input type="email" class="form-control" id="taskUsrEmail" placeholder="name@beejee.org" name="user_email" value="<?= $task['user_email']; ?>">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="taskDesc" class="form-label">Задача</label>
                                                        <textarea class="form-control" id="taskDesc" rows="3" name="task_description"><?= $task['task_description']; ?></textarea>
                                                    </div>

                                                    <div class="mb-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" name="completed" <?php if($task['completed']) : ?>checked<?php endif; ?>>
                                                            <label class="form-check-label" for="flexCheckDefault">
                                                                Задача выполнена
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                                                    <button type="submit" class="btn btn-primary">Сохранить</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="row justify-content-between">
                <div class="col-auto me-auto">
                    <nav aria-label="...">
                        <ul class="pagination">
                            <?php if(isset($_GET['page']) && $_GET['page'] > 1) : ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=1<?php if(isset($sort)) : ?>&sort=<?= $sort; ?><?php endif; ?>" aria-label="Первая">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $page_perv; ?><?php if(isset($sort)) : ?>&sort=<?= $sort; ?><?php endif; ?>" aria-label="Предыдущая">
                                        <span aria-hidden="true">&lsaquo;</span>
                                    </a>
                                </li>
                            <?php else : ?>
                                <li class="page-item disabled">
                                    <span class="page-link" aria-label="Первая">
                                        <span aria-hidden="true">&laquo;</span>
                                    </span>
                                </li>
                                <li class="page-item disabled">
                                    <span class="page-link" aria-label="Предыдущая">
                                        <span aria-hidden="true">&lsaquo;</span>
                                    </span>
                                </li>
                            <?php endif; ?>

                            <?php for($i = 1; $i <= $pages; $i++) : ?> 
                                <?php if(!isset($_GET['page']) && $i == 1) : ?>
                                    <li class="page-item active" aria-current="page">
                                        <span class="page-link"><?= $i; ?></span>
                                    </li>
                                <?php elseif(isset($_GET['page']) && $_GET['page'] == $i) : ?>
                                    <li class="page-item active" aria-current="page">
                                        <span class="page-link"><?= $i; ?></span>
                                    </li>
                                <?php else : ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?= $i; ?><?php if(isset($sort)) : ?>&sort=<?= $sort; ?><?php endif; ?>"><?= $i; ?></a>
                                    </li>
                                <?php endif; ?>
                            <?php endfor; ?>                            

                            <?php if(isset($_GET['page']) && $_GET['page'] < $pages) : ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $page_next; ?><?php if(isset($sort)) : ?>&sort=<?= $sort; ?><?php endif; ?>" aria-label="Следующая">
                                        <span aria-hidden="true">&rsaquo;</span>
                                    </a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $pages; ?><?php if(isset($sort)) : ?>&sort=<?= $sort; ?><?php endif; ?>" aria-label="Последняя">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            <?php else : ?>
                                <li class="page-item disabled">
                                    <span class="page-link" aria-label="Следующая">
                                        <span aria-hidden="true">&rsaquo;</span>
                                    </span>
                                </li>
                                <li class="page-item disabled">
                                    <span class="page-link" aria-label="Последняя">
                                        <span aria-hidden="true">&raquo;</span>
                                    </span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>

                <div class="col-auto">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#taskAdd"><i class="bi bi-plus-circle"></i> Добавить задачу</button>
                </div>
            </div>
        </div>

        <div class="modal fade" id="taskAdd" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="create.php" class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Новая задача</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">                   
                        <div class="mb-3">
                            <label for="taskUsrName" class="form-label">Имя пользователя</label>
                            <input type="text" class="form-control" id="taskUsrName" name="user_name">
                        </div>

                        <div class="mb-3">
                            <label for="taskUsrEmail" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="taskUsrEmail" placeholder="name@beejee.org" name="user_email">
                        </div>

                        <div class="mb-3">
                            <label for="taskDesc" class="form-label">Задача</label>
                            <textarea class="form-control" id="taskDesc" rows="3" name="task_description"></textarea>
                        </div>

                        <?php if ($user) { ?>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="flexCheckDefault" name="completed" <?php if($task['completed']) : ?>checked<?php endif; ?>>
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Задача выполнена
                                    </label>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary">Добавить</button>
                    </div>
                </form>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" async defer></script>
    </body>
</html>
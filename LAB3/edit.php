<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    $title = clearData($_GET['title']);
    $type = clearData($_GET['type']);
    $location = clearData($_GET['location']);
    $rel_date = clearData($_GET['rel_date']);
    $release_date = clearData($_GET['release_date']);
    $description = clearData($_GET['description']);
    $uploadlink = clearData($_GET['uploadlink']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (!empty($_POST['type']) && !empty($_POST['location']) && !empty($_POST['rel_date']) && !empty($_POST['release_date']) && !empty($_POST['description']))
    {
        $_SESSION['Item']['title'] = clearData($_POST['title']);
        $_SESSION['Item']['type'] = clearData($_POST['type']);
        $_SESSION['Item']['location'] = clearData($_POST['location']);
        $_SESSION['Item']['rel_date'] = clearData($_POST['rel_date']);
        $_SESSION['Item']['release_date'] = clearData($_POST['release_date']);
        $_SESSION['Item']['description'] = clearData($_POST['description']);
        if (!empty($_FILES['uploadfile']['name']))
        {
            $tmp_path = 'tmp/';
            $file_path = 'Images/';
            $result = imageCheck();
            if ($result == 1)
            {
                $name = resize($_FILES['uploadfile']);
                $uploadfile = $file_path . $name;
                if (@copy($tmp_path . $name, $file_path . $_POST['title'] . '.jpg'))
                    $uploadlink = "Images/". $_POST['title'] . '.jpg';
                unlink($tmp_path . $name);
                $_SESSION['Item']['uploadlink'] = $uploadlink;
            }
            else
            {
                echo $result;
                exit;
            }
        }
        header("Location: index.php?page=catalog");
        exit;
    }
    else
    {
        echo 'Заполните форму полностью!';
    }
}
?>

<center><h3>Редактировать экспонат или достопримечательность</h3></center>
<table align='center'>
    <tr>
        <td>
            <form method='POST' action='index.php?page=edit' enctype='multipart/form-data'>
                <p>Название: <input type='text' name='title' value='<?php echo $title; ?>'></p>
                <p>Тип:&nbsp;&nbsp;&nbsp;&nbsp;<select size='10' multiple name='type'>
                        <option value='Поп' <?php if($type == 'Поп') echo 'selected'; ?>>Архитектурный памятник</option>
                        …[omitted]
                    </select></p>
                <p>Местоположение: <input type='text' name='location' value='<?php echo $location; ?>'></p>
                <p>Дата открытия: <input type='text' name='rel_date' value='<?php echo $rel_date; ?>'></p>
                <p>Дата релиза: <input type='text' name='release_date' value='<?php echo $release_date; ?>'></p>
                <p>Описание: <textarea name='description'><?php echo $description; ?></textarea></p>
                <p>Загрузить изображение: <input type='file' name='uploadfile'></p>
                <input type='hidden' name='uploadlink' value='<?php echo $uploadlink; ?>'>
                <input type='submit' value='Сохранить изменения'>
            </form>
        </td>
    </tr>
</table>
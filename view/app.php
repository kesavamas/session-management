<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="/regenerate" method="post">
        <input type hidden value="<?php echo $csrf ?>" name="csrf" />
        <button type="submit">Create session</button>
    </form>
</body>

</html>
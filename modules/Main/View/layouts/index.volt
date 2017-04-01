<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="author" content=""/>

    <title> {{ title }} </title>

    <link rel="stylesheet" href="css/default.css">
</head>
<body>
<div class="main-content">
    YOUR APPLICATION LAYOUT
    <nav class="navbar">
        {{ tag.navigation().renderMenu() }}
    </nav>

    <article class="content">
        {{ content() }}
    </article>
    <footer class="main-footer sticky footer-type-1">
    </footer>
</div>
</body>
</html>
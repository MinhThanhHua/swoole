<!DOCTYPE html>
<html lang="en">
<head>
    <title>Thanh Handsome</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?= $this->Html->css([
        'util.css',
        'main.css',
        'font-awesome.min.css',
        'bootstrap/css/bootstrap.min.css',
        'animate/animate.css',
        'select2/select2.min.css',
        'perfect-scrollbar/perfect-scrollbar.css'
    ]) ?>
    <?= $this->fetch('css') ?>

    <?= $this->Html->script([
        'jquery/jquery-3.2.1.min.js',
        'main.js',
        'bootstrap/js/popper.js',
        'bootstrap/js/bootstrap.min.js',
        'select2/select2.min.js'
    ]) ?>
    <?= $this->fetch('script') ?>
</head>
<body>
<div class="container">
    <div>
        <?= $this->fetch('content') ?>
    </div>
</div>
</body>
</html>

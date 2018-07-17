<?php //$this->extend('layout.html.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Example</title>
</head>

<body><?php //$this->slots()->output('_content') ?>
    
    <section id="marked-content">
    <?= $this->wysiwyg("specialContent", [
        "height" => 200
    ]); ?>
</section>

</body>
</html>


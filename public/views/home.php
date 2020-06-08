<?php require_once('public/inc/head.php');?>
<h1>Hello World!</h1>

<!-- passing data from a model -->
<ul>
    <?php echo SECRET_KEY ?>
    <?php echo $user->login();?>
</ul>
<?php require_once('public/inc/footer.php');?>
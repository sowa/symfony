<traces>
<?php foreach ($exception->getTrace() as $i => $trace): ?>
        <trace>
        <?php echo $view->render('FrameworkBundle:Exception:trace.txt.php', array('i' => $i, 'trace' => $trace)) ?>

        </trace>
<?php endforeach; ?>
    </traces>

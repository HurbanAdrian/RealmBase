<?php

/**
 * @var \Framework\Http\HttpException $exception
 */
/**
 * @var bool $showDetail
 */
/**
 * @var \Framework\Support\View $view
 */

$view->setLayout(null);

?>

<h1><?php echo $exception->getCode() . " - " . $exception->getMessage() ?></h1>

<?php
if ($showDetail && $exception->getCode() != 500) :
    ?>
    <?php echo get_class($exception) ?>: <strong><?php echo $exception->getMessage() ?></strong>
    in file <strong><?php echo $exception->getFile() ?></strong>
    at line <strong><?php echo $exception->getLine() ?></strong>
    <pre>Stack trace:<br><?php echo $exception->getTraceAsString() ?></pre>
<?php endif; ?>

<?php
while ($showDetail && $exception->getPrevious() != null) { ?>
    <?php echo get_class($exception->getPrevious()) ?>: <strong><?php echo $exception->getPrevious()->getMessage() ?></strong>
    in file <strong><?php echo $exception->getPrevious()->getFile() ?></strong>
    at line <strong><?php echo $exception->getPrevious()->getLine() ?></strong>
    <pre>Stack trace:<br><?php echo $exception->getPrevious()->getTraceAsString() ?></pre>
    <?php $exception = $exception->getPrevious(); ?>
<?php } ?>

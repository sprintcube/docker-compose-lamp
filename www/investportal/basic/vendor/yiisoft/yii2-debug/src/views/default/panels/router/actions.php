<?php

use yii\helpers\Html;

/* @var $actionRoutes yii\debug\models\router\ActionRoutes */
?>
<?php if (count($actionRoutes->routes) === 0): ?>
    <h3>No actions configured.</h3>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>Action</th>
                <th>Route</th>
                <th>First Matching Rule</th>
                <th>Rules Tested</th>
            </tr>
            </thead>
            <tbody>
                <?php $i = 1; foreach ($actionRoutes->routes as $action => $route): ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= $action; ?></td>
                        <td><?= Html::encode($route['route']) ?></td>
                        <td><?= Html::encode($route['rule']) ?></td>
                        <td><?= Html::encode($route['count']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif;

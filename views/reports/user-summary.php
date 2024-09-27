<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var array $summary */

$this->title = $summary['name'] . ' ' . $summary['date'] . ' - Summary';
$this->params['breadcrumbs'][] = ['label' => 'Report', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Summary', 'url' => ['summary']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-summary">

    <h1 class="mt-6"><?= Html::encode($this->title) ?></h1>

    <div class="sm:max-w-2xl">
        <div class="mt-6 p-5 shadow-lg rounded-md">
            <table id="w0" class="table-classic">
                <tbody>
                    <tr>
                        <th>Name</th>
                        <td><?= $summary['name'] ?></td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td><?= $summary['date'] ?></td>
                    </tr>
                    <tr>
                        <th>Tokens</th>
                        <td><?= $summary['count'] ?></td>
                    </tr>
                    <tr>
                        <th>Served</th>
                        <td><?= $summary['served'] ?></td>
                    </tr>
                    <tr>
                        <th>Avg. wait time</th>
                        <td><?= $summary['wait_time'] ?></td>
                    </tr>
                    <tr>
                        <th>Avg. serve time</th>
                        <td><?= $summary['serve_time'] ?></td>
                    </tr>
                    <tr>
                        <th>Avg. recall time</th>
                        <td><?= $summary['recall_time'] ?></td>
                    </tr>
                    <tr>
                        <th>Avg. recall</th>
                        <td><?= $summary['recall_count'] ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?php use_stylesheet('bootstrap.min.css') ?>
<?php use_javascript('bootstrap.min.js') ?>
<div class="container-fluid">
    <div stlye=" text-align:center">

        <table class="table">
            <tbody>
                <tr>
                    <th>Id:</th>
                    <td><?php echo $ks_wc_event->getId() ?></td>
                </tr>
                <tr>
                    <th>Subject:</th>
                    <td><?php echo $ks_wc_event->getSubject() ?></td>
                </tr>
                <tr>
                    <th>Description:</th>
                    <td><?php echo $ks_wc_event->getDescription() ?></td>
                </tr>
                <tr>
                    <th>Start time:</th>
                    <td><?php echo $ks_wc_event->getStartTime() ?></td>
                </tr>
                <tr>
                    <th>End time:</th>
                    <td><?php echo $ks_wc_event->getEndTime() ?></td>
                </tr>
                <tr>
                    <th>Is all day event:</th>
                    <td><?php echo $ks_wc_event->getIsAllDayEvent() ?></td>
                </tr>
                <tr>
                    <th>Color:</th>
                    <td><?php echo $ks_wc_event->getColor() ?></td>
                </tr>
                <tr>
                    <th>Recurring rule:</th>
                    <td><?php echo $ks_wc_event->getRecurringRule() ?></td>
                </tr>
            </tbody>
        </table>
    </div>      
</div>
<table>

    <hr />

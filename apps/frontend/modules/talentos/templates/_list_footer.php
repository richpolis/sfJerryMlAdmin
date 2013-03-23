<?php use_helper('Escaping') ?>
<?php if ($sf_user->getSeleccionarTalentos()): ?>

    <?php if (count($sf_user->getTalentos()) > 0): ?>
        <br/>
        <br/>

        <h2>Talentos Seleccionados</h2>
        <table style="width: 100%;">
            <tr>
                <th>
                    Imagen
                </th>
                <th>
                    Talento
                </th>
                <th>
                    Status
                </th>
                <th>
                    Acciones
                </th>
            </tr>
            <?php foreach ($talentosSeleccionados as $talento): ?>
                <tr>
                    <td>
                        <?php include_partial('imagen', array('talentos' => $talento)); ?>
                    </td>
                    <td><?php echo $talento ?></td>
                    <td>
                        <?php if ($talento->getIsActive()): ?>
                            <img alt="Checked" title="Checked" src="/sfDoctrinePlugin/images/tick.png">
                        <?php endif; ?>
                    </td>
                    <td>
                        <ul class="sf_admin_td_actions">
                            <li class="sf_admin_action_select">
                                <?php echo link_to(__('Quitar Seleccion', array(), 'messages'), 'talentos/ListRemove?slug=' . $talento->getSlug(), array()) ?>
                            </li>
                        </ul>

                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

<?php endif; ?>

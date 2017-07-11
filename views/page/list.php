<?php
/** @var object[] $items */
if ($items) {
    reset($items);
    $properties = get_object_vars(current($items));
    unset($properties['id']);
    $properties = array_merge(['id'], array_keys($properties));
    ?>
    <table>
        <tr>
            <?php foreach ($properties as $property) {
                ?>
                <th><?= $property ?></th><?php
            } ?>
        </tr>
        <?php foreach ($items as $item) {
            ?>
            <tr>
            <?php foreach ($properties as $property) {
                ?>
                <td><?= $item->$property ?> </td> <?php
            } ?>
            </tr><?php
        } ?>
    </table>
    <?php
}
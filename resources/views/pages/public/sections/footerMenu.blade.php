<?php
//to change menu items go to config/base_config.php
//create new pages from DB
//and put their id on publicMenuPages and publicSubmenuPages appropriately
?> 
<!-- RD Navbar Nav-->
<?php 
$menuClass = 'list footer-classic-list footer-classic-list_2-cols';
$submenuClass = '';
$activeClass = 'active';
?>
<ul class="<?= $menuClass ?>">
    <?php foreach ($menuMain->menuTopLevelItems() as $menuItem): ?>
    <?php 
    $subItems = null;
    $menuItemClass = (!empty($subItems) ? '' : '');
    $menuLinkClass = (!empty($subItems) ? '' : '');
    $activeItemClass = (!empty($subItems) ? '' : ($menuItem->active() ? $activeClass : ''));
    $url = (!empty($subItems) ? '#' : e($menuItem->url()));
    ?>
    <li class="<?= $menuItemClass  ?> <?= $activeItemClass  ?>">
        <a class="<?= $menuLinkClass  ?>" href="<?= $url ?>"><?= e($menuItem->name());  ?></a>
        <?php if(!empty($subItems)): ?>
            <ul class="<?= $submenuClass  ?>">
                <?php foreach ($subItems as $submenuItem): ?>
                <?php 
                $subItems = $submenuItem->getSubItems($menuMain);
                $menuItemClass = (!empty($subItems) ? '' : '');
                $menuLinkClass = (!empty($subItems) ? '' : '');
                $activeItemClass = (!empty($subItems) ? '' : ($submenuItem->active() ? $activeClass : ''));
                $url = (!empty($subItems) ? e($submenuItem->url()) : e($submenuItem->url()));
                ?>
                <li class="<?= $menuItemClass  ?> <?= $activeItemClass  ?>">
                    <a href="<?= $url ?>"><?= e($submenuItem->name());  ?></a>
                </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </li>
    <?php endforeach; ?>
</ul>
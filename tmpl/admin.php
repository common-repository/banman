<?php if(!class_exists('WP_List_Table')) require_once(ABSPATH.'wp-admin/includes/class-wp-list-table.php'); ?>
<div class="wrap">
<form id="banman-list-form" method="post">
<h1><?php _e('List of banners', 'banman'); ?> <a href="?page=edit" class="page-title-action"><?php _e('Add new', 'banman'); ?></a></h1>
<?php $BanmanTable->search_box(__('search', 'banman'), 'search_id'); ?>
<?php echo $BanmanTable->display(); ?>
<?php BanMan::sorttable_init(); ?>
</form>
<style>
/*th.column-name {
    width: 250px;
}*/
th.column-url {
    width: 90px;
}
th.column-id, th.column-publish {
    width: 75px;
}
th.column-type {
    width: 65px;
}
th.column-show_count, th.column-click_count {
    width: 115px;
}
a.banner-thumb img {
    width: 80px;
}
.dashicons.dashicons-hidden {
    color: #999;
}
</style>

<?php

defined('ABSPATH') || exit;

global $rz_explore;

?>

<div class="rz-paging">
    <?php

        $base = Rz()->get_explore_page_url();

        echo Rz()->pagination([
            'base' => add_query_arg( [ 'onpage' => '%#%' ], $base ),
            'format' => '?onpage=%#%',
            'current' => $rz_explore->query()->page,
            'total' => $rz_explore->query()->posts->max_num_pages,
        ]);

    ?>
</div>

<?php Rz()->the_template('routiz/explore/summary'); ?>

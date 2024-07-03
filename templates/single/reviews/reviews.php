<?php

defined('ABSPATH') || exit;

// check if comments are enabled
// if( ! $reviews->comments_open ) {
//     return;
// }

// review stats
Rz()->the_template('routiz/single/reviews/stats');

// list of reviews
Rz()->the_template('routiz/single/reviews/comments/comments');

// leave a review form
// Rz()->the_template('routiz/single/reviews/form');

// leave a review modal
Rz()->the_template('routiz/single/modal/submit-review');
Rz()->the_template('routiz/single/modal/review-reply');

<?php


namespace CommonsBooking\Repository;


use CommonsBooking\Wordpress\CustomPostType\CustomPostType;

abstract class PostRepository
{

    public static function getByPostById($postId)
    {
        $post = get_post($postId);

        if($post instanceof \WP_Post) {
            if($post->post_type == \CommonsBooking\Wordpress\CustomPostType\Timeframe::getPostType()) {
                $type = get_post_meta($post->ID, 'type', true);
                switch ($type) {
                    case \CommonsBooking\Wordpress\CustomPostType\Timeframe::BOOKING_ID:
                        return new \CommonsBooking\Model\Booking($post);
                        break;
                }
            }

            if($post->post_type == \CommonsBooking\Wordpress\CustomPostType\Item::getPostType()) {
                return new \CommonsBooking\Model\Item($post);
            }

            if($post->post_type == \CommonsBooking\Wordpress\CustomPostType\Location::getPostType()) {
                return new \CommonsBooking\Model\Location($post);
            }
        }
        return $post;
    }

}

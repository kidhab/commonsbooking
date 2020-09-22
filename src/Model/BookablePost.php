<?php


namespace CommonsBooking\Model;


use CommonsBooking\Repository\Timeframe;

class BookablePost extends CustomPost
{

    /**
     * @param false $asModel
     *
     * @return array
     * @throws \Exception
     * @TODO: should support $args
     */
    public function getBookableTimeframes($asModel = true)
    {
        if(get_called_class() == Location::class) {
            $bookableTimeframes = Timeframe::get(
                [$this->ID],
                [],
                [\CommonsBooking\Wordpress\CustomPostType\Timeframe::BOOKABLE_ID],
                $this->getDate() ?: null,
                $asModel
            );

        }
        if(get_called_class() == Item::class) {
            $bookableTimeframes = Timeframe::get(
                [],
                [$this->ID],
                [\CommonsBooking\Wordpress\CustomPostType\Timeframe::BOOKABLE_ID],
                $this->getDate() ?: null,
                $asModel
            );
        }
        return $bookableTimeframes;
    }

    /**
     * Returns bookable timeframes for a specific location
     *
     * @param bool $asModel
     *
     * @return array
     * @throws \Exception
     */
    public function isBookable($asModel = false)
    {
        return count($this->getBookableTimeframes());
    }

}
<?php


namespace CommonsBooking\API;



class ItemsRoute extends BaseRoute
{

    /**
     * The base of this controller's route.
     *
     * @var string
     */
    protected $rest_base = "items";

    /**
     * Commons-API schema definition.
     * @var string
     */
    protected $schemaUrl = "https://raw.githubusercontent.com/wielebenwir/commons-api/master/commons-api.items.schema.json";

    /**
     * Returns raw data collection.
     * @param $request
     *
     * @return \stdClass
     */
    public function getItemData($request) {
        $data = new \stdClass();
        $data->items = [];

        $params = $request->get_params();
        $args = [];
        if(array_key_exists('id', $params)) {
            $args = [
                'p' => $params['id']
            ];
        }

        $items = \CommonsBooking\Repository\Item::get($args);
        foreach ($items as $item) {
            $itemdata = $this->prepare_item_for_response($item, $request);
            $data->items[] = $this->prepare_response_for_collection($itemdata);
        }
        return $data;
    }

    /**
     * Get a collection of items
     *
     * @param $request Full data about the request.
     *
     * @return \WP_Error|\WP_REST_Response
     */
    public function get_items($request)
    {
        //get parameters from request
        $params = $request->get_params();

        $data = $this->getItemData($request);

        // Add owners data
        if(!array_key_exists('owners', $params) || $params['owners'] != "false") {
            $ownersRoute = new OwnersRoute();
            $data->owners = $ownersRoute->getItemData($request);
        }

        // Add projects data
        if(!array_key_exists('projects', $params) || $params['projects'] != "false") {
            $projectsRoute = new ProjectsRoute();
            $data->projects = $projectsRoute->getItemData($request);
        }

        // Add locations data
        if(!array_key_exists('locations', $params) || $params['locations'] != "false") {
            $locationsRoute = new LocationsRoute();
            $data->locations = $locationsRoute->getItemData($request);
        }

        // Add availability data
        if(!array_key_exists('availability', $params) || $params['availability'] != "false") {
            $data->availability = [];
            foreach($data->items as $item) {
                $availabilityRoute = new AvailabilityRoute();
                $data->availability = array_merge($data->availability, $availabilityRoute->getItemData($item->id));
            }
            
        }

        if(WP_DEBUG) {
            $this->validateData($data);
        }
        return new \WP_REST_Response($data, 200);
    }

    /**
     * Get one item from the collection
     *
     * @param \WP_REST_Request $request Full data about the request.
     *
     * @return \WP_Error|\WP_REST_Response
     */
    public function get_item($request)
    {
        $data = $this->getItemData($request);
        if(WP_DEBUG) {
            $this->validateData($data);
        }
        return new \WP_REST_Response($data, 200);
    }

    /**
     * @param mixed $item
     * @param \WP_REST_Request $request
     *
     * @return array|\WP_Error|\WP_REST_Response
     */
    public function prepare_item_for_response($item, $request)
    {
        $preparedItem = new \stdClass();
        $preparedItem->id = $item->ID . '';
        $preparedItem->name = $item->post_title;
        $preparedItem->url         = get_permalink($item->ID);
        $preparedItem->description = $item->post_content;
        $preparedItem->ownerId     = $item->post_author;
        $preparedItem->projectId   = "1";

        if(get_the_post_thumbnail_url($item->ID, 'full')) {
            $preparedItem->image =get_the_post_thumbnail_url($item->ID, 'full');
        }

        return $preparedItem;
    }

}

<?php

/**
 * Format a list of items
 *
 * @param $lostItems
 * @return array
 */
function formatItems($lostItems) {
    $formattedItems = [];
    foreach ($lostItems as $lostItem) {
        $item = formatItem($lostItem);
        $formattedItems[] = $item;
    }

    return $formattedItems;
}

/**
 * Formats multiple categories
 *
 * @param $categories
 * @return array
 */
function formatCategories($categories) {
    $formattedCategories = [];
    foreach($categories as $category) {
        $category = formatCategory($category);
        $formattedCategories[] = $category;
    }

    return $formattedCategories;
}

/**
 * Formats a single category
 *
 * @param $category
 * @return object
 */
function formatCategory($category) {
    $formattedCategory = (object) [
        "id" => $category->id,
        "name" => $category->name,
        "dateCreated" => formatTime($category->created_at),
        "dataUpdated" => formatTime($category->updated_at)
    ];

    return $formattedCategory;
}

/**
 * Format request data
 * extracts returned request queries to match data on client side
 *
 * @param $item
 * @return object - array of formatted items
 */
function formatItem($item)
{
    $formattedItem = (object) [
        "id" => $item->id,
        "name" => $item->name,
        "description" => $item->description,
        "category" => $item->category,
        "finder" => $item->finder,
        "owner" => $item->owner,
        "found" => $item->found,
        "dateCreated" => formatTime($item->created_at),
        "dateUpdated" => formatTime($item->updated_at)
    ];

    return $formattedItem;
}

/**
* Formats user from database to a more consistent format
*
* @param object $user - user whose data is to be formatted
*
* @return object - format
*/
function formatUser($user) {
    $formattedUser = (object) [
        "id" => $user->id,
        "username" => $user->username,
        "phone" => $user->phone,
        "email" => $user->email,
        "location" => $user->location
    ];

    return $formattedUser;
}

/**
 * Checks if the given time is null and returns null else it returns
 * the time in the date format
 *
 * @param string $time - the time in the date format
 *
 * @return mixed null|string
 */
function formatTime($time)
{
    return $time === null ? null : date("Y-m-d H:i:s", $time->getTimestamp());
}

/**
 * Formats pagination object
 *
 * @param LostItem[] $lostItems
 *
 * @return array - array of formatted pagination details
 */
function formatPagination($lostItems) {
    $pagination = [
        "totalCount" => $lostItems->total(),
        "pageSize" => $lostItems->perPage(),
        "lastPage" => $lostItems->lastPage(),
        "currentPage" => $lostItems->currentPage(),
    ];

    return $pagination;
}

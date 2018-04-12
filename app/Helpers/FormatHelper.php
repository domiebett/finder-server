<?php

/**
 * Format a list of items
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
* Format request data
* extracts returned request queries to match data on client side
*
* @param array $items - collection of items
*
* @return array - array of formatted items
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
        "userName" => $user->user_name,
        "email" => $user->email,
        "firstName" => $user->first_name,
        "lastName" => $user->lastName,
        "location" => $user->location,
        "dateCreated" => formatTime($user->created_at),
        "dateUpdated" => formatTime($user->updated_at)
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
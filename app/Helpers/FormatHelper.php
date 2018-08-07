<?php

use App\Models\LostItem;

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
 * @param LostItem $item
 * @return array - array of formatted items
 */
function formatItem(LostItem $item)
{
    $itemFiles = $item->files()->get();

    $formattedItem = [
        "id" => $item->id,
        "name" => $item->name,
        "description" => $item->description,
        "category" => $item->category,
        "finder" => $item->finder,
        "owner" => $item->owner,
        "found" => $item->found,
        "images" => formatFiles($itemFiles),
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
 * @return array - format
 */
function formatUser($user) {
    $formattedUser = [
        "id" => $user->id,
        "username" => $user->username,
        "phone" => $user->phone,
        "email" => $user->email,
        "location" => $user->location
    ];

    return $formattedUser;
}

/**
 * Format item files
 *
 * @param $files
 * @return array
 */
function formatFiles($files) {
    $formattedFiles = [];
    foreach($files as $file) {
        $file = formatFile($file);
        $formattedFiles[] = $file;
    }

    return $formattedFiles;
}

/**
 * Format single item file
 *
 * @param $file
 * @return array
 */
function formatFile($file) {
    $formattedFile = [
        "id" => $file->id,
        "name" => $file->name,
        "identity" => $file->identity,
        "url" => $file->url
    ];

    return $formattedFile;
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

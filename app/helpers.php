<?php
use Carbon\Carbon;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\TransformerAbstract;
use Auth;
use DB;

/**
 * Merge two array together, passing the second array through array filter to remove null values
 * @param array $base
 * @param array $newItems
 * @return array
 */
function array_compare(array $base, array $newItems)
{
//    dd($base);
    return array_merge($base, array_filter($newItems));
}

/**
 * Find out how many days ago a date was
 * @param $date
 * @return mixed
 */
function getHowManyDaysAgo($date)
{
    $now = Carbon::now();
    $date = Carbon::createFromFormat('Y-m-d', $date);
    $diff = $now->diff($date);
    $days_ago = $diff->days;
    return $days_ago;
}

/**
 *
 * @param $table
 * @param $name
 * @return mixed
 */
function countItem($table, $name)
{
    $count = DB::table($table)
        ->where('name', $name)
        ->where('user_id', Auth::user()->id)
        ->count();

    return $count;
}

/**
 *
 * @param $name
 * @param $table
 * @return mixed
 */
function pluckName($name, $table)
{
    $name = DB::table($table)
        ->where('name', $name)
        ->where('user_id', Auth::user()->id)
        ->pluck('name');

    return $name;
}

/**
 *
 * @param $date
 * @param $for
 * @return string
 */
//function convertDate(Carbon $date, $for = NULL)
//{
//    switch($for) {
//        case "sql":
//            return $date->format('Y-m-d');
//            break;
//        default:
//            return $date->format('d/m/y');
//            break;
//    }
//}

/**
 *
 * @param $resource
 */
function transform($resource)
{
    $manager = new Manager();
    $manager->setSerializer(new DataArraySerializer);

    $manager->parseIncludes(request()->get('includes', []));

//    return $manager->createData($resource);
    return $manager->createData($resource)->toArray();
}

/**
 *
 * @param $model
 * @param TransformerAbstract $transformer
 * @param null $key
 * @return Collection
 */
function createCollection($model, TransformerAbstract $transformer, $key = null)
{
    return new Collection($model, $transformer, $key);
}

/**
 * @param Model               $model
 * @param TransformerAbstract $transformer
 * @param null                $key
 * @return Item
 */
function createItem($model, TransformerAbstract $transformer, $key = null)
{
    return new Item($model, $transformer, $key);
}

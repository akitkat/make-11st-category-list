<?php

exec('rm category.tsv');

$fp = fopen('category.tsv', 'w');
seek($fp);
fclose($fp);

function seek ($fp, int $cateory_id = 0, array $category_id_ancestors = [], array $category_name_ancestors = [])
{
    sleep(1);
    $url = $_SERVER['BASE_URL'] . $cateory_id;
    $content = file_get_contents($url);
    $content = ltrim($content, '(');
    $content = rtrim($content, ')');
    $categories = json_decode($content, true);
    foreach ($categories as $category) {
        fputcsv($fp, [
            ...array_pad([
                ...$category_id_ancestors,
                $category['dispCtgrNo']
            ], 4, '-'),
            ...array_pad([
                ...$category_name_ancestors,
                $category['dispCtgrNm']
            ], 4, '-')
        ], "\t");

        if ($category['isLast'] == 'N') {
            seek($fp, $category['dispCtgrNo'], [
                ...$category_id_ancestors,
                $category['dispCtgrNo']
            ], [
                ...$category_name_ancestors,
                $category['dispCtgrNm']
            ]);
        }
    }
}

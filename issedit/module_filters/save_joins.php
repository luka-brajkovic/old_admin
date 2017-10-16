<?php

if ($category != '') {

    $languagesThis = new Collection("languages");
    $languagesArrHere = $languagesThis->getCollection("WHERE code!=''");
    foreach ($languagesArrHere as $languageThis) {

        getValuesForJoins($category, $languageThis->id, $resource_id);
    }

    $rid = $resource_id;
}

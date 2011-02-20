<?php
// the threshold value of the number of Google results
// above which you consider the string to be SPAM.
// Using a smaller value might increase false positives.
define("BANHAMMER_THRESHOLD",1000);
// the minimum number of words for which the test is
// performed.
// Using a smaller value might increase false positives.
define("BANHAMMER_MINWORDS",20);
// If you have a Google API key, paste it here (optional).
//define("BANHAMMER_GOOGLEAPIKEY","");

/**
 * This function checks wether the provided input is considered SPAM or not.
 * Based on an idea mentioned here:
 * http://www.webdevrefinery.com/forums/topic/7318-the-lazy-spammer-banhammer/
 * Using http://code.google.com/intl/de/apis/websearch/docs/#fonje_snippets
 * \param   string      A string to be checked for SPAM content.
 * \return  boolean     true if string is considered SPAM, false if not.
 */
function banhammer($string) {
    // remove double quotes, so that quote search in Google won't break.
    $string = str_replace('"','',$string);
    // if the string is shorter than BANHAMMER_MINWORDS words, we risk
    // banning false positives. Banhammer will therefore only act on
    // phrases with more than BANHAMMER_MINWORDS words.
    if(substr_count($string,' ')+1 < constant("BANHAMMER_MINWORDS"))
        return false;
    // construct Google API call
    $url = "https://ajax.googleapis.com/ajax/services/search/web?v=1.0"
         . "&q=" . urlencode($string)
         . "&userip=" . $_SERVER['REMOTE_ADDR'];
    if(defined("BANHAMMER_GOOGLEAPIKEY") && "" != constant("BANHAMMER_GOOGLEAPIKEY"))
        $url .= "&key=" . constant("BANHAMMER_GOOGLEAPIKEY");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_REFERER, $_SERVER['SCRIPT_URI']);
    // decode JSON
    $json = json_decode(curl_exec($ch));
    curl_close($ch);
    // perform the actual Banhammer check and return the result
    return (isset($json->responseData->cursor->estimatedResultCount))
        ? (floatval($json->responseData->cursor->estimatedResultCount)
            > BANHAMMER_THRESHOLD)
        : "false";
}

?>

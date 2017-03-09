<?php
// issues
echo "Extract GitHub Issues\n";

function getJsonFor($url){
    //Initiate curl
    $ch = curl_init();
    //Set the url
    curl_setopt($ch, CURLOPT_URL,$url);
    //Set the User Agent as username
    curl_setopt($ch, CURLOPT_USERAGENT, "TIXI24");
    //Accept the response as json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Accept: application/json'));
    //Will return the response, if false it print the response
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Execute
    $result=curl_exec($ch);
    // Closing
    curl_close($ch);

    //Decode the json in associative array
    return json_decode($result);
}

$repositories = array();
$repositories[] = 'requirements';
$repositories[] = 'sfitixi';

$url = "https://api.github.com/repos/TIXI24/";

$counter = 0;
$comma = '; ';
foreach ($repositories as $repository){
    $page = 1;
    while (true) {
        $issues = getJsonFor($url.$repository."/issues?page=".$page);
        foreach ($issues as $issue){
            $lbl = "";
            foreach ($issue->labels as $label){
                $lbl .= ($lbl=="")? $label->name: ', '.$label->name;
            }
            $csv =
                $repository.$comma.
                $issue->number.$comma.
                $issue->state.$comma.
                $issue->title.$comma.
                $issue->html_url.$comma.
                $lbl."\n";
            echo $csv;
            $counter++;
        }
        $page++;
        if (count($issues)< 30){
            break;
        }
    }
}
echo "Finished, ".$counter." issues.\n";

?>
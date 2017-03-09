<?php
// issues
echo "Extract GitHub Issues\n";

/*
 * JSON output is copied to a file due to usage restrictions at GitHub
 * Use the following command on OSX (terminal) to create the files
 * curl -o ~/Projects/GitHubIssues/sfitixi.json https://api.github.com/repos/TIXI24/sfitixi/issues?state=open&per_page=1000
 * curl -o ~/Projects/GitHubIssues/requirements.json https://api.github.com/repos/TIXI24/requirements/issues?state=open&per_page=1000
 *
 * ISSUES with this code:
 * 1. /issues only returns 30 items per page
 * 2. the second page is the same as the first page
 * 3. cummulating parameters ?par1=x&par2=y is not possible (second one dropped)
 */
$repositories = array();
$repositories[] = 'requirements';
$repositories[] = 'sfitixi';

$counter = 0;
foreach ($repositories as $repository){
    $path = '/Users/mart/Projects/GitHubIssues/';
    $filename = $path.$repository.'.json';
    $handle = fopen($filename, 'r');
    $size = filesize($filename);
    $content = fread($handle, $size);
    $issues = json_decode($content);
    $comma = '; ';

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
    fclose($handle);
}
echo "Finished, ".$counter." issues.\n";
?>
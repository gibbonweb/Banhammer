<?php
require('banhammer.php');
echo "<pre>";
echo "\n";

echo 'This should be spam: banhammer("I like it! I like it a lot. You know exactly what youre talking about, exactly where other people are coming from on this issue. Im glad that I had the fortune to stumble across your blog. Its definitely an important issue that not enough people are talking about and Im glad that I got the chance to see all the angles.");';
$banned = banhammer("I like it! I like it a lot. You know exactly what youre talking about, exactly where other people are coming from on this issue. Im glad that I had the fortune to stumble across your blog. Its definitely an important issue that not enough people are talking about and Im glad that I got the chance to see all the angles.");
if($banned) echo "\nComment has been banhammered";
echo "\n\n";
echo 'This should be a valid comment (as I just wrote it myself): banhammer("Hello, thank you for this great post! I especially like the second image - the texture of the rocks is just amazing! I am looking forward to your next post!");';
$banned = banhammer("Hello, thank you for this great post! I especially like the second image - the texture of the rocks is just amazing! I am looking forward to your next post!");
if(!$banned) echo "\nComment is valid";
echo "\n\n";
echo 'This should be a valid comment (as it is too short to be identified perfectly via a Google search): banhammer("Great work, man!");';
$banned = banhammer("Great work, man!");
if(!$banned) echo "\nComment is valid";
echo "\n\n";

?>

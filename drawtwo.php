<?php
session_start();
require 'vendor/autoload.php';

$client = new \GuzzleHttp\Client();
$response = $client->request('GET', 'https://deckofcardsapi.com/api/deck/new/shuffle/?deck_count=1');

$response_json = json_decode($response->getBody(), TRUE);

$response2 = $client->request('GET', 'https://deckofcardsapi.com/api/deck/' . $response_json['deck_id'] . '/draw/?count=2');

$response_json2 = json_decode($response2->getBody(), TRUE);

$card_array = $response_json2['cards'];

$_SESSION['card_array'] = $card_array;
$_SESSION['deck_id'] = $response_json['deck_id'];

$card_total = calc_card_total($card_array);

function calc_card_total($card_array)
{
    $card_total1 = 0;
    $card_total2 = 0;

    $card_value1 = ["KING" => 10, "QUEEN" => 10, "JACK" => 10, "ACE" => 1, "2" => 2, "3" => 3, "4" => 4, "5" => 5, "6" => 6, "7" => 7, "8" => 8, "9" => 9, "10" => 10];

    $card_value2 = ["KING" => 10, "QUEEN" => 10, "JACK" => 10, "ACE" => 11, "2" => 2, "3" => 3, "4" => 4, "5" => 5, "6" => 6, "7" => 7, "8" => 8, "9" => 9, "10" => 10];

    $card_value = "";

    foreach ($card_array as $card) {
        $card_value = $card['value'];

        $card_total1 = $card_total1 + $card_value1[$card_value];
        $card_total2 += $card_value2[$card_value];
    }
    if ($card_total2 <= 21) {
        return $card_total2;
    } else {
        return $card_total1;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <?php
    foreach ($card_array as $card) : ?>

        <img src="<?= $card['image']; ?>" alt="card image">

    <?php endforeach ?>
    <h1><?php echo "Your card total is $card_total" ?></h1>

    <form action="drawagain.php" method="get">
        <?php if($card_total > 21) : ?>
            BUST!!! <br>
            <a href="index.php">Play Again</a>
        <?php elseif($card_total == 21) : ?>
            Winner Winner Chicken Dinner!
            <a href="index.php">Play Again</a>
        <?php else : ?>
            <input type="submit" value="Draw Again">
        <?php endif ?>
    </form>
</body>

</html>
<?php

$url = 'http://coding-challenge.renderedtext.com/';
$jsonContent = file_get_contents($url);
$jsonDecode = json_decode($jsonContent, true);

$typesOfMeat = [
	'cocktail_sausages',
	'salami',
	'shrimps',
	'anchovies',
	'ham',
	'minced_meat',
	'sausage',
	'kebab',
	'minced_beef',
	'mussels',
	'tuna',
	'calamari',
	'crab_meat',
];

$numberOfPizzas = 0;
foreach ($jsonDecode['pizzas'] as $pizza) {
	if ($pizza[key($pizza)] !== 'nil') {
		$numberOfPizzas++;

		$pizzaWithMeat = filterPizzasWithMeat($pizza, $typesOfMeat);
		if (!empty($pizzaWithMeat)) {
			$pizzasWithMeat[] = $pizzaWithMeat;
		}

		$pizzaWithMoreThanOneTypeOfCheese = filterPizzasWithMoreThanOneTypeOfCheese($pizza);
		if (!empty($pizzaWithMoreThanOneTypeOfCheese)) {
			$pizzasWithMoreThanOneTypeOfCheese[] = $pizzaWithMoreThanOneTypeOfCheese;
		}

		$pizzaWithMeatAndOlives = filterPizzasWithMeatAndOlives($pizza, $typesOfMeat);
		if (!empty($pizzaWithMeatAndOlives)) {
			$pizzasWithMeatAndOlives[] = $pizzaWithMeatAndOlives;
		}

		$pizzaWithMozzarellaAndMushrooms = filterPizzasWithMozzarellaAndMushrooms($pizza);
		if (!empty($pizzaWithMozzarellaAndMushrooms)) {
			$pizzasWithMozzarellaAndMushrooms[] = $pizzaWithMozzarellaAndMushrooms;
		}
	}
}

$finalJson = [
	'personal_info' => [
		'full_name' => 'Vladimir Terescenko',
		'email' => 'vterescenko@gmail.com',
		'code_link' => 'https://github.com/dredonja/coding-challenge.renderedtext.com',
	],
	'answer' => [
		'group_1' => [
			'percentage' => (100 / $numberOfPizzas) * count($pizzasWithMeat) . '%',
			'cheapest' => getCheapestPizza($pizzasWithMeat),
		],
		'group_2' => [
			'percentage' => (100 / $numberOfPizzas) * count($pizzasWithMoreThanOneTypeOfCheese) . '%',
			'cheapest' => getCheapestPizza($pizzasWithMoreThanOneTypeOfCheese),
		],
		'group_3' => [
			'percentage' => (100 / $numberOfPizzas) * count($pizzasWithMeatAndOlives) . '%',
			'cheapest' => getCheapestPizza($pizzasWithMeatAndOlives),

		],
		'group_4' => [
			'percentage' => (100 / $numberOfPizzas) * count($pizzasWithMozzarellaAndMushrooms) . '%',
			'cheapest' => getCheapestPizza($pizzasWithMozzarellaAndMushrooms),
		],
	],
];

echo json_encode($finalJson);

/**
 * Filters pizzas that have meat in ingredients
 *
 * @param array $pizza
 * @param array $typesOfMeat
 * @return array
 */
function filterPizzasWithMeat($pizza, $typesOfMeat) {
	$ingredients = $pizza[key($pizza)]['ingredients'];

	foreach ($ingredients as $ingredient) {
		if (in_array($ingredient, $typesOfMeat)) {
			return $pizza;
		}
	}
}

/**
 * Filters pizzas that have more than one type of cheese in ingredients
 *
 * @param array $pizza
 * @return array
 */
function filterPizzasWithMoreThanOneTypeOfCheese($pizza) {
	$ingredients = $pizza[key($pizza)]['ingredients'];

	$cheeses = [];
	foreach ($ingredients as $ingredient) {
		if (strpos($ingredient, 'cheese') !== false || strpos($ingredient, 'mozzarella') !== false) {
			$cheeses[] = $ingredient;
		}
	}

	if (count($cheeses) >= 2) {
		return $pizza;
	}
}

/**
 * Filters pizzas that have meat and olives in ingredients
 *
 * @param array $pizza
 * @param array $typesOfMeat
 * @return array
 */
function filterPizzasWithMeatAndOlives($pizza, $typesOfMeat) {
	$ingredients = $pizza[key($pizza)]['ingredients'];

	$olives = false;
	$meat = false;
	foreach ($ingredients as $ingredient) {
		if (strpos($ingredient, 'olives') !== false) {
			$olives = true;
		}
	}

	foreach ($ingredients as $ingredient) {
		if(in_array($ingredient, $typesOfMeat)) {
			$meat = true;
		}
	}

	if ($olives && $meat) {
		return $pizza;
	}
}

/**
 * Filters pizzas that have mozzarella and mushrooms in ingredients
 *
 * @param array $pizza
 * @return array
 */
function filterPizzasWithMozzarellaAndMushrooms($pizza) {
	$ingredients = $pizza[key($pizza)]['ingredients'];

	$mozzarella = false;
	$mushrooms = false;
	foreach ($ingredients as $ingredient) {
		if (strpos($ingredient, 'mozzarella') !== false) {
			$mozzarella = true;
		}
	}

	if (in_array('mushrooms', $ingredients)) {
		$mushrooms = true;
	}

	if ($mozzarella && $mushrooms) {
		return $pizza;
	}
}

/**
 * Returns cheapest pizza from array of given pizzas
 *
 * @param array $pizza
 * @return array
 */
function getCheapestPizza($pizzas) {
	foreach ($pizzas as $key => $pizza) {
		$prices[$key] = $pizza['price'];
	}

	$minPriceKey = array_search(min($prices), $prices);

	return $pizzas[$minPriceKey];
}

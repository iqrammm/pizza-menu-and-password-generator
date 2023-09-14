<?php
declare(strict_types=1);

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\Style\SelectableStyle;

require_once(__DIR__ . '/../vendor/autoload.php');

$wantSmallPizza = false;
$smallPizzaQuantity = 0;
$smallPizzaPeperoni = false;
$extraCheeseSmallPizza = false;

$wantMediumPizza = false;
$mediumPizzaQuantity = 0;
$mediumPizzaPeperoni = false;
$extraCheeseMediumPizza = false;

$wantLargePizza = false;
$largePizzaQuantity = 0;
$extraCheeseLargePizza = false;

function selectorInputRedraw (CliMenu $menu) {
  if ($menu->getSelectedItem()->showsItemExtra()) {
      $menu->getSelectedItem()->hideItemExtra();
  } else {
      $menu->getSelectedItem()->showItemExtra();
  }
  $menu->redraw();
};

$menu = (new CliMenuBuilder)
    ->setTitle('Pizza Order')
    ->modifySelectableStyle(function (SelectableStyle $style) {
        $style->setItemExtra('[x]');
    })
    ->addSubMenu('Small pizza', function (CliMenuBuilder $b) 
    use(&$smallPizzaQuantity, $smallPizzaPeperoni, $extraCheeseSmallPizza) {
        $b->setTitle('CLI Menu > Small pizza')
          ->addItem('Quantity', function (CliMenu $menu) use(&$smallPizzaQuantity) {
            $number = $menu->askNumber();
            $number->getStyle()
                ->setBg('180')
                ->setFg('245');
            
            $result = $number->setPlaceholderText("2")
                ->ask();
          
            $smallPizzaQuantity = (int)$result->fetch(); 
          })
          ->addItem('Include Peperoni ?', function (CliMenu $menu) use (&$smallPizzaPeperoni) {
              selectorInputRedraw($menu);
              $smallPizzaPeperoni = !$smallPizzaPeperoni;
          })
          ->addItem('Extra cheese ?', function (CliMenu $menu) use (&$extraCheeseSmallPizza) {
              selectorInputRedraw($menu);
              $extraCheeseSmallPizza = !$extraCheeseSmallPizza;
          })
          ->addLineBreak('-');
    })
    ->addSubMenu('Medium pizza', function (CliMenuBuilder $b) 
    use(&$mediumPizzaQuantity, $mediumPizzaPeperoni, $extraCheeseMediumPizza) {
        $b->setTitle('CLI Menu > Small pizza')
          ->addItem('Quantity', function (CliMenu $menu) use(&$mediumPizzaQuantity) {
            $number = $menu->askNumber();
            $number->getStyle()
                ->setBg('180')
                ->setFg('245');
            
            $result = $number->setPlaceholderText("2")
                ->ask();
          
            $mediumPizzaQuantity = (int)$result->fetch(); 
          })
          ->addItem('Include Peperoni ?', function (CliMenu $menu) use (&$mediumPizzaPeperoni) {
              selectorInputRedraw($menu);
              $mediumPizzaPeperoni = !$mediumPizzaPeperoni;
          })
          ->addItem('Extra cheese ?', function (CliMenu $menu) use (&$extraCheeseMediumPizza) {
              selectorInputRedraw($menu);
              $extraCheeseMediumPizza = !$extraCheeseMediumPizza;
          })
          ->addLineBreak('-');
    })
    ->addSubMenu('Large pizza', function (CliMenuBuilder $b) use ($largePizzaQuantity) {
        $b->setTitle('CLI Menu > Large pizza')
          ->addItem('Quantity', function (CliMenu $menu) use(&$largePizzaQuantity) {
            $number = $menu->askNumber();
            $number->getStyle()
                ->setBg('180')
                ->setFg('245');
            
            $result = $number->setPlaceholderText("2")
                ->ask();
          
            $largePizzaQuantity = (int)$result->fetch(); 
          })
          ->addItem('Extra cheese ?', function (CliMenu $menu) use (&$extraCheeseLargePizza) {
              selectorInputRedraw($menu);
              $extraCheeseLargePizza = !$extraCheeseLargePizza;
          })
          ->addLineBreak('-');
    })
    ->addItem('Checkout', function (CliMenu $menu) 
    use (
        &$smallPizzaQuantity, $smallPizzaPeperoni, $extraCheeseSmallPizza,
        &$mediumPizzaQuantity, $mediumPizzaPeperoni, $extraCheeseMediumPizza,
        &$largePizzaQuantity, $extraCheeseLargePizza
    ) {
        // Calculate the cost of each item
        $smallPizzaCost = 15 + ($smallPizzaPeperoni ? 3 : 0) + ($extraCheeseSmallPizza ? 6 : 0);
        $mediumPizzaCost = 22 + ($mediumPizzaPeperoni ? 5 : 0) + ($extraCheeseMediumPizza ? 6 : 0);
        $largePizzaCost = 30 + ($extraCheeseLargePizza ? 6 : 0);
    
        // Calculate the total cost
        $totalCost = ($smallPizzaCost * $smallPizzaQuantity) +
                     ($mediumPizzaCost * $mediumPizzaQuantity) +
                     ($largePizzaCost * $largePizzaQuantity);
    
        // Output the total cost
        $menu->close();
        echo "Your total bill is RM" . $totalCost . PHP_EOL;
    })
    ->build();

$menu->open();
<?php
declare(strict_types=1);

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\Style\SelectableStyle;

require_once(__DIR__ . '/../vendor/autoload.php');

$includeSmall = false;
$includeCapital = false;
$includeNumbers = false;
$includeSymbols = false;
$minimumLength = 10;

$selectorInputRedraw = function (CliMenu $menu) {
    if ($menu->getSelectedItem()->showsItemExtra()) {
        $menu->getSelectedItem()->hideItemExtra();
    } else {
        $menu->getSelectedItem()->showItemExtra();
    }
    $menu->redraw();
};

function generatePassword(int $minLength, array $selectedCharacterSets): string {
  $characterSets = [];
  
  $characterSets['small'] = 'abcdefghijklmnopqrstuvwxyz';
  $characterSets['capital'] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $characterSets['numbers'] = '0123456789';
  $characterSets['symbols'] = '!#$%&()*+@^';
  
  $selectedCharacters = '';
  
  foreach ($selectedCharacterSets as $setName => $isSelected) {
      if ($isSelected) {
          $selectedCharacters .= $characterSets[$setName];
      }
  }
  
  $password = '';
  
  for ($i = 0; $i < $minLength; $i++) {
      $randomChar = $selectedCharacters[random_int(0, strlen($selectedCharacters) - 1)];
      $password .= $randomChar;
  }
  
  return $password;
}

$menu = (new CliMenuBuilder)
    ->setTitle('Password Generator')
    ->addItem('Include small characters (a-z)', function (CliMenu $menu) use (&$includeSmall, $selectorInputRedraw) {
      $selectorInputRedraw($menu);
      $includeSmall = !$includeSmall;
    })
    ->addItem('Include capital letters (A-Z)', function (CliMenu $menu) use (&$includeCapital, $selectorInputRedraw) {
        $selectorInputRedraw($menu);
        $includeCapital = !$includeCapital;
    })
    ->addItem('Include numbers (0-9)', function (CliMenu $menu) use (&$includeNumbers, $selectorInputRedraw) {
        $selectorInputRedraw($menu);
        $includeNumbers = !$includeNumbers;
    })
    ->addItem('Include symbols (!#$%&()*+@^)', function (CliMenu $menu) use (&$includeSymbols, $selectorInputRedraw) {
        $selectorInputRedraw($menu);
        $includeSymbols = !$includeSymbols;
    })
    ->modifySelectableStyle(function (SelectableStyle $style) {
        $style->setItemExtra('[x]');
    })
    ->addItem('Minimum length of Password', function (CliMenu $menu) use(&$minimumLength) {
      $number = $menu->askNumber();
      $number->getStyle()
          ->setBg('180')
          ->setFg('245');
      
      $result = $number->setPlaceholderText("10")
          ->ask();
    
      $minimumLength = (int)$result->fetch(); 
    })
    ->addItem('Generate Password', function (CliMenu $menu) use (&$includeSmall, &$includeCapital, &$includeNumbers, &$includeSymbols, &$minimumLength) {
        $password = generatePassword($minimumLength, [
            'small' => $includeSmall,
            'capital' => $includeCapital,
            'numbers' => $includeNumbers,
            'symbols' => $includeSymbols,
        ]);

        echo "Generated Password: $password\n";
    })
    ->addLineBreak('-')
    ->build();

$menu->open();
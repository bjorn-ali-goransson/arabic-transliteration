<?php
  
$arabic_transliteration_constants = array(
  'end_of_string' => '$',

  // letters
  'alef' => 0x0627,
  'ba' => 0x0628,
  'ta' => 0x062A,
  'tha' => 0x062B,
  'jim' => 0x062C,
  'hha' => 0x062D,
  'kha' => 0x062E,
  'dal' => 0x062F,
  'dhal' => 0x0630,
  'ra' => 0x0631,
  'zay' => 0x0632,
  'sin' => 0x0633,
  'shin' => 0x0634,
  'sad' => 0x0635,
  'dad' => 0x0636,
  'tta' => 0x0637,
  'zza' => 0x0638,
  'ayn' => 0x0639,
  'ghayn' => 0x063A,
  'fa' => 0x0641,
  'qaf' => 0x0642,
  'kaf' => 0x0643,
  'lam' => 0x0644,
  'mim' => 0x0645,
  'nun' => 0x0646,
  'ha' => 0x0647,
  'waw' => 0x0648,
  'ya' => 0x064A,
  
  // other letters
  'alef_with_wasla' => 0x0671,
  'alef_with_sup_hamza' => 0x0623,
  'alef_with_sub_hamza' => 0x0625,
  'alef_maqsura' => 0x0649,
  'alef_with_madda' => 0x0622,
  'ta_marbuta' => 0x0629,
  'waw_with_hamza' => 0x0624,
  'ya_with_hamza' => 0x0626,
  'hamza' => 0x0621,

  // harakat
  'fatha' => 0x064E,
  'damma' => 0x064F,
  'kasra' => 0x0650,
  'sukun' => 0x0652, // (not a haraka! rather, a non-haraka!)
  
  // tanween
  'fathatan' => 0x064B,
  'dammatan' => 0x064C,
  'kasratan' => 0x064D,
	
  // other tashkil
  'shadda' => 0x0651,

  'dagger_alef' => 0x0670,
);

foreach($arabic_transliteration_constants as $name => $value){
  $arabic_transliteration_constants[$name] = arabic_transliteration_convert_to_utf8($value);
}

$arabic_transliteration_constants['sun_letters'] = 
  $arabic_transliteration_constants['ta'] .
  $arabic_transliteration_constants['tha'] .
  $arabic_transliteration_constants['dal'] .
  $arabic_transliteration_constants['dhal'] .
  $arabic_transliteration_constants['ra'] .
  $arabic_transliteration_constants['zay'] .
  $arabic_transliteration_constants['sin'] .
  $arabic_transliteration_constants['shin'] .
  $arabic_transliteration_constants['sad'] .
  $arabic_transliteration_constants['dad'] .
  $arabic_transliteration_constants['tta'] .
  $arabic_transliteration_constants['zza'] .
  $arabic_transliteration_constants['lam'] .
  $arabic_transliteration_constants['nun'];
  
$arabic_transliteration_constants['moon_letters'] = 
  $arabic_transliteration_constants['ba'] .
  $arabic_transliteration_constants['jim'] .
  $arabic_transliteration_constants['hha'] .
  $arabic_transliteration_constants['kha'] .
  $arabic_transliteration_constants['ayn'] .
  $arabic_transliteration_constants['ghayn'] .
  $arabic_transliteration_constants['fa'] .
  $arabic_transliteration_constants['qaf'] .
  $arabic_transliteration_constants['kaf'] .
  $arabic_transliteration_constants['mim'] .
  $arabic_transliteration_constants['ha'] .
  $arabic_transliteration_constants['waw'] .
  $arabic_transliteration_constants['ya'];
  
$arabic_transliteration_constants['extraneous_letters'] = 
  $arabic_transliteration_constants['alef_with_wasla'] .
  $arabic_transliteration_constants['alef_with_sup_hamza'] .
  $arabic_transliteration_constants['alef_with_sub_hamza'] .
  $arabic_transliteration_constants['alef_maqsura'] .
  $arabic_transliteration_constants['alef_with_madda'] .
  $arabic_transliteration_constants['ta_marbuta'] .
  $arabic_transliteration_constants['waw_with_hamza'] .
  $arabic_transliteration_constants['ya_with_hamza'] .
  $arabic_transliteration_constants['hamza'];
  
$arabic_transliteration_constants['standard_harakat'] = 
  $arabic_transliteration_constants['fatha'] .
  $arabic_transliteration_constants['damma'] .
  $arabic_transliteration_constants['kasra'];
  
$arabic_transliteration_constants['tanween'] = 
  $arabic_transliteration_constants['fathatan'] .
  $arabic_transliteration_constants['dammatan'] .
  $arabic_transliteration_constants['kasratan'];
  
$arabic_transliteration_constants['tashkil'] = 
  $arabic_transliteration_constants['standard_harakat'] .
  $arabic_transliteration_constants['sukun'] .
  $arabic_transliteration_constants['tanween'] .
  $arabic_transliteration_constants['shadda'];
<?php
  
require dirname(__FILE__) . '/constants.php';

function arabic_transliteration($content, $options = array()) {
  $default_options = array(
    'stop-on-sukun' => 1,
  );
  
  foreach($default_options as $key => $default_value){
    if(!array_key_exists($key, $options)){
      $options[$key] = $default_value;
    }
  }
  
  // tags
	$content = strip_tags($content);

  // remove extraneoous whitespace
	$content = arabic_transliteration_replace("\s+", " ", $content);

  $content = arabic_transliteration_transform($content, $options);
  $content = arabic_transliteration_translate($content, $options);
	
	// cleanup
	$content = preg_replace("/[\x{0590}-\x{06FF}]/u", "", $content);
	
	return $content;
}

function arabic_transliteration_translate($content, $options){
  global $arabic_transliteration_constants;
  extract($arabic_transliteration_constants);

  $translation = array(
	  // alef with fathatan
    "$fathatan$alef" => $fathatan,
    "$alef$fathatan" => $fathatan,

	  // tanween
    $fathatan => 'an',
    $kasratan => 'in',
    $dammatan => 'un',

    // consonants
    $alef => 'ā',
    $ba => 'b',
    $ta => 't',
    $tha => 'th',
    $jim => 'j',
    $hha => 'ḥ',
    $kha => 'kh',
    $dal => 'd',
    $dhal => 'dh',
    $ra => 'r',
    $zay => 'z',
    $sin => 's',
    $shin => 'sh',
    $sad => 'ṣ',
    $dad => 'ḍ',
    $tta => 'ṭ',
    $zza => 'ẓ',
    $ayn => 'ʿ',
    $ghayn => 'gh',
    $fa => 'f',
    $qaf => 'q',
    $kaf => 'k',
    $lam => 'l',
    $mim => 'm',
    $nun => 'n',
    $ha => 'h',
	
    $hamza => '-',
    $ta_marbuta => 't',

    // waw
    "$damma$waw$fatha" => "{$damma}w$fatha",
    "$damma$waw$alef" => "{$damma}w$alef",
    "$damma$waw" => "ū",
    $waw => 'w',

    // ya
    "$kasra$ya$fatha" => "{$kasra}y$fatha",
    "$kasra$ya$alef" => "{$kasra}y$alef",
    "$kasra$ya" => "ī",
    $ya => 'y',

    // vowels
    $alef => 'ā',

    // harakat
    $fatha => 'a',
    $kasra => 'i',
    $damma => 'u',
  );

  $content = str_replace(array_keys($translation), array_values($translation), $content);

  return $content;
}

function arabic_transliteration_transform($content, $options){
  global $arabic_transliteration_constants;
  extract($arabic_transliteration_constants);

  // move shadda next to letter
  $content = arabic_transliteration_replace("([$standard_harakat$sukun$tanween])($shadda)", "\\2\\1", $content);

  // one-letter words should always have its haraka transliterated
  $last_word_is_one_letter = preg_match("/(?:^| )[$sun_letters$moon_letters$extraneous_letters][$tashkil]*$end_of_string/u", $content);

	if($options['stop-on-sukun'] && !$last_word_is_one_letter){
		// tanween
		$content = arabic_transliteration_replace("$fathatan$alef$end_of_string", "$fatha$alef", $content);
		$content = arabic_transliteration_replace("$fathatan$end_of_string", "", $content);
		$content = arabic_transliteration_replace("$dammatan$end_of_string", "", $content);
		$content = arabic_transliteration_replace("$kasratan$end_of_string", "", $content);
		// harakat
		$content = arabic_transliteration_replace("$fatha$end_of_string", "", $content);
		$content = arabic_transliteration_replace("$damma$end_of_string", "", $content);
		$content = arabic_transliteration_replace("$kasra$end_of_string", "", $content);
	}
	


	/* SPECIAL CASES */
	
  // prevent lam becoming "-" if succeeded by tanween
	$content = arabic_transliteration_replace("لاً", "لْاً", $content);
	// allah (common spelling: defective tashkil)
	$content = arabic_transliteration_replace("الله", "ٱلْلَاه", $content);
	$content = arabic_transliteration_replace("اللَّه", "ٱلْلَاه", $content);
	// allah (remove -)
	$content = arabic_transliteration_replace("$alef_with_wasla$lam$lam$shadda$fatha$dagger_alef", "$alef_with_wasla$lam$sukun$lam$fatha$dagger_alef", $content);
	
	// alladhee, alladheena, etc
	$content = arabic_transliteration_replace("$alef_with_wasla$lam$lam$shadda$fatha", "$alef_with_wasla$lam$lam$fatha", $content);
	
	// ta marbutah without preceding fathah
	$content = arabic_transliteration_replace("([^$fatha])$ta_marbuta", "\$1$fatha$ta_marbuta", $content);
	
	// unmarked alif-lam with sun letter
	$content = arabic_transliteration_replace("(^| )$alef$lam([$sun_letters])", "\\1$alef_with_wasla$lam\\2", $content);
	// unmarked alif-lam with moon letter
	$content = arabic_transliteration_replace("(^| )$alef$lam([$moon_letters])", "\\1$alef_with_wasla$lam\\2", $content);
	
	// sun letters
	$content = arabic_transliteration_replace("$alef_with_wasla$lam([^$lam])$shadda", "$alef_with_wasla\$1-\$1", $content);
	$content = arabic_transliteration_replace("$alef$lam([^$lam])$shadda", "$alef_with_wasla\$1-\$1", $content);
	$content = arabic_transliteration_replace("$lam([^$lam])$shadda", "$alef_with_wasla\$1-\$1", $content);
	$content = arabic_transliteration_replace("$alef_with_wasla$lam$lam$shadda", "$alef_with_wasla$lam$lam", $content);
	// moon letters
	$content = arabic_transliteration_replace("([$alef_with_wasla$tashkil])$lam([^$tashkil$alef_maqsura])", "\$1$lam-\$2", $content);
	
	// ana
	$content = arabic_transliteration_replace("$alef_with_sup_hamza$fatha?$nun$fatha?$alef$", "أَنَ$sukun", $content);
	$content = arabic_transliteration_replace("$alef_with_sup_hamza$fatha?$nun$fatha?$alef ", "أَنَ$sukun ", $content);
	// anti
	$content = arabic_transliteration_replace("أَنْتِ$", "أَنْتِ$sukun", $content);
	


	/* SPECIAL LETTERS */

	// tatwil
	$content = arabic_transliteration_replace("ـ", "", $content);
	// dagger alif
	$content = arabic_transliteration_replace("$dagger_alef", "$alef", $content);
	// alif maqsura
	$content = arabic_transliteration_replace("$alef_maqsura", "$alef", $content);

	// hamza in beginning of words (with harakah)
	$content = arabic_transliteration_replace("^[$alef_with_sup_hamza$alef_with_sub_hamza$hamza]([$standard_harakat])", "\$1", $content);
	$content = arabic_transliteration_replace(" [$alef_with_sup_hamza$alef_with_sub_hamza$hamza]([$standard_harakat])", " \$1", $content);
	// hamza in beginning of words (without harakah)
	$content = arabic_transliteration_replace("^$alef_with_sup_hamza", "a", $content);
	$content = arabic_transliteration_replace(" $alef_with_sup_hamza", " a", $content);
	$content = arabic_transliteration_replace("^$alef_with_sub_hamza", "i", $content);
	$content = arabic_transliteration_replace(" $alef_with_sub_hamza", " i", $content);
	$content = arabic_transliteration_replace("^$hamza", "'", $content);
	$content = arabic_transliteration_replace(" $hamza", " '", $content);
	// hamza inside words
	$content = arabic_transliteration_replace("([^-])[$alef_with_sup_hamza$alef_with_sub_hamza$hamza$waw_with_hamza$ya_with_hamza]", "\$1-", $content);
	$content = arabic_transliteration_replace("[$alef_with_sup_hamza$alef_with_sub_hamza$hamza$waw_with_hamza$ya_with_hamza]", "", $content);
	
	// alif with wasla preceded with haraka
	$content = arabic_transliteration_replace("([$standard_harakat] )$alef_with_wasla", "\$1", $content);
	
	// alif with wasla preceded with long a
	$content = arabic_transliteration_replace("$fatha$alef $alef_with_wasla", "$fatha ", $content);
	// alif with wasla preceded with long u
	$content = arabic_transliteration_replace("$damma$waw $alef_with_wasla", "$damma ", $content);
	// alif with wasla preceded with long i
	$content = arabic_transliteration_replace("$kasra$ya $alef_with_wasla", "$kasra ", $content);
	
	// alif with wasla
	$content = arabic_transliteration_replace("$alef_with_wasla", "a", $content);
	// alif with madda
	$content = arabic_transliteration_replace("$alef_with_madda", "$alef", $content);

	// ta marbuta at end of word sequence
	$content = arabic_transliteration_replace("$ta_marbuta$end_of_string", "$ha", $content);
	
	// question mark
	$content = arabic_transliteration_replace("؟", "?", $content);
	


	/* SPECIAL CASES */

	// i - mi'ah
	$content = arabic_transliteration_replace("$kasra$alef", "$kasra", $content);
	
	/* Shadda */
	// vowels
	$content = arabic_transliteration_replace("$damma$waw$shadda", "$damma$waw$sukun$waw", $content);
	$content = arabic_transliteration_replace("$kasra$ya$shadda", "$kasra$ya$sukun$ya", $content);
	// regular
	$content = arabic_transliteration_replace("(.)$shadda", "\$1$sukun\$1", $content);
  
  //shadda of two-letter transliterated letters
  $content = arabic_transliteration_replace("($tha|$kha|$dhal|$shin|$ghayn)$sukun\\1", "\\1$sukun-\\1", $content);

  return $content;
}

function arabic_transliteration_replace($pattern, $replace, $subject){
  return preg_replace("/$pattern/u", $replace, $subject);
}
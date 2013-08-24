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

function arabic_transliteration_transform($content, $options){
  global $arabic_transliteration_constants;
  extract($arabic_transliteration_constants);

  // move shadda next to letter
  $content = arabic_transliteration_replace("([$standard_harakat$sukun$tanween])($shadda)", "\\2\\1", $content);

  // one-letter words should always have its haraka transliterated
  if(preg_match("/(?:^| )[$sun_letters$moon_letters$extraneous_letters][$tashkil]*$/u", $content)){
    $options['stop-on-sukun'] = 0;
  }

  // anti at end of sentence should always be written out
  if(preg_match("/(?:^| )[$alef$alef_with_sup_hamza]$fatha?$nun$sukun?$ta$kasra$/u", $content)){
    $options['stop-on-sukun'] = 0;
  }



	/* ALEF WITH WASLA */
  
  // unmarked alef with wasla indicated by sukun on next letter
  $content = arabic_transliteration_replace("(^| )($non_transforming_prefixed_particles)?$alef([$standard_letters_without_lam])$sukun([$standard_letters_without_lam][$fatha$kasra])", "\\1\\2$alef_with_wasla$kasra\\3$sukun\\4", $content);
  $content = arabic_transliteration_replace("(^| )($non_transforming_prefixed_particles)?$alef([$standard_letters_without_lam])$sukun([$standard_letters_without_lam][$damma])", "\\1\\2$alef_with_wasla$damma\\3$sukun\\4", $content);
  $content = arabic_transliteration_replace("(^| )($non_transforming_prefixed_particles)?$alef([$standard_letters])$sukun", "\\1\\2$alef_with_wasla\\3$sukun", $content);
  // TODO: fi`l amr with lam
  
  // unmarked alef with wasla indicated by no sign on next letter, shadda on 2nd next letter
  $content = arabic_transliteration_replace("$alef([$standard_letters][$standard_letters])$shadda", "$alef_with_wasla\\1$shadda", $content);

  // regular alef, lam, and regular letter not marked by any tashkil
  $content = arabic_transliteration_replace("(^| )($non_transforming_prefixed_particles)?$alef$lam([$standard_letters])", "\\1\\2$alef_with_wasla$lam-\\3", $content);

  // alef-lam in "allah" should not have dash (=> al-lāh), so remove first lam for it not to be considered as a regular alef-lam
	$content = arabic_transliteration_replace("$alef_with_wasla$lam$lam$shadda$fatha$dagger_alef?$ha", "$alef_with_wasla$lam$shadda$fatha$dagger_alef$ha", $content);

  // alladhee/allatee should have alef with wasla
	$content = arabic_transliteration_replace("(^| )($non_transforming_prefixed_particles)?$alef$lam$shadda$fatha?([$dhal$ta])$kasra?$ya", "\\1\\2$alef_with_wasla$lam$shadda$fatha\\3$kasra$ya", $content);



  /* DAGGER ALEF */

  // rahman should have alef lam
  $content = arabic_transliteration_replace("$lam$ra$shadda?$fatha?$hha$sukun?$mim$fatha?$nun", "$lam$ra$shadda$fatha$hha$sukun$mim$fatha$dagger_alef$nun", $content);
	// lillah should have dagger alef
  $content = arabic_transliteration_replace("(^| )($fa$fatha|$waw$fatha)?$lam$kasra?$lam(?:$shadda|$shadda$fatha)?$ha$kasra?($| )", "\\1\\2$lam$kasra$lam$shadda$fatha$dagger_alef$ha$kasra\\3", $content);
	// dhalika should have dagger alef
  $content = arabic_transliteration_replace("(^| )($non_transforming_prefixed_particles)?$dhal$fatha?$lam$kasra?$kaf$fatha?($| )", "\\1\\2$dhal$fatha$dagger_alef$lam$kasra$kaf$fatha\\3", $content);
	
	// dagger alef
	$content = arabic_transliteration_replace($dagger_alef, $alef, $content);



  /* SUN/MOON LETTERS */
	
	// sun letters
	$content = arabic_transliteration_replace("(^| )$alef_with_wasla$lam([$sun_letters])$shadda", "\\1$alef_with_wasla\\2-\\2", $content);
	
	// moon letters
	$content = arabic_transliteration_replace("(^| )$alef_with_wasla$lam$sukun?([$moon_letters])", "\\1$alef_with_wasla$lam-\\2", $content);

  // 
	
  /*
  // prevent lam becoming "-" if succeeded by tanween
	$content = arabic_transliteration_replace("لاً", "لْاً", $content);
	// allah (common spelling: defective tashkil)
	$content = arabic_transliteration_replace("الله", "ٱلْلَاه", $content);
	$content = arabic_transliteration_replace("اللَّه", "ٱلْلَاه", $content);
	
	// ta marbutah without preceding fathah
	$content = arabic_transliteration_replace("([^$fatha])$ta_marbuta", "\$1$fatha$ta_marbuta", $content);
	
	// ana
	$content = arabic_transliteration_replace("$alef_with_sup_hamza$fatha?$nun$fatha?$alef$", "أَنَ$sukun", $content);
	$content = arabic_transliteration_replace("$alef_with_sup_hamza$fatha?$nun$fatha?$alef ", "أَنَ$sukun ", $content);
	// anti
	$content = arabic_transliteration_replace("أَنْتِ$", "أَنْتِ$sukun", $content);
  */
	


	/* SPECIAL LETTERS */

	// tatwil
	$content = arabic_transliteration_replace("ـ", "", $content); // todo: add to constants

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
	$content = arabic_transliteration_replace("([^-])[$alef_with_sup_hamza$alef_with_sub_hamza$hamza$waw_with_hamza$ya_with_hamza]", "\$1'", $content);
	$content = arabic_transliteration_replace("[$alef_with_sup_hamza$alef_with_sub_hamza$hamza$waw_with_hamza$ya_with_hamza]", "", $content);
	
	// alif with wasla preceded by haraka
	$content = arabic_transliteration_replace("([$standard_harakat])( )?$alef_with_wasla([$standard_harakat])?", "\\1\\2", $content);
	
	// alif with wasla preceded by long a
	$content = arabic_transliteration_replace("([$standard_letters])$fatha?$alef $alef_with_wasla([$standard_harakat])?", "\\1$fatha ", $content);
	// alif with wasla preceded by long u
	$content = arabic_transliteration_replace("$damma$waw $alef_with_wasla([$standard_harakat])?", "$damma ", $content);
	// alif with wasla preceded by long i
	$content = arabic_transliteration_replace("$kasra$ya $alef_with_wasla([$standard_harakat])?", "$kasra ", $content);
	// alif with wasla preceded by sukun
	$content = arabic_transliteration_replace("([$standard_letters])$sukun $alef_with_wasla([$standard_harakat])?", "\\1$kasra ", $content);
	
	// alif with wasla
	//$content = arabic_transliteration_replace("$alef_with_wasla", "a", $content);
	// alif with madda
	$content = arabic_transliteration_replace("$alef_with_madda", "$alef", $content);

	// ta marbuta at end of word sequence
	$content = arabic_transliteration_replace("$ta_marbuta([$tashkil]*)$", "$ha\\1", $content);
	
	// question mark
	$content = arabic_transliteration_replace("؟", "?", $content);
	


	/* SPECIAL CASES */

	// i - mi'ah
	$content = arabic_transliteration_replace("$kasra$alef", "$kasra", $content);
	
	/* SHADDA */

	// vowels
	$content = arabic_transliteration_replace("$damma$waw$shadda", "$damma$waw$sukun$waw", $content);
	$content = arabic_transliteration_replace("$kasra$ya$shadda", "$kasra$ya$sukun$ya", $content);
	
  // regular
	$content = arabic_transliteration_replace("(.)$shadda", "\$1$sukun\$1", $content);
  
  //shadda of two-letter transliterated letters
  $content = arabic_transliteration_replace("($tha|$kha|$dhal|$shin|$ghayn)$sukun\\1", "\\1$sukun-\\1", $content);



  /* STOP ON SUKUN */
  
	if($options['stop-on-sukun']){
		// tanween
		$content = arabic_transliteration_replace("$fathatan$alef$", "$fatha$alef", $content);
		$content = arabic_transliteration_replace("$fathatan$", "", $content);
		$content = arabic_transliteration_replace("$dammatan$", "", $content);
		$content = arabic_transliteration_replace("$kasratan$", "", $content);
		// harakat
		$content = arabic_transliteration_replace("$fatha$", "", $content);
		$content = arabic_transliteration_replace("$damma$", "", $content);
		$content = arabic_transliteration_replace("$kasra$", "", $content);
	}

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
	
    $hamza => '\'',
    $ta_marbuta => 't',

    // waw
    "$damma$waw$fatha" => "{$damma}w$fatha",
    "$damma$waw$sukun$waw$fatha" => "{$damma}ww$fatha",
    "$damma$waw$alef" => "{$damma}w$alef",
    "$damma$waw$sukun$waw$alef" => "{$damma}ww$alef",
    "$damma$waw" => "ū",
    $waw => 'w',

    // ya
    "$kasra$ya$sukun$ya$fatha" => "{$kasra}yy$fatha",
    "$kasra$ya$fatha" => "{$kasra}y$fatha",
    "$kasra$ya$sukun$ya$alef" => "{$kasra}yy$alef",
    "$kasra$ya$alef" => "{$kasra}y$alef",
    "$kasra$ya" => "ī",
    $ya => 'y',

    // vowels
    "$fatha$alef" => 'ā',
    $alef => 'ā',
    
    "$alef_with_wasla$fatha" => 'a',
    "$alef_with_wasla$kasra" => 'i',
    "$alef_with_wasla$damma" => 'u',
    "$alef_with_wasla" => 'a',

    // harakat
    $fatha => 'a',
    $kasra => 'i',
    $damma => 'u',
  );

  $content = str_replace(array_keys($translation), array_values($translation), $content);

  return $content;
}

function arabic_transliteration_replace($pattern, $replace, $subject){
  return preg_replace("/$pattern/u", $replace, $subject);
}
<?php

function arabic_transliteration($content, $options = array()) {
  $default_options = array(
    'stop-on-sukun' => 1,
  );
  
  foreach($default_options as $key => $default_value){
    if(!array_key_exists($key, $options)){
      $options[$key] = $default_value;
    }
  }

  $end_of_string = "$";
  
  $alef = "\x{0627}";
  $ba = "\x{0628}";
  $ta = "\x{062A}";
  $tha = "\x{062B}";
  $jim = "\x{062C}";
  $hha = "\x{062D}";
  $kha = "\x{062E}";
  $dal = "\x{062F}";
  $dhal = "\x{0630}";
  $ra = "\x{0631}";
  $zay = "\x{0632}";
  $sin = "\x{0633}";
  $shin = "\x{0634}";
  $sad = "\x{0635}";
  $dad = "\x{0636}";
  $tta = "\x{0637}";
  $zza = "\x{0638}";
  $ayn = "\x{0639}";
  $ghayn = "\x{063A}";
  $fa = "\x{0641}";
  $qaf = "\x{0642}";
  $kaf = "\x{0643}";
  $lam = "\x{0644}";
  $mim = "\x{0645}";
  $nun = "\x{0646}";
  $ha = "\x{0647}";
  $waw = "\x{0648}";
  $ya = "\x{064A}";
  
  $sun_letters = "$ta$tha$dal$dhal$ra$zay$sin$shin$sad$dad$tta$zza$lam$nun";
  $moon_letters = "$ba$jim$hha$kha$ayn$ghayn$fa$qaf$kaf$mim$ha$waw$ya";
  
  $alef_with_wasla = "\x{0671}";
  $alef_with_sup_hamza = "\x{0623}";
  $alef_with_sub_hamza = "\x{0625}";
  $alef_maqsura = "\x{0649}";
  $alef_with_madda = "\x{0622}";
  $ta_marbuta = "\x{0629}";
  $waw_with_hamza = "\x{0624}";
  $ya_with_hamza = "\x{0626}";
  $hamza = "\x{0621}";

  $extraneous_letters = "$alef_with_wasla$alef_with_sup_hamza$alef_with_sub_hamza$alef_maqsura$alef_with_madda$ta_marbuta$waw_with_hamza$ya_with_hamza$hamza";

  $fatha = "\x{064E}";
  $damma = "\x{064F}";
  $kasra = "\x{0650}";
  $standard_harakat = "$fatha$damma$kasra";
  $sukun = "\x{0652}";
  
  $fathatan = "\x{064B}";
  $dammatan = "\x{064C}";
  $kasratan = "\x{064D}";
  $tanween = "$fathatan$dammatan$kasratan";
	
  $shadda = "\x{0651}";
  $tashkil = "$standard_harakat$sukun$tanween$shadda";

  $dagger_alef = "\x{0670}";



  /* TRANSFORMATION PHASE */
  
  // whitespace
	$content = strip_tags($content);

  // remove extraneoous whitespace
	$content = arabic_transliteration_replace("\s+", " ", $content);

  // move shadda next to letter
  $content = arabic_transliteration_replace("([$standard_harakat$sukun$tanween])($shadda)", "\\2\\1", $content);

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
	
	/* Special cases */
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
	
	/* Special letters */
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
	
	/* Special cases */
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



  /* TRANSLATION PHASE */

  // Harakat

  $translation = array(
	  // alef with fathatan
    "$fathatan$alef" => $fathatan,
    "$alef$fathatan" => $fathatan,

	  // tanween
    $fathatan => 'an',
    $kasratan => 'in',
    $dammatan => 'un',
  );

  $content = str_replace(array_keys($translation), array_values($translation), $content);
	
	// long/short u
	$content = arabic_transliteration_replace("$damma$waw([^$fatha$alef])", "ū\$1", $content);
	$content = arabic_transliteration_replace("$damma", "u", $content);
	// long/short i
	$content = arabic_transliteration_replace("$kasra$ya([^$fatha$alef])", "ī\$1", $content);
	$content = arabic_transliteration_replace("$kasra", "i", $content);
	// long/short a
	$content = arabic_transliteration_replace("$fatha$alef", "ā", $content);
	$content = arabic_transliteration_replace("$fatha", "a", $content);
	
	/* Letters */
	$content = arabic_transliteration_replace($alef, "ā", $content);
	$content = arabic_transliteration_replace($ba, "b", $content);
	$content = arabic_transliteration_replace($ta, "t", $content);
	$content = arabic_transliteration_replace($tha, "th", $content);
	$content = arabic_transliteration_replace($jim, "j", $content);
	$content = arabic_transliteration_replace($hha, "ḥ", $content);
	$content = arabic_transliteration_replace($kha, "kh", $content);
	$content = arabic_transliteration_replace($dal, "d", $content);
	$content = arabic_transliteration_replace($dhal, "dh", $content);
	$content = arabic_transliteration_replace($ra, "r", $content);
	$content = arabic_transliteration_replace($zay, "z", $content);
	$content = arabic_transliteration_replace($sin, "s", $content);
	$content = arabic_transliteration_replace($shin, "sh", $content);
	$content = arabic_transliteration_replace($sad, "ṣ", $content);
	$content = arabic_transliteration_replace($dad, "ḍ", $content);
	$content = arabic_transliteration_replace($tta, "ṭ", $content);
	$content = arabic_transliteration_replace($zza, "ẓ", $content);
	$content = arabic_transliteration_replace($ayn, "ʿ", $content);
	$content = arabic_transliteration_replace($ghayn, "gh", $content);
	$content = arabic_transliteration_replace($fa, "f", $content);
	$content = arabic_transliteration_replace($qaf, "q", $content);
	$content = arabic_transliteration_replace($kaf, "k", $content);
	$content = arabic_transliteration_replace($lam, "l", $content);
	$content = arabic_transliteration_replace($mim, "m", $content);
	$content = arabic_transliteration_replace($nun, "n", $content);
	$content = arabic_transliteration_replace($ha, "h", $content);
	$content = arabic_transliteration_replace($waw, "w", $content);
	$content = arabic_transliteration_replace($ya, "y", $content);
	
	$content = arabic_transliteration_replace($hamza, "-", $content);
	$content = arabic_transliteration_replace($ta_marbuta, "t", $content);
	
	/* Cleanup */
	
	$content = preg_replace("/[\x{0590}-\x{06FF}]/u", "", $content);
	
	return $content;
}

function arabic_transliteration_convert_to_utf8($unicode , $encoding = 'UTF-8'){
  return mb_convert_encoding("&#{$unicode};", $encoding, 'HTML-ENTITIES');
}

function arabic_transliteration_replace($pattern, $replace, $subject){
  //if(strpos($replace, "\x") !== FALSE){
    $replace = preg_replace_callback("/\\\\x\{([0-9A-F]{4})\}/", function($matches){
      return arabic_transliteration_convert_to_utf8(hexdec($matches[1]));
    }, $replace);
  //}
  
  return preg_replace("/$pattern/u", $replace, $subject);
}
<?php

function transliterate_to_arabic($content, $options = array()) {
  $default_options = array(
    'stop-on-sukun' => 1,
  );
  
  foreach($default_options as $key => $default_value){
    if(!array_key_exists($key, $options)){
      $options[$key] = $default_value;
    }
  }
  
	$content = preg_replace("/<[^>]+>/u", "", $content);
	$content = preg_replace("/\s+/u", " ", $content);

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

  $dagger_alef = "\x{0670}";

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

  // move shadda next to letter
  $content = preg_replace("/([$standard_harakat$sukun$tanween])($shadda)/u", "\\2\\1", $content);
  
	if($options['stop-on-sukun']){
		// tanween
		$content = preg_replace("/$fathatan$alef$end_of_string/u", "$fatha$alef", $content);
		$content = preg_replace("/$fathatan$end_of_string/u", "", $content);
		$content = preg_replace("/$dammatan$end_of_string/u", "", $content);
		$content = preg_replace("/$kasratan$end_of_string/u", "", $content);
		// harakat
		$content = preg_replace("/$fatha$end_of_string/u", "", $content);
		$content = preg_replace("/$damma$end_of_string/u", "", $content);
		//$content = preg_replace("/لِ$/u", "لْ", $content); // one-char words should never stop on sukun...
		$content = preg_replace("/$kasra$end_of_string/u", "", $content);
	}
	
	/* Special cases */
	// prevent lam becoming "-" if succeeded by tanween
	$content = preg_replace("/لاً/u", "لْاً", $content);
	// allah (common spelling: defective tashkil)
	$content = preg_replace("/الله/u", "ٱلْلَاه", $content);
	$content = preg_replace("/اللَّه/u", "ٱلْلَاه", $content);
	// allah (remove -)
	$content = preg_replace("/$alef_with_wasla$lam$lam$shadda$fatha$dagger_alef/u", "$alef_with_wasla$lam$sukun$lam$fatha$dagger_alef", $content);
	
	// alladhee, alladheena, etc
	$content = preg_replace("/$alef_with_wasla$lam$lam$shadda$fatha/u", "$alef_with_wasla$lam$lam$fatha", $content);
	
	// ta marbutah without preceding fathah
	$content = preg_replace("/([^$fatha])$ta_marbuta/u", "\$1$fatha$ta_marbuta", $content);
	
	// unmarked alif-lam with sun letter
	$content = preg_replace("/ $alef$lam([$sun_letters])/u", " $alef_with_wasla$lam\$1", $content);
	// unmarked alif-lam with moon letter
	$content = preg_replace("/ $alef$lam([$moon_letters])/u", " $alef_with_wasla$lam\$1", $content);
	
	// sun letters
	$content = preg_replace("/$alef_with_wasla$lam([^$lam])$shadda/u", "$alef_with_wasla\$1-\$1", $content);
	$content = preg_replace("/$alef$lam([^$lam])$shadda/u", "$alef_with_wasla\$1-\$1", $content);
	$content = preg_replace("/$lam([^$lam])$shadda/u", "$alef_with_wasla\$1-\$1", $content);
	$content = preg_replace("/$alef_with_wasla$lam$lam$shadda/u", "$alef_with_wasla$lam$lam", $content);
	// moon letters
	$content = preg_replace("/([$alef_with_wasla$tashkil])$lam([^$tashkil$alef_maqsura])/u", "\$1$lam-\$2", $content);
	
	// ana
	$content = preg_replace("/$alef_with_sup_hamza$fatha?$nun$fatha?$alef$/u", "أَنَ$sukun", $content);
	$content = preg_replace("/$alef_with_sup_hamza$fatha?$nun$fatha?$alef /u", "أَنَ$sukun ", $content);
	// anti
	$content = preg_replace("/أَنْتِ$/u", "أَنْتِ$sukun", $content);
	
	/* Special letters */
	// tatwil
	$content = preg_replace("/ـ/u", "", $content);
	// dagger alif
	$content = preg_replace("/$dagger_alef/u", "$alef", $content);
	// alif maqsura
	$content = preg_replace("/$alef_maqsura/u", "$alef", $content);
	// hamza in beginning of words (with harakah)
	$content = preg_replace("/^[$alef_with_sup_hamza$alef_with_sub_hamza$hamza]([$standard_harakat])/u", "\$1", $content);
	$content = preg_replace("/ [$alef_with_sup_hamza$alef_with_sub_hamza$hamza]([$standard_harakat])/u", " \$1", $content);
	// hamza in beginning of words (without harakah)
	$content = preg_replace("/^[$alef_with_sup_hamza]/u", "a", $content);
	$content = preg_replace("/^[$alef_with_sub_hamza]/u", "i", $content);
	$content = preg_replace("/^[$hamza]/u", "'", $content);
	$content = preg_replace("/ [$alef_with_sup_hamza]/u", " a", $content);
	$content = preg_replace("/ [$alef_with_sub_hamza]/u", " i", $content);
	$content = preg_replace("/ [$hamza]/u", " '", $content);
	// hamza inside words
	$content = preg_replace("/([^-])[$alef_with_sup_hamza$alef_with_sub_hamza$hamza$waw_with_hamza$ya_with_hamza]/u", "\$1-", $content);
	$content = preg_replace("/[$alef_with_sup_hamza$alef_with_sub_hamza$hamza$waw_with_hamza$ya_with_hamza]/u", "", $content);
	
	// alif with wasla preceded with haraka
	$content = preg_replace("/([$standard_harakat] )$alef_with_wasla/u", "\$1", $content);
	
	// alif with wasla preceded with long a
	$content = preg_replace("/$fatha$alef $alef_with_wasla/u", "a ", $content);
	// alif with wasla preceded with long u
	$content = preg_replace("/$damma$waw $alef_with_wasla/u", "u ", $content);
	// alif with wasla preceded with long i
	$content = preg_replace("/$kasra$ya $alef_with_wasla/u", "i ", $content);
	
	// alif with wasla
	$content = preg_replace("/$alef_with_wasla/u", "a", $content);
	// alif with madda
	$content = preg_replace("/$alef_with_madda/u", "$alef", $content);
	
	// question mark
	$content = preg_replace("/؟/u", "?", $content);
	
	/* Special cases */
	// i - mi'ah
	$content = preg_replace("/$kasra$alef/u", "$kasra", $content);
	
	/* Shadda */
	// vowels
	$content = preg_replace("/$damma$waw$shadda/u", "$damma$waw$sukun$waw", $content);
	$content = preg_replace("/$kasra$ya$shadda/u", "$kasra$ya$sukun$ya", $content);
	// regular
	$content = preg_replace("/(.)$shadda/u", "\$1$sukun\$1", $content);
	
	/* Harakat */
	// alef with fathatan
	$content = preg_replace("/(?:$fathatan$alef|$alef$fathatan)/u", "$fathatan", $content);
	// tanween
	$content = preg_replace("/$fathatan/u", "an", $content);
	$content = preg_replace("/$dammatan/u", "un", $content);
	$content = preg_replace("/$kasratan/u", "in", $content);
	// long/short u
	$content = preg_replace("/$damma$waw([^$fatha$alef])/u", "ū\$1", $content);
	$content = preg_replace("/$damma/u", "u", $content);
	// long/short i
	$content = preg_replace("/$kasra$ya([^$fatha$alef])/u", "ī\$1", $content);
	$content = preg_replace("/$kasra/u", "i", $content);
	// long/short a
	$content = preg_replace("/$fatha$alef/u", "ā", $content);
	$content = preg_replace("/$fatha/u", "a", $content);
  
  //shadda of two-letter transliterated letters
  $content = preg_replace("/($tha|$kha|$dhal|$shin|$ghayn)$sukun//1/u", "\\1$sukun-\\1", $content);
	
	/* Letters */
	$content = preg_replace("/$alef/u", "ā", $content);
	$content = preg_replace("/$ba/u", "b", $content);
	$content = preg_replace("/$ta/u", "t", $content);
	$content = preg_replace("/$tha/u", "th", $content);
	$content = preg_replace("/$jim/u", "j", $content);
	$content = preg_replace("/$hha/u", "ḥ", $content);
	$content = preg_replace("/$kha/u", "kh", $content);
	$content = preg_replace("/$dal/u", "d", $content);
	$content = preg_replace("/$dhal/u", "dh", $content);
	$content = preg_replace("/$ra/u", "r", $content);
	$content = preg_replace("/$zay/u", "z", $content);
	$content = preg_replace("/$sin/u", "s", $content);
	$content = preg_replace("/$shin/u", "sh", $content);
	$content = preg_replace("/$sad/u", "ṣ", $content);
	$content = preg_replace("/$dad/u", "ḍ", $content);
	$content = preg_replace("/$tta/u", "ṭ", $content);
	$content = preg_replace("/$zza/u", "ẓ", $content);
	$content = preg_replace("/$ayn/u", "ʿ", $content);
	$content = preg_replace("/$ghayn/u", "gh", $content);
	$content = preg_replace("/$fa/u", "f", $content);
	$content = preg_replace("/$qaf/u", "q", $content);
	$content = preg_replace("/$kaf/u", "k", $content);
	$content = preg_replace("/$lam/u", "l", $content);
	$content = preg_replace("/$mim/u", "m", $content);
	$content = preg_replace("/$nun/u", "n", $content);
	$content = preg_replace("/$ha/u", "h", $content);
	$content = preg_replace("/$waw/u", "w", $content);
	$content = preg_replace("/$ya/u", "y", $content);
	
	$content = preg_replace("/$hamza/u", "-", $content);
	// ta marbuta at end of word sequence
	$content = preg_replace("/$ta_marbuta$end_of_string/u", "h", $content);
	$content = preg_replace("/$ta_marbuta/u", "t", $content);
	
	/* Cleanup */
	
	$content = preg_replace("/[\x{0590}-\x{06ff}]/u", "", $content);
	
	return $content;
}
Arabic Transliteration in PHP
==========================

This function takes an arabic string like نَعْبُدُ and returns a latinized transliteration like /naʿbud/ (somewhat resembling the ALA-LC standard).

Usage: require arabic_transliteration.php and call:

```
$options = array('stop-on-sukun' => 1);
$str = arabic_transliteration('بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيمِ', $options);
// $str is now 'bismi llāhi r-raḥmāni r-raḥīm'
```

To wrap all arabic text in ```&lt;span&gt;```s having the transliterated content as tooltip, use the following code (hint: 0600-06ff is the arabic unicode codepage):

```
$str = preg_replace_callback("/([\x{0600}-\x{06ff}]([\s,.]+[\x{0600}-\x{06ff}]+)*)+/u", function($matches){
  return "<span class=\"arabic\" title=\"" . transliterate_to_arabic($matches[1], array('stop-on-sukun' => 1)) . "\">{$matches[0]}</span>";
}, $content);
```

These tests currently pass:

```
'بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيمِ' => 'bismi llāhi r-raḥmāni r-raḥīm',
'الْحَمْدُ لِلَّهِ رَبِّ الْعَالَمِينَ' => 'al-ḥamdu lillāhi rabbi l-ʿālamīn',
'الرَّحْمَنِ الرَّحِيمِ' => 'ar-raḥmāni r-raḥīm',
'مَالِكِ يَوْمِ الدِّينِ' => 'māliki yawmi d-dīn',
'إِيَّاكَ نَعْبُدُ وَإِيَّاكَ نَسْتَعِينُ' => 'iyyāka naʿbudu wa\'iyyāka nastaʿīn',
'اهْدِنَا الصِّرَاطَ الْمُسْتَقِيمَ' => 'ihdina ṣ-ṣirāṭa l-mustaqīm',
'صِرَاطَ الَّذِينَ أَنْعَمْتَ عَلَيْهِمْ غَيْرِ الْمَغْضُوبِ عَلَيْهِمْ وَلا الضَّالِّينَ' => 'ṣirāṭa lladhīna anʿamta ʿalayhim ghayri l-maghḍūbi ʿalayhim wala ḍ-ḍāllīn',
//
'إِذَا رَأَيْتَنِي قَدْ فَتَحْتُ فَمِي بِالحَرْفِ فَانْقُطْ نُقْطَةً فَوْقَهُ عَلَى أَعْلَاهُ' => 'idhā ra\'aytanī qad fataḥtu famī bil-ḥarfi fanquṭ nuqṭatan fawqahu ʿalā aʿlāh',
'فَإِنْ ضَمَمْتُ فَمِي فَانْقُطْ نُقْطَةً بَيْنَ يَدَيِ ٱلحَرْفِ' => 'fa\'in ḍamamtu famī fanquṭ nuqṭatan bayna yadayi l-ḥarf',
'وَإِنْ كَسَرْتُ فَاجْعَلْ ٱلنُّقْطَةَ تَحْتَ ٱلحَرْفِ' => 'wa\'in kasartu fajʿali n-nuqṭata taḥta l-ḥarf',
'فَإِنْ أَتْبَعْتُ شَيْئاً مِنْ ذَلِكَ غُنَّةً فَاجْعَلْ مَكَانَ ٱلنُّقْطَةِ نُقْطَتَيْنِ' => 'fa\'in atbaʿtu shay\'an min dhālika ghunnatan fajʿal makāna n-nuqṭati nuqṭatayn',
```

Todo
----

* More tests
* Pattern recognition to know when alef with wasla and lam are part of a verb, and thus should have kasra or damma (as opposed to fatha when alef lam is the definitive article)
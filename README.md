Arabic Transliteration in PHP
==========================

This function takes an arabic string like نَعْبُدُ and returns a latinized transliteration like /naʿbud/ (somewhat resembling the ALA-LC standard).

Usage: include transliteration.php and call:

```
$options = array('stop-on-sukun' => 1);
$str = arabic_transliteration('بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيمِ', $options);
// $str is now 'bismi llāhi r-raḥmāni r-raḥīm'
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
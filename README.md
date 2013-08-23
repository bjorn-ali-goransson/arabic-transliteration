PHP-arabic-transliteration
==========================

This function takes an arabic string like نَعْبُدُ and returns a latinized transliteration like 'naʿbud' (somewhat resembling the ALA-LC standard).

Usage: include transliteration.php and call:

```
$options = array('stop-on-sukun' => 1);
arabic_transliteration('بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيمِ الْحَمْدُ لِلَّهِ رَبِّ الْعَالَمِينَ الرَّحْمَنِ الرَّحِيمِ مَالِكِ يَوْمِ الدِّينِ إِيَّاكَ نَعْبُدُ وَإِيَّاكَ نَسْتَعِينُ اهْدِنَا الصِّرَاطَ الْمُسْتَقِيمَ صِرَاطَ الَّذِينَ أَنْعَمْتَ عَلَيْهِمْ غَيْرِ الْمَغْضُوبِ عَلَيْهِمْ وَلا الضَّالِّينَ', $options);
```

These tests currently pass:

```
// al-fatiha
'بِسْمِ' => 'bism',
'اللَّهِ' => 'allāh',
'الرَّحْمَنِ' => 'ar-raḥmān',
'الرَّحِيمِ' => 'ar-raḥīm',
'الْحَمْدُ' => 'al-ḥamd',
'لِلَّهِ' => 'lillāh',
'رَبِّ' => 'rabb',
'الْعَالَمِينَ' => 'al-ʿālamīn',
'الرَّحْمَنِ' => 'ar-raḥmān',
'الرَّحِيمِ' => 'ar-raḥīm',
'مَالِكِ' => 'mālik',
'يَوْمِ' => 'yawm',
'الدِّينِ' => 'ad-dīn',
'إِيَّاكَ' => 'iyyāk',
'نَعْبُدُ' => 'naʿbud',
'وَإِيَّاكَ' => 'wa\'iyyāk',
'نَسْتَعِينُ' => 'nastaʿīn',
'اهْدِنَا' => 'ihdinā',
'الصِّرَاطَ' => 'aṣ-ṣirāṭ',
'الْمُسْتَقِيمَ' => 'al-mustaqīm',
'صِرَاطَ' => 'ṣirāṭ',
'الَّذِينَ' => 'alladhīn',
'أَنْعَمْتَ' => 'anʿamt',
'عَلَيْهِمْ' => 'ʿalayhim',
'غَيْرِ' => 'ghayr',
'الْمَغْضُوبِ' => 'al-maghḍūb',
'عَلَيْهِمْ' => 'ʿalayhim',
'وَلا' => 'walā',
'الضَّالِّينَ' => 'aḍ-ḍāllīn',

// some special words
'اللُّغَةُ' => 'al-lughah',
'اللَّه' => 'allāh',
'اقْرَأْ' => 'iqra\'',

      
'بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيمِ' => 'bismi llāhi r-raḥmāni r-raḥīm',
'الْحَمْدُ لِلَّهِ رَبِّ الْعَالَمِينَ' => 'al-ḥamdu lillāhi rabbi l-ʿālamīn',
'الرَّحْمَنِ الرَّحِيمِ' => 'ar-raḥmāni r-raḥīm',
'مَالِكِ يَوْمِ الدِّينِ' => 'māliki yawmi d-dīn',
'إِيَّاكَ نَعْبُدُ وَإِيَّاكَ نَسْتَعِينُ' => 'iyyāka naʿbudu wa\'iyyāka nastaʿīn',
'اهْدِنَا الصِّرَاطَ الْمُسْتَقِيمَ' => 'ihdina ṣ-ṣirāṭa l-mustaqīm',
'صِرَاطَ الَّذِينَ أَنْعَمْتَ عَلَيْهِمْ غَيْرِ الْمَغْضُوبِ عَلَيْهِمْ وَلا الضَّالِّينَ' => 'ṣirāṭa lladhīna anʿamta ʿalayhim ghayri l-maghḍūbi ʿalayhim wala ḍ-ḍāllīn',

'إِذَا رَأَيْتَنِي قَدْ فَتَحْتُ فَمِي بِالحَرْفِ فَانْقُطْ نُقْطَةً فَوْقَهُ عَلَى أَعْلَاهُ' => 'idhā ra\'aytanī qad fataḥtu famī bil-ḥarfi fanquṭ nuqṭatan fawqahu ʿalā aʿlāh',
'فَإِنْ ضَمَمْتُ فَمِي فَانْقُطْ نُقْطَةً بَيْنَ يَدَيِ ٱلحَرْفِ' => 'fa\'in ḍamamtu famī fanquṭ nuqṭatan bayna yadayi l-ḥarf',
'وَإِنْ كَسَرْتُ فَاجْعَلْ ٱلنُّقْطَةَ تَحْتَ ٱلحَرْفِ' => 'wa\'in kasartu fajʿali n-nuqṭata taḥta l-ḥarf',
'فَإِنْ أَتْبَعْتُ شَيْئاً مِنْ ذَلِكَ غُنَّةً فَاجْعَلْ مَكَانَ ٱلنُّقْطَةِ نُقْطَتَيْنِ' => 'fa\'in atbaʿtu shay\'an min dhālika ghunnatan fajʿal makāna n-nuqṭati nuqṭatayn',
```
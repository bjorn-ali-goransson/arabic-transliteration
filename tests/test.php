<?php
require dirname(__FILE__) . '/simpletest/autorun.php';
require '../transliteration.php';

SimpleTest::prefer(new HtmlReporter('utf-8'));

class TestOfLogging extends UnitTestCase {
  function testAlphabetTranslationWithoutHarakat() {
    $test = array(
      'ا' => 'ā',
      'ب' => 'b',
      'ت' => 't',
      'ث' => 'th',
      'ج' => 'j',
      'ح' => 'ḥ',
      'خ' => 'kh',
      'د' => 'd',
      'ذ' => 'dh',
      'ر' => 'r',
      'ز' => 'z',
      'س' => 's',
      'ش' => 'sh',
      'ص' => 'ṣ',
      'ض' => 'ḍ',
      'ط' => 'ṭ',
      'ظ' => 'ẓ',
      'ع' => 'ʿ',
      'غ' => 'gh',
      'ف' => 'f',
      'ق' => 'q',
      'ك' => 'k',
      'ل' => 'l',
      'م' => 'm',
      'ن' => 'n',
      'ه' => 'h',
      'و' => 'w',
      'ي' => 'y',
    );
    foreach($test as $arabic => $transliterated){
      $this->assertEqual(arabic_transliteration($arabic), $transliterated);
    }
  }
  function testAlphabetTranslationWithHarakat() {
    $test = array(
      'بَ' => 'ba',
      'تُ' => 'tu',
      'ثِ' => 'thi',
      'جَ' => 'ja',
      'حُ' => 'ḥu',
      'خِ' => 'khi',
      'دَ' => 'da',
      'ذُ' => 'dhu',
      'رِ' => 'ri',
      'زَ' => 'za',
      'سُ' => 'su',
      'شِ' => 'shi',
      'صَ' => 'ṣa',
      'ضُ' => 'ḍu',
      'طِ' => 'ṭi',
      'ظَ' => 'ẓa',
      'عُ' => 'ʿu',
      'غِ' => 'ghi',
      'فَ' => 'fa',
      'قُ' => 'qu',
      'كِ' => 'ki',
      'لَ' => 'la',
      'مُ' => 'mu',
      'نِ' => 'ni',
      'هَ' => 'ha',
      'وُ' => 'wu',
      'يِ' => 'yi',
    );
    foreach($test as $arabic => $transliterated){
      $this->assertEqual(arabic_transliteration($arabic), $transliterated);
    }
  }
  function testWordsStopOnSukun() {
    $test = array(
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
    );
    foreach($test as $arabic => $transliterated){
      $this->assertEqual(arabic_transliteration($arabic), $transliterated);
    }
  }
  function testAntiWithStopOnSukun() {
    $test = array(
      'أَنْتِ' => 'anti',
      'مَنْ أَنْتِ' => 'man anti',
    );
    foreach($test as $arabic => $transliterated){
      $this->assertEqual(arabic_transliteration($arabic), $transliterated);
    }
  }
  function testAlladheeWithPrefixedParticles() {
    $ba = 'بِ';
    $kaf = 'كَ';
    $ta = 'تَ';
    $waw = 'وَ';
    $fa = 'فَ';

    $test = array(
      /* ALLADHEE */

      // without waw or fa
      'الَّذِي' => 'alladhī',
      $ba . 'الَّذِي' => 'billadhī',
      $kaf . 'الَّذِي' => 'kalladhī',
      $ta . 'الَّذِي' => 'talladhī',
      
      // with waw
      $waw . 'الَّذِي' => 'walladhī',
      $waw . $ba . 'الَّذِي' => 'wabilladhī',
      $waw . $kaf . 'الَّذِي' => 'wakalladhī',
      $waw . $ta . 'الَّذِي' => 'watalladhī',

      // with fa
      $fa . 'الَّذِي' => 'falladhī',
      $fa . $ba . 'الَّذِي' => 'fabilladhī',
      $fa . $kaf . 'الَّذِي' => 'fakalladhī',
      $fa . $ta . 'الَّذِي' => 'fatalladhī',



      /* ALLATEE */

      // without waw or fa
      'الَّتِي' => 'allatī',
      $ba . 'الَّتِي' => 'billatī',
      $kaf . 'الَّتِي' => 'kallatī',
      $ta . 'الَّتِي' => 'tallatī',
      
      // with waw
      $waw . 'الَّتِي' => 'wallatī',
      $waw . $ba . 'الَّتِي' => 'wabillatī',
      $waw . $kaf . 'الَّتِي' => 'wakallatī',
      $waw . $ta . 'الَّتِي' => 'watallatī',

      // with fa
      $fa . 'الَّتِي' => 'fallatī',
      $fa . $ba . 'الَّتِي' => 'fabillatī',
      $fa . $kaf . 'الَّتِي' => 'fakallatī',
      $fa . $ta . 'الَّتِي' => 'fatallatī',
    );
    foreach($test as $arabic => $transliterated){
      $this->assertEqual(arabic_transliteration($arabic), $transliterated);
    }
  }
  function testSentencesStopOnSukun() {
    $test = array(
      'إِذَا رَأَيْتَنِي قَدْ فَتَحْتُ فَمِي بِالحَرْفِ فَانْقُطْ نُقْطَةً فَوْقَهُ عَلَى أَعْلَاهُ' => 'idhā ra\'aytanī qad fatahtu famī bil-harf fanquṭ nuqṭatan fawqahu ʿalā aʿlāh',
      'فَإِنْ ضَمَمْتُ فَمِي فَانْقُطْ نُقْطَةً بَيْنَ يَدَيِ ٱلحَرْفِ' => 'fa\'in ḍamamtu famī fanquṭ nuqṭatan bayna yadayi l-harf',
      'وَإِنْ كَسَرْتُ فَاجْعَلْ ٱلنُّقْطَةَ تَحْتَ ٱلحَرْفِ' => 'wa\'in kasartu fajʿali n-nuqṭata tahta l-harf',
      'فَإِنْ أَتْبَعْتُ شَيْئاً مِنْ ذَلِكَ غُنَّةً فَاجْعَلْ مَكَانَ ٱلنُّقْطَةِ نُقْطَتَيْنِ' => 'fa\'in atbaʿtu shay\'an min dhālika ghunnatan fajʿal makāna n-nuqṭati nuqṭatayn',
    );
    foreach($test as $arabic => $transliterated){
      $this->assertEqual(arabic_transliteration($arabic), $transliterated);
    }
  }
}

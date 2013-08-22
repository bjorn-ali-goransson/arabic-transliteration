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
      'بِسْمِ' => 'bism',
      'اللَّهِ' => 'allāh',
      'الرَّحْمَنِ' => 'ar-raḥmān',
      'الرَّحِيمِ' => 'ar-raḥīm',
      'الْحَمْدُ' => 'al-ḥamd',
      'لِلَّهِ' => 'allāh',
      'رَبِّ' => 'rabb',
      'الْعَالَمِينَ' => 'al-`ālamīn',
      'الرَّحْمَنِ' => 'ar-raḥmān',
      'الرَّحِيمِ' => 'ar-raḥīm',
      'مَالِكِ' => 'mālik',
      'يَوْمِ' => 'yawm',
      'الدِّينِ' => 'ad-dīn',
      'إِيَّاكَ' => 'iyyāk',
      'نَعْبُدُ' => 'na`bud',
      'وَإِيَّاكَ' => 'wa\'iyyāk',
      'نَسْتَعِينُ' => 'nasta`īn',
      'اهْدِنَا' => 'ihdinā',
      'الصِّرَاطَ' => 'aṣ-ṣirāṭ',
      'الْمُسْتَقِيمَ' => 'al-mustaqīm',
      'صِرَاطَ' => 'ṣirāṭ',
      'الَّذِينَ' => 'alladhīn',
      'أَنْعَمْتَ' => 'an`amt',
      'عَلَيْهِمْ' => '`alayhim',
      'غَيْرِ' => 'ghayr',
      'الْمَغْضُوبِ' => 'al-maghḑub',
      'عَلَيْهِمْ' => '`alayhim',
      'وَلا' => 'walā',
      'الضَّالِّينَ' => 'aḑ-ḑāllīn',
    );
    foreach($test as $arabic => $transliterated){
      $this->assertEqual(arabic_transliteration($arabic), $transliterated);
    }
  }
}

<?php
/**
* Batch process to import slideshow data from the old MediaWiki databases.
*/

require_once(dirname(__FILE__).'/../bootstrap/Doctrine.php');

class ChannelSettingsImportBatch extends DoctrineBatch
{
   protected function process()
   {
      $this->logNotice("Importing channel settings");

      $channels = Doctrine_Manager::connection()->fetchAssoc("
         select c.id, oc.GA_Code, oc.adspot_160x600, oc.adspot_300x250, oc.adspot2_300x250, oc.adspot3_300x250
         from `OLD_ltk_master`.channels oc
         join channel c on c.slug = replace(replace(oc.description, 'http://', ''), '.lovetoknow.com', '')");

      foreach($channels as $c)
      {
         $this->importChannel($c['id'], $c['GA_Code'], $c['adspot_160x600'], $c['adspot_300x250'], $c['adspot2_300x250'], $c['adspot3_300x250']);
      }
   }

   private function importChannel($id, $ga, $sidebar, $top, $middle, $bottom)
   {
      $this->logNotice("Importing channel '$id'");

      $channel = Doctrine::getTable('Channel')->find($id);

      $settings = $channel->get('settings');

      $settings['analytics_code'] = $ga;

      $settings['ad_sidebar'] = $sidebar;
      $settings['ad_1'] = $top;
      $settings['ad_2'] = $bottom;
      $settings['ad_3'] = $middle;
      $settings['ad_slide'] = '471969';
      $settings['footer_text'] = $channel->slug.' home';

      switch($channel->slug)
      {
         case 'business': $settings['ad_chitika'] = '854249'; break;
         case 'crafts': $settings['ad_chitika'] = '755235'; break;
         case 'diet': $settings['ad_chitika'] = '854240'; break;
         case 'dogs': $settings['ad_chitika'] = '854250'; break;
         case 'hair': $settings['ad_chitika'] = '755234'; break;
         case 'horoscopes': $settings['ad_chitika'] = '854256'; break;
         case 'interiordesign': $settings['ad_chitika'] = '854252'; break;
         case 'kids': $settings['ad_chitika'] = '854242'; break;
         case 'makeup': $settings['ad_chitika'] = '854246'; break;
         case 'swimsuits': $settings['ad_chitika'] = '854244'; break;
         case 'weddings': $settings['ad_chitika'] = '755226'; break;
         case 'recipes': $settings['ad_chitika'] = '755221'; break;
         case 'pregnancy': $settings['ad_chitika'] = '755228'; break;
         case 'tattoos': $settings['ad_chitika'] = '755231'; break;
         default: $settings['ad_chitika'] = '';
      }

      switch($channel->slug)
      {
         case 'business': $settings['footer_text'] = 'Free Business Advice'; break;
         case 'crafts': $settings['footer_text'] = 'Craft Patterns and Projects'; break;
         case 'diet': $settings['footer_text'] = 'Free Online Diet Plans'; break;
         case 'dogs': $settings['footer_text'] = 'Type of Dogs and Dog Training'; break;
         case 'hair': $settings['footer_text'] = 'Hair Styles and Hair Cuts'; break;
         case 'horoscopes': $settings['footer_text'] = 'Love horoscopes'; break;
         case 'interiordesign': $settings['footer_text'] = 'Home Interior Design Ideas'; break;
         case 'kids': $settings['footer_text'] = 'Kids Products'; break;
         case 'makeup': $settings['footer_text'] = 'Makeup Beauty Tips'; break;
         case 'swimsuits': $settings['footer_text'] = 'Swimwear and Bathing Suits'; break;
         case 'weddings': $settings['footer_text'] = 'Wedding Ideas'; break;
         case 'recipes': $settings['footer_text'] = 'Free Online Recipes'; break;
         case 'pregnancy': $settings['footer_text'] = 'Pregnancy Signs and Symptoms'; break;
         case 'tattoos': $settings['footer_text'] = 'Tattoo Designs and Galleries'; break;
         default: $settings['footer_text'] = '';
      }

      $settings['ad_article_type'] = 'text_image';
      $settings['ad_article_size'] = '468x60';
      switch($channel->slug)
      {
         case 'engagementrings': $settings['ad_article_type'] = 'image'; break;
         case 'hair': $settings['ad_article_type'] = 'image'; $settings['ad_article_size'] = '300x250'; break;
      }

      switch($channel->slug)
      {
         case 'addiction': $settings['ad_article_1'] = '4359200789'; break;
         case 'appliances': $settings['ad_article_1'] = '141'; break;
         case 'antiques': $settings['ad_article_1'] = '7256158016'; break;
         case 'autism': $settings['ad_article_1'] = '8694919951'; break;
         case 'baby': $settings['ad_article_1'] = '0265338199'; break;
         case 'best': $settings['ad_article_1'] = '4846089625'; break;
         case 'boardgames': $settings['ad_article_1'] = '2332166554'; break;
         case 'business': $settings['ad_article_1'] = '2997679608'; break;
         case 'buy': $settings['ad_article_1'] = '4246709875'; break;
         case 'camping': $settings['ad_article_1'] = '3494852411'; break;
         case 'cake-decorating': $settings['ad_article_1'] = '111'; break;
         case 'candles': $settings['ad_article_1'] = '7725612434'; break;
         case 'cats': $settings['ad_article_1'] = '9018208949'; break;
         case 'cars': $settings['ad_article_1'] = '9232291637'; break;
         case 'celebrity': $settings['ad_article_1'] = '8410795192'; break;
         case 'cellphones': $settings['ad_article_1'] = '5602993486'; break;
         case 'charity': $settings['ad_article_1'] = '0690992442'; break;
         case 'cheerleading': $settings['ad_article_1'] = '4270762930'; break;
         case 'childrens-clothing': $settings['ad_article_1'] = '1066294665'; break;
         case 'childrens-books': $settings['ad_article_1'] = '100'; break;
         case 'bedding': $settings['ad_article_1'] = '9505360311'; break;
         case 'christmas': $settings['ad_article_1'] = '0383802092'; break;
         case 'cleaning': $settings['ad_article_1'] = '4301838971'; break;
         case 'cocktails': $settings['ad_article_1'] = '137'; break;
         case 'college': $settings['ad_article_1'] = '6413503822'; break;
         case 'costumes': $settings['ad_article_1'] = '5697827866'; break;
         case 'crafts': $settings['ad_article_1'] = '6890772804'; break;
         case 'creditcards': $settings['ad_article_1'] = '1458280963'; break;
         case 'cruises': $settings['ad_article_1'] = '9559074655'; break;
         case 'dance': $settings['ad_article_1'] = '8113644957'; break;
         case 'dating': $settings['ad_article_1'] = '5930847020'; break;
         case 'dying': $settings['ad_article_1'] = '2113212287'; break;
         case 'diet': $settings['ad_article_1'] = '5383738139'; break;
         case 'divorce': $settings['ad_article_1'] = '9464840956'; break;
         case 'dogs': $settings['ad_article_1'] = '0083576876'; break;
         case 'engagementrings': $settings['ad_article_1'] = '8127690058'; break;
         case 'exercise': $settings['ad_article_1'] = '4072050858'; break;
         case 'feng-shui': $settings['ad_article_1'] = '3963764429'; break;
         case 'freelance-writing': $settings['ad_article_1'] = '3895784979'; break;
         case 'french': $settings['ad_article_1'] = '7796743520'; break;
         case 'furniture': $settings['ad_article_1'] = '9436185385'; break;
         case 'garden': $settings['ad_article_1'] = '6109469177'; break;
         case 'genealogy': $settings['ad_article_1'] = '115'; break;
         case 'gourmet': $settings['ad_article_1'] = '1308353507'; break;
         case 'greenliving': $settings['ad_article_1'] = '7837120882'; break;
         case 'guitar': $settings['ad_article_1'] = '3366586054'; break;
         case 'hair': $settings['ad_article_1'] = '5579038386'; break;
         case 'handbags': $settings['ad_article_1'] = '2181515320'; break;
         case 'herbs': $settings['ad_article_1'] = '7876206455'; break;
         case 'homeimprovement': $settings['ad_article_1'] = '0244912839'; break;
         case 'home-school': $settings['ad_article_1'] = '7292035832'; break;
         case 'horoscopes': $settings['ad_article_1'] = '3985255379'; break;
         case 'insurance': $settings['ad_article_1'] = '9617301042'; break;
         case 'interiordesign': $settings['ad_article_1'] = '8666053078'; break;
         case 'jewelry': $settings['ad_article_1'] = '9971686920'; break;
         case 'jobs': $settings['ad_article_1'] = '1487322990'; break;
         case 'kids': $settings['ad_article_1'] = '3677023502'; break;
         case 'lingerie': $settings['ad_article_1'] = '6765090324'; break;
         case 'makeup': $settings['ad_article_1'] = '8640666935'; break;
         case 'mens-fashion': $settings['ad_article_1'] = '2462822152'; break;
         case 'mortgage': $settings['ad_article_1'] = '9743584669'; break;
         case 'movies': $settings['ad_article_1'] = '5912376749'; break;
         case 'music': $settings['ad_article_1'] = '6226889582'; break;
         case 'online': $settings['ad_article_1'] = '3590773351'; break;
         case 'organic': $settings['ad_article_1'] = '3238894538'; break;
         case 'origami': $settings['ad_article_1'] = '3238894538'; break;
         case 'paranormal': $settings['ad_article_1'] = '0969906407'; break;
         case 'party': $settings['ad_article_1'] = '3788624728'; break;
         case 'photography': $settings['ad_article_1'] = '7851587246'; break;
         case 'plussize': $settings['ad_article_1'] = '6418132664'; break;
         case 'pregnancy': $settings['ad_article_1'] = '5899123054'; break;
         case 'quiz': $settings['ad_article_1'] = '1888984985'; break;
         case 'reality-tv': $settings['ad_article_1'] = '0579749955'; break;
         case 'recipes': $settings['ad_article_1'] = '13'; break;
         case 'safety': $settings['ad_article_1'] = '5547844313'; break;
         case 'sanfrancisco': $settings['ad_article_1'] = '0765326516'; break;
         case 'save': $settings['ad_article_1'] = '4242366471'; break;
         case 'sci-fi': $settings['ad_article_1'] = '4635503593'; break;
         case 'scrapbooking': $settings['ad_article_1'] = '7241587023'; break;
         case 'seniors': $settings['ad_article_1'] = '4413234158'; break;
         case 'shoes': $settings['ad_article_1'] = '6117393891'; break;
         case 'ski': $settings['ad_article_1'] = '3440534809'; break;
         case 'skincare': $settings['ad_article_1'] = '0894551147'; break;
         case 'sleep': $settings['ad_article_1'] = '1347514891'; break;
         case 'soap-operas': $settings['ad_article_1'] = '4935580659'; break;
         case 'socialnetworking': $settings['ad_article_1'] = '2549338408'; break;
         case 'stress': $settings['ad_article_1'] = '1073982754'; break;
         case 'sunglasses': $settings['ad_article_1'] = '5228220345'; break;
         case 'swimsuits': $settings['ad_article_1'] = '6719733560'; break;
         case 'tattoos': $settings['ad_article_1'] = '1629131423'; break;
         case 'teens': $settings['ad_article_1'] = '7107618423'; break;
         case 'themeparks': $settings['ad_article_1'] = '3087110602'; break;
         case 'toys': $settings['ad_article_1'] = '109'; break;
         case 'travel': $settings['ad_article_1'] = '4047213485'; break;
         case 'vegetarian': $settings['ad_article_1'] = '4381859375'; break;
         case 'videogames': $settings['ad_article_1'] = '2897507487'; break;
         case 'vitamins': $settings['ad_article_1'] = '1035493004'; break;
         case 'watches': $settings['ad_article_1'] = '2862118454'; break;
         case 'web-design': $settings['ad_article_1'] = '7231000721'; break;
         case 'weddings': $settings['ad_article_1'] = '7646166926'; break;
         case 'wine': $settings['ad_article_1'] = '5886251170'; break;
         case 'womens-fashion': $settings['ad_article_1'] = '1992456824'; break;
         case 'yoga': $settings['ad_article_1'] = '9224742440'; break;
         case 'uniforms': $settings['ad_article_1'] = '105'; break;
      }

      switch($channel->slug)
      {
         case 'addiction': $settings['ad_article_2'] = '6160789012'; break;
         case 'appliances': $settings['ad_article_2'] = '142'; break;
         case 'antiques': $settings['ad_article_2'] = '7499662213'; break;
         case 'autism': $settings['ad_article_2'] = '8899872708'; break;
         case 'boardgames': $settings['ad_article_2'] = '8496119332'; break;
         case 'buy': $settings['ad_article_2'] = '9040889820'; break;
         case 'cake-decorating': $settings['ad_article_2'] = '112'; break;
         case 'camping': $settings['ad_article_2'] = '6681202618'; break;
         case 'candles': $settings['ad_article_2'] = '4817027322'; break;
         case 'cars': $settings['ad_article_2'] = '1876456352'; break;
         case 'cats': $settings['ad_article_2'] = '9473809143'; break;
         case 'celebrity': $settings['ad_article_2'] = '1918459119'; break;
         case 'cellphones': $settings['ad_article_2'] = '1139687789'; break;
         case 'charity': $settings['ad_article_2'] = '6557672896'; break;
         case 'cheerleading': $settings['ad_article_2'] = '1524583409'; break;
         case 'childrens-clothing': $settings['ad_article_2'] = '124'; break;
         case 'childrens-books': $settings['ad_article_2'] = '4856095220'; break;
         case 'bedding': $settings['ad_article_2'] = '7151910803'; break;
         case 'christmas': $settings['ad_article_2'] = '0829826105'; break;
         case 'cleaning': $settings['ad_article_2'] = '6560801013'; break;
         case 'cocktails': $settings['ad_article_2'] = '138'; break;
         case 'college': $settings['ad_article_2'] = '2223767847'; break;
         case 'costumes': $settings['ad_article_2'] = '6486257361'; break;
         case 'crafts': $settings['ad_article_2'] = '6588391260'; break;
         case 'cruises': $settings['ad_article_2'] = '8724173677'; break;
         case 'dance': $settings['ad_article_2'] = '0709478315'; break;
         case 'dying': $settings['ad_article_2'] = '1114989142'; break;
         case 'divorce': $settings['ad_article_2'] = '6308389091'; break;
         case 'dogs': $settings['ad_article_2'] = '0179506510'; break;
         case 'engagementrings': $settings['ad_article_2'] = '6935138453'; break;
         case 'exercise': $settings['ad_article_2'] = '0232278452'; break;
         case 'feng-shui': $settings['ad_article_2'] = '1064824894'; break;
         case 'freelance-writing': $settings['ad_article_2'] = '0195290352'; break;
         case 'french': $settings['ad_article_2'] = '2030708414'; break;
         case 'furniture': $settings['ad_article_2'] = '3462701133'; break;
         case 'genealogy': $settings['ad_article_2'] = '116'; break;
         case 'gourmet': $settings['ad_article_2'] = '5472303376'; break;
         case 'greenliving': $settings['ad_article_2'] = '0207076124'; break;
         case 'guitar': $settings['ad_article_2'] = '4423874197'; break;
         case 'hair': $settings['ad_article_2'] = '4688516530'; break;
         case 'handbags': $settings['ad_article_2'] = '7363752427'; break;
         case 'herbs': $settings['ad_article_2'] = '7229388396'; break;
         case 'home-school': $settings['ad_article_2'] = '7872896179'; break;
         case 'jewelry': $settings['ad_article_2'] = '8603721256'; break;
         case 'jobs': $settings['ad_article_2'] = '6005418014'; break;
         case 'lingerie': $settings['ad_article_2'] = '8586002736'; break;
         case 'mens-fashion': $settings['ad_article_2'] = '3299497719'; break;
         case 'movies': $settings['ad_article_2'] = '5002371002'; break;
         case 'music': $settings['ad_article_2'] = '7836577704'; break;
         case 'online': $settings['ad_article_2'] = '1810203430'; break;
         case 'organic': $settings['ad_article_2'] = '1282141629'; break;
         case 'origami': $settings['ad_article_2'] = '4617135867'; break;
         case 'paranormal': $settings['ad_article_2'] = '5940368660'; break;
         case 'party': $settings['ad_article_2'] = '0458771134'; break;
         case 'photography': $settings['ad_article_2'] = '5134725303'; break;
         case 'pregnancy': $settings['ad_article_2'] = '8895888456'; break;
         case 'quiz': $settings['ad_article_2'] = '5906004142'; break;
         case 'reality-tv': $settings['ad_article_2'] = '0052022080'; break;
         case 'recipes': $settings['ad_article_2'] = '133'; break;
         case 'safety': $settings['ad_article_2'] = '6539703904'; break;
         case 'sanfrancisco': $settings['ad_article_2'] = '3779969233'; break;
         case 'save': $settings['ad_article_2'] = '6399513056'; break;
         case 'sci-fi': $settings['ad_article_2'] = '0967144179'; break;
         case 'scrapbooking': $settings['ad_article_2'] = '7251068654'; break;
         case 'seniors': $settings['ad_article_2'] = '6323482192'; break;
         case 'shoes': $settings['ad_article_2'] = '1948000358'; break;
         case 'ski': $settings['ad_article_2'] = '0743313292'; break;
         case 'sleep': $settings['ad_article_2'] = '8004220537'; break;
         case 'soap-operas': $settings['ad_article_2'] = '0368849853'; break;
         case 'socialnetworking': $settings['ad_article_2'] = '3682394229'; break;
         case 'stress': $settings['ad_article_2'] = '3032698131'; break;
         case 'sunglasses': $settings['ad_article_2'] = '4720384146'; break;
         case 'tattoos': $settings['ad_article_2'] = '6965955644'; break;
         case 'themeparks': $settings['ad_article_2'] = '7299095381'; break;
         case 'toys': $settings['ad_article_2'] = '7425757795'; break;
         case 'travel': $settings['ad_article_2'] = '3884112309'; break;
         case 'vegetarian': $settings['ad_article_2'] = '3547522444'; break;
         case 'videogames': $settings['ad_article_2'] = '3442768267'; break;
         case 'vitamins': $settings['ad_article_2'] = '5995908618'; break;
         case 'watches': $settings['ad_article_2'] = '4880483118'; break;
         case 'web-design': $settings['ad_article_2'] = '9443820743'; break;
         case 'weddings': $settings['ad_article_2'] = '3628998572'; break;
         case 'wine': $settings['ad_article_2'] = '0478611621'; break;
         case 'womens-fashion': $settings['ad_article_2'] = '2112855978'; break;
         case 'yoga': $settings['ad_article_2'] = '1527339065'; break;
         case 'business': $settings['ad_article_2'] = '1443390891'; break;
         case 'insurance': $settings['ad_article_2'] = '0969469777'; break;
         case 'mortgage': $settings['ad_article_2'] = '1286104984'; break;
         case 'plussize': $settings['ad_article_2'] = '5319055690'; break;
         case 'creditcards': $settings['ad_article_2'] = '5701352098'; break;
         case 'makeup': $settings['ad_article_2'] = '2022322893'; break;
         case 'diet': $settings['ad_article_2'] = '8661626005'; break;
         case 'skincare': $settings['ad_article_2'] = '5684658855'; break;
         case 'kids': $settings['ad_article_2'] = '6618334527'; break;
         case 'baby': $settings['ad_article_2'] = '6582955083'; break;
         case 'dating': $settings['ad_article_2'] = '2278660534'; break;
         case 'teens': $settings['ad_article_2'] = '0961071366'; break;
         case 'horoscopes': $settings['ad_article_2'] = '0718978398'; break;
         case 'interiordesign': $settings['ad_article_2'] = '3158935833'; break;
         case 'homeimprovement': $settings['ad_article_2'] = '4916323528'; break;
         case 'garden': $settings['ad_article_2'] = '2326248528'; break;
         case 'swimsuits': $settings['ad_article_2'] = '9226947619'; break;
         case 'uniforms': $settings['ad_article_2'] = '106'; break;
      }

      $channel->settings = $settings;

      $channel->save();

      $channel->free();
   }
}

ChannelSettingsImportBatch::run();

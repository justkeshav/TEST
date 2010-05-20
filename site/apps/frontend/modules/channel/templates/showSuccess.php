<h1><?php echo $channel->title ?></h1>
<div class="mainContent">
    <div class='NewestAdditionImage'>
        <img src="/images/<?php echo $channel->slug ?>.jpg" />
    </div>
    <div class='NewestAddition' style='width:560px;'>
    <h3>What's New</h3>
        <div id='leftNewAddition'>
        <?php
        $i = 1;
        foreach($latestArticles as $latestArticle)
        {
            if($i == 4)
            {
                ?>
                </div><div id='rightNewAddition'>
                <?php
            }
            ?>
                    <div><?php echo link_to_content($latestArticle) ?><br />
                    in <?php echo link_to_absolute('categoryUrl', 'categoryTitle',$latestArticle) ?></div>
            <?php
            $i++;
        }
        ?>
        </div>
    </div>
</div>
<div class="clear">
        <div id='articleHighlight'><h2>Article Highlight</h2>
            <div>                
                <img src="<?php echo $channel->Details->Highlight->Image->genUrl('highlight') ?>" /></div>
        <div>            
            <?php echo link_to_content($channel->Details->Highlight)?>
        </div>
        <div><?php echo $channel->Details->getRaw('highlight_content')?></div></div>
        <div id='articleHighlight'>
            <h2>Popular Article</h2>
            <div class="clear">
                <img src="<?php echo $channel->Details->Popular1->Image->getThumbUrl() ?>" />
                <div><?php echo link_to_content($channel->Details->Popular1) ?></div>
                <div><?php echo $channel->Details->getRaw('popular1_content')?></div>
            </div>
            <div class="clear">
                <div><img src="<?php echo $channel->Details->Popular2->Image->getThumbUrl() ?>" />
                <?php echo link_to_content($channel->Details->Popular2) ?></div>
                <div><?php echo $channel->Details->getRaw('popular2_content') ?>
                </div>
            </div>
        </div>
</div>
<div class="clear"><?php echo $channel->getRaw('content')?></div>
<div class="clear">
    <div id="slideshow">
        <table>
            <tr><td colspan='3' id="bold"><?php echo $channel->title ?> Slideshows</td><tr>
            <?php
            $i = 1;
            foreach($slideShows as $slideShow)
            {
               if($i == 4)
               {
                   ?>
                   </tr><tr>
                   <?php
               }
               ?>
               <td>
               <?php
               echo link_to_content($slideShow, $slideShow->title, array('absolute' => true, 'rel' => 'slideshow'));
               $i++;
               ?>
               </td>
               <?php
            }
            ?>
            </tr>
        </table>
    </div>    
    <div id='categoryArticle'>
        <?php
        $category = "";
        $categoryIndex = 0;        
        $sFeatureArticleBackUp = "";
        $sFeaturePosition = "";
        foreach($categoryArticles as $article)
        {
            $sFeatureArticle = "";            
            $categoryIndex++;            
            if($category != $article->categoryTitle)
            {
                $category = $article->categoryTitle;                              
                foreach($featureArticles as $featureArticle)
                {                    
                    if ($article->category_id == $featureArticle->category_id)
                    {
                        $sFeatureArticle = link_to_content($featureArticle, '<img src="'.$featureArticle->Title->Image->getThumbUrl().'" /><br />');
                        $sFeatureArticle .= link_to_content($featureArticle, $featureArticle->title);
                        $sFeaturePosition = $featureArticle->position;
                        break;
                    }
                }                
                switch($sFeaturePosition)
                {
                    case "Above":
                        echo $sFeatureArticle;
                        break;
                    case "Below":
                        if($categoryIndex > 1)
                        {
                            echo $sFeatureArticleBackUp;
                            $sFeatureArticleBackUp = "";
                        }
                        $sFeatureArticleBackUp = $sFeatureArticle ;
                        break;
                    case "Right": ?>
                        <div class="floatRight"><?php echo $sFeatureArticle ?></div><?php
                        break;
                }

                ?>               
                <p><?php echo link_to_absolute('categoryUrl','categoryTitle',$article) ?></p>
                <?php
            }
            echo link_to_content($article,$article->title) ?><br />
            <?php
        }
        ?>
    </div>    
</div>

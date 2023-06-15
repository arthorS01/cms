
        <div id="history_container">           
            <div id="controlls">
    
                    <input type="text" name="search"  placeholder="Search">
                
                <button id="clear_btn">clear</button>
            </div>
            <div id="history_area">
               
                    <?php foreach($param as $history):?>

                        <?php 
                            $meta_data = JSON_decode($history["meta_data"],true);
                        ?>
                        <div class="record">
                            <div class="headline">
                                <span class="attribute"><?=$history["subject"]?></span>
                                <span class="attribute"><?=date("Y/M/D",(int)($history["date"]))?></span>
                                <span class="attribute"><span>see details</span><button><i class="show_detail_btn icofont-arrow-down"></i></button></span>
                            
                            </div>
                            <div class="history_detail_area">

                                
                                <?php foreach($meta_data as $data=>$value):?>
                                    <?php

                                    if($data=="response"){
                                        continue;
                                    }
                                    ?>
                                <div class="data_entry">
                                    <div class="data"><?=$data." : "?></div><div class="entry"><?=$value?></div>
                                </div>

                                <?php endforeach;?>
                            </div>
                        </div>
                        <?php endforeach;?>
                   
            </div>
        </div>
  
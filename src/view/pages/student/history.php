
        <div id="history_container">           
            <div id="controlls">
    
                    <div id="search_bar">
                        <input type="text" name="search"  placeholder="Search" id="search_bar_value"><button id="close_search_btn"> X </button>
                        <button id="clear_btn">clear</button>
                        <div id="search_results">

                        </div>
                    </div>   
               
            </div>

           <?php if($param==null){ ?>
            <p class="no_case">No activites recorded</p>
           
            <?php }else{ ?>
            <div id="history_area">
                <table id="history_table">
                    <thead>
                        <th>&nbsp;</th>
                        <th>Activity</th>
                        <th>Date</th>
                    </thead>
                    <tbody>
                    <?php if($param!==null){
                     foreach($param as $history){?>
                        <tr>
                        <td>&nbsp;</td>
                        <td class="activity"><?=$history["body"]?></td>
                        <td class='date'><?=date("Y/M/D",(int)($history["date"]))?></td>
                  
                        </tr>
                        <?php
                    } };?>
                    </tbody>
                   
                </table>

               
            </div>
            <?php }?>

           
        </div>
  
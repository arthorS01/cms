    <?php 
    
    $html = "";


    if(!$param){
        $html = "<div class='not_found'> <img src='assets/images/no-results.png' height='100px' width='100px'  alt='no result found'></div>";
    }else{
        $html=""; 
        foreach($param as $row){

           $row="<tr><td>".$row["body"]."</td>"."<td>".$row["date"]."</td></tr>";
           $html.=$row;
        }

        $html = "<table><thead><tr><th>Activity</th><th>Date</th></tr></thead><tbody>".$html."</tbody></table>";
    }


    

    echo $html;
    ?>


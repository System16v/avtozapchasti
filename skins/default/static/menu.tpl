<ul>
    <?php if(!isset($_GET['key1'])){
        echo '<li class="first"><a href="/static/main/Каталог товаров" class="ktl">Каталог товаров</a></li>';
    }else{
        if(isset($_GET['key1']) && $_GET['key1'] == 'Каталог товаров'){
            echo '<li class="first"><a href="/static/main/Каталог товаров" class="current">Каталог товаров</a></li>';
        } else{
            echo '<li class="first"><a href="/static/main/Каталог товаров" class="unc">Каталог товаров</a></li>';
        }
    }
    ?>
    <?php
    while($m=$menu->fetch_assoc()) {
        ?>
        <?php if ($m['name'] == 'Электрика') {
            if(isset($_GET['key1']) && $_GET['key1'] == $m['name']){
                echo '<li class="end"><a href="/static/main/'.$m['name'].'" class="current">'.$m['name'].'</a></li>';
            } else{
                echo '<li class="end"><a href="/static/main/'.$m['name'].'">'.$m['name'].'</a></li>';
            }
        } else {
            if(isset($_GET['key1']) && $_GET['key1'] == $m['name']){
                echo '<li><a href="/static/main/'.$m['name'].'" class="current">'.$m['name'].'</a></li>';
            } else{
                echo '<li><a href="/static/main/'.$m['name'].'">'.$m['name'].'</a></li>';
            }
          }
    }
?>
</ul>
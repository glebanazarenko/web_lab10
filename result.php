<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Анализ текста</title>
</head>

<body>
    <main>
        <?php
        if( function_exists('iconv') ){
            echo'dg;bknter<br>';
        }


        if($_POST['comments'] != ''){
            echo'<textarea style="color:red; font-style:italic">'.$_POST['comments'].'</textarea><br>';
            test_it(iconv("utf-8", "cp1251",$_POST['comments'])  );
        }
        else{
            echo'<p>Нет текста для анализа<p><br>';
        }
        ?>
        <a href="index.html">"Другой анализ"</a>
    </main>
</body>

</html>

<?php
function test_it( $text ) { 
    echo strlen($text);

    // количество символов в тексте определяется функцией размера текста
    echo 'Количество символов: '.strlen($text).'<br>'; 
    preg_match_all( '/[a-zа-яё]/ui', $text, $matches);
    echo 'Количество букв: '.count($matches[0]).'<br>';


    preg_match_all("/[A-ZА-ЯЁ]/u", $text, $str);
    echo 'Количество заглавных букв: '.count($str[0]).'<br>';

    echo 'Количество прописных букв: '.count($matches[0]) - count($str[0]).'<br>';

    $str = preg_replace("/[^[:punct:]]/", '', $text);
    echo 'Количество знаков препинания: '.strlen($str).'<br>';


    // определяем ассоциированный массив с цифрами
    $cifra=array( '0'=>true, '1'=>true, '2'=>true, '3'=>true, '4'=>true, 
                  '5'=>true, '6'=>true, '7'=>true, '8'=>true, '9'=>true ); 
    // вводим переменные для хранения информации о: 
    $cifra_amount=0; // количество цифр в тексте
    $word_amount=0; // количество слов в тексте
    $word=''; // текущее слово
    $words=array(); // список всех слов
    for($i=0; $i<strlen($text); $i++) // перебираем все символы текста
    { 
        if( array_key_exists($text[iconv("cp1251", "utf-8", $i)], $cifra) ) // если встретилась цифра
            $cifra_amount++; // увеличиваем счетчик цифр
            // если в тексте встретился пробел или текст закончился
        if( $text[$i]==' ' || $i==strlen($text)-1 ) {
            if($i==strlen($text)-1){
                $word.=$text[iconv("cp1251", "utf-8", $i)];
            }
            if( $word ) // если есть текущее слово
            {
                // если текущее слово сохранено в списке слов
                if( isset($words[iconv("cp1251", "utf-8", $word)]) ) 
                    $words[iconv("cp1251", "utf-8", $word)]++; // увеличиваем число его повторов
                else 
                    $words[iconv("cp1251", "utf-8", $word)]=1; // первый повтор слова
            } 
            $word=''; // сбрасываем текущее слово
        } 
        else // если слово продолжается
            $word.=$text[iconv("cp1251", "utf-8", $i)]; //добавляем в текущее слово новый символ
                
    } 
    // выводим количество цифр в тексте
    echo 'Количество цифр: '.$cifra_amount.'<br>'; 
    
    $symbs = test_symbs( $text ) ;
    ksort($symbs);
    foreach ($symbs as $key => $value) {
        /*
        $value[$key] = iconv("utf-8", "cp1251",$value[$key]);
        echo iconv("cp1251", "utf-8", $value[0]);
        */
        echo 'Количество символа "'.$key.'" = '.$value.'<br>';
    }

    ksort($words);
    // выводим количество слов в тексте
    echo 'Количество слов: '.count($words).'<br>';  
    foreach($words as $key => $value) {
        echo 'Количество слов "'.$key.'" = '.$value.'<br>';
    }
} 

function test_symbs( $text ) 
{ 
    $symbs=array(); // массив символов текста
    //$l_text=strtolower($text);
    
    $l_text=strtolower($text); // переводим текст в нижний регистр

    // последовательно перебираем все символы текста
    for($i=0; $i<strlen($l_text); $i++) { 
        if( isset($symbs[iconv("cp1251", "utf-8", $l_text[$i])]) ){ // если символ есть в массиве 
            $symbs[iconv("cp1251", "utf-8", $l_text[$i])]++; // увеличиваем счетчик повторов
            
        }
        else // иначе
            $symbs[iconv("cp1251", "utf-8", $l_text[$i])]=1; // добавляем символ в массив
    }
    return  $symbs;// возвращаем массив с числом вхождений символов в тексте
} 
?>
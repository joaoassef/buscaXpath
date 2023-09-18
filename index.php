<?php

    //SETA HORÁRIO DE SÃO PAULO
    date_default_timezone_set('America/Sao_Paulo');

    //CASO QUEIRA QUE FIQUE REPETINDO A OPERAÇÃO DESCOMENTAR AS LINHAS ABAIXO (RELOAD DE 5 minutos a 15 rondomicos)
    //$tempo = rand(300,900);
    //echo "<meta HTTP-EQUIV='refresh' CONTENT='{$tempo}'>";

    $dom = new domDocument();

    //EXEMPLO DE BUSCA, UTILIZANDO O ÍNDICE IBOVESPA
    $url = "https://www.infomoney.com.br/cotacoes/b3/indice/ibovespa/";
    $content = file_get_contents($url);
    @$dom->loadHTML($content);
    $xpath = new DOMXpath($dom);

    //TITULO
    $titulo = $xpath->query("/html/body/div[4]/div/div[1]/div[1]/div/div[1]/h1");

    //PONTOS
    $pontos = $xpath->query("/html/body/div[4]/div/div[1]/div[1]/div/div[3]/div[1]/p");

    //VARIAÇÃO DO DIA
    $variacaoDoDia = $xpath->query("/html/body/div[4]/div/div[1]/div[1]/div/div[3]/div[2]/p");

    //NOME DO ARQUIVO
    $arquivo = 'dados.txt';

    //TEXTO QUE SERÁ GRAVADO
    $texto = date("d-m-Y H:i:s") ." - ". trim($titulo->item(0)->textContent) . " --> " . trim($pontos->item(0)->textContent) . " [".trim($variacaoDoDia->item(0)->textContent). "]\n";

    //VERIFICANCO SE O ARQUIVO EXISTE
    if (!file_exists($arquivo)) {
        //CRIA O ARQUIVO CASO ELE NÃO EXISTIR
        $handle = fopen($arquivo, 'w');
        if ($handle === false) {
            die('Não foi possível criar o arquivo.');
        }
        fclose($handle);
    }

    //ABRE O ARQUIVO PARA ESCRITA
    $handle = fopen($arquivo, 'a');

    //VERIFICA SE O ARQUIVO FOI ABERTO
    if ($handle === false) {
        die('Não foi possível abrir o arquivo.');
    }

    //ESCREVE O TEXTO NO ARQUIVO ABERTO
    if (fwrite($handle, $texto) === false) {
        die('Não foi possível escrever no arquivo.');
    }

    //FECHA O ARQUIVO
    fclose($handle);

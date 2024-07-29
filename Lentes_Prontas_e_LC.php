<?php

// Cabeçalho obrigatório
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=UTF-8");

// Incluindo conexão
include_once 'conexao.php';

// QUERY

$id_marca = filter_input(INPUT_GET, 'id_marca');
$marca = filter_input(INPUT_GET, 'marca');
$marca_variant = filter_input(INPUT_GET, 'marca_variant');

$read = $conn->prepare("SELECT b.ID AS lente_id,b.MARCA AS MARCA, a.MARCA_VARIANT AS MARCA_VARIANT, a.ID_MARCA AS ID, a.PRODUTO AS PRODUTO, a.PRECO_PAR AS PRECO_PAR, a.ESFERICO AS ESFERICO,
a.CILINDRICO AS CILINDRICO,a.DURACAO AS DURACAO,a.BLUE_UV AS BLUE_UV,a.DISPONIBILIDADE AS DISPONIBILIDADE,a.DISPONIBILIDADE_DOIS AS DISPONIBILIDADE_DOIS,
a.COM_AR AS COM_AR,a.ADICAO AS ADICAO,a.ALTURA AS ALTURA,a.IR AS IR,a.CIL AS CIL,a.DIAM AS DIAM,a.VISODROP AS VISODROP,a.CRIZAL_PREVENCIA AS CRIZAL_PREVENCIA,
a.CRIZAL_SAPPHIRE AS CRIZAL_SAPPHIRE,a.CRIZAL_ROCK AS CRIZAL_ROCK,a.CRIZAL_EASY_PRO AS CRIZAL_EASY_PRO,a.OPTIFOG AS OPTIFOG,a.TRIO_EASY_CLEAN AS TRIO_EASY_CLEAN,
a.SEM_AR AS SEM_AR,a.NO_REFLEX AS NO_REFLEX,a.COLORACAO_SIMPLES AS COLORACAO_SIMPLES,a.COLORACAO_DEGRADE AS COLORACAO_DEGRADE,a.CRIZAL_SAPPHIRE_HR AS CRIZAL_SAPPHIRE_HR,
a.VERNIZ_HC_INC AS VERNIZ_HC_INC,a.INCOLOR AS INCOLOR,a.INDICE AS INDICE,a.DURAVISION_PLATINUM AS DURAVISION_PLATINUM,a.DURAVISION_SILVER AS DURAVISION_SILVER,
a.DURAVISION_CHROME AS DURAVISION_CHROME,a.DURAVISION_PLATINUM_UV AS DURAVISION_PLATINUM_UV,
a.DURAVISION_SILVER_UV AS DURAVISION_SILVER_UV,a.DURAVISION_CHROME_UV AS DURAVISION_CHROME_UV,a.MAR AS MAR,a.PRISMA AS PRISMA,a.ID_ABA_EXCEL
FROM TB_OPC_LENTES a
INNER JOIN TB_ID_LENTES b ON b.ID = a.ID_MARCA
WHERE a.ID_ABA_EXCEL = {$id_marca}
");

$read->setFetchMode(PDO::FETCH_ASSOC);
$read->execute();
$array = $read->fetchAll();

$qtd = 0;
$lista_produtos["Lentes"] = [];

foreach ($array as $dados) {
     $ID = $dados["ID"];
    $marca = $dados["MARCA"];
    $marcaVariant = $dados["MARCA_VARIANT"];
    $produto = $dados["PRODUTO"];
    $precoPar = "R$ " . number_format($dados["PRECO_PAR"], 2, ",", ".");
    $esferico = $dados["ESFERICO"];
    $cilindrico = $dados["CILINDRICO"];
    $duracao = $dados["DURACAO"];
    $blueUV = $dados["BLUE_UV"];
    $disponibilidade = $dados["DISPONIBILIDADE"];
    $disponibilidadeDois = $dados["DISPONIBILIDADE_DOIS"];
    $comAr = "R$ " . $dados["COM_AR"];
    $adicao = $dados["ADICAO"];
    $altura = $dados["ALTURA"];
    $ir = $dados["IR"];
    $cil = $dados["CIL"];
    $diam = $dados["DIAM"];
    $visodrop = $dados["VISODROP"];
    $crizalPrevencia = "R$ " .number_format($dados["CRIZAL_PREVENCIA"], 2,",", ".");
    $crizalSapphire = "R$ " .number_format($dados["CRIZAL_SAPPHIRE"], 2,",", ".");
    $crizalRock ="R$ " .number_format($dados["CRIZAL_ROCK"], 2,",", ".");
    $crizalEasyPro = "R$ " .number_format($dados["CRIZAL_EASY_PRO"], 2,",", ".");
    $optifog = "R$ " .number_format($dados["OPTIFOG"], 2,",", "." );
    $trioEasyClean = "R$ " .number_format($dados["TRIO_EASY_CLEAN"], 2,",", ".");
    $semAr =  "R$ " .number_format($dados["SEM_AR"], 2,",", ".");
    $noReflex =  "R$ " .number_format($dados["NO_REFLEX"], 2,",", ".");
    $coloracaoSimples ="R$ " .number_format($dados["COLORACAO_SIMPLES"], 2, ",", ".");
    $coloracaoDegrade = "R$ " .number_format($dados["COLORACAO_DEGRADE"], 2, ",", ".");
    $crizalSapphireHr =  "R$ " . number_format($dados["CRIZAL_SAPPHIRE_HR"], 2,",", ".");
    $vernizHcInc = $dados["VERNIZ_HC_INC"];
    $incolor = $dados["INCOLOR"];
    $indice = $dados["INDICE"];
    $duravisionPlatinum = $dados["DURAVISION_PLATINUM"];
    $duravisionSilver = $dados["DURAVISION_SILVER"];
    $duravisionChrome = $dados["DURAVISION_CHROME"];
    $duravisionPlatinumUv = $dados["DURAVISION_PLATINUM_UV"];
    $duravisionSilverUv = $dados["DURAVISION_SILVER_UV"];
    $duravisionChromeUv = $dados["DURAVISION_CHROME_UV"];
    $mar = $dados["MAR"];
    $prisma = $dados["PRISMA"];
    $idabaexcel = $dados["ID_ABA_EXCEL"];

    $lista_produtos["Lentes"][] = [
        'ID' => $ID,
        'MARCA' => $marca,
        'MARCA_VARIANT' => $marcaVariant,
        'PRODUTO' => $produto,
        'PRECO_PAR' => $precoPar,
        'ESFERICO' => $esferico,
        'CILINDRICO' => $cilindrico,
        'DURACAO' => $duracao,
        'BLUE_UV' => $blueUV,
        'DISPONIBILIDADE' => $disponibilidade,
        'DISPONIBILIDADE_DOIS' => $disponibilidadeDois,
        'COM_AR' => $comAr,
        'ADICAO' => $adicao,
        'ALTURA' => $altura,
        'IR' => $ir,
        'CIL' => $cil,
        'DIAM' => $diam,
        'VISODROP' => $visodrop,
        'CRIZAL_PREVENCIA' => $crizalPrevencia,
        'CRIZAL_SAPPHIRE' => $crizalSapphire,
        'CRIZAL_ROCK' => $crizalRock,
        'CRIZAL_EASY_PRO' => $crizalEasyPro,
        'OPTIFOG' => $optifog,
        'TRIO_EASY_CLEAN' => $trioEasyClean,
        'SEM_AR' => $semAr,
        'NO_REFLEX' => $noReflex,
        'COLORACAO_SIMPLES' => $coloracaoSimples,
        'COLORACAO_DEGRADE' => $coloracaoDegrade,
        'CRIZAL_SAPPHIRE_HR' => $crizalSapphireHr,
        'VERNIZ_HC_INC' => $vernizHcInc,
        'INCOLOR' => $incolor,
        'INDICE' => $indice,
        'DURAVISION_PLATINUM' => $duravisionPlatinum,
        'DURAVISION_SILVER' => $duravisionSilver,
        'DURAVISION_CHROME' => $duravisionChrome,
        'DURAVISION_PLATINUM_UV' => $duravisionPlatinumUv,
        'DURAVISION_SILVER_UV' => $duravisionSilverUv,
        'DURAVISION_CHROME_UV' => $duravisionChromeUv,
        'MAR' => $mar,
        'PRISMA' => $prisma,
        'ID_ABA_EXCEL' => $idabaexcel
    ];
    $qtd++;
}

// Resposta com status 200
http_response_code(200);

// Retornar as informações em JSON
echo json_encode($lista_produtos);

// echo "Total geral: {$qtd}";
?>

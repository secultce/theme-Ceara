<?php

$registration = $entity;
$status = $registration->status;

$bank_data_form_action = $app->createUrl('aldirblanc', 'status', [
    'registration_id' => $registration->id
]);

$type_bank_accounts = [
    'Conta corrente',
    'Conta poupança'
];

$banks = [
    '001 - BANCO DO BRASIL',
    '104 - CAIXA ECONOMICA FEDERAL',
    '237 - BANCO BRADESCO',
    '341 - ITAÚ UNIBANCO',
    '033 - BANCO SANTANDER (BRASIL)',
    '260 - NU PAGAMENTOS',
    '323 - MERCADO PAGO',
    '290 - PAGSEGURO',
    '003 - BANCO DA AMAZONIA',
    '004 - BANCO DO NORDESTE DO BRASIL',
    '007 - BNDES',
    '010 - CREDICOAMO',
    '011 - C.SUISSE HEDGING-GRIFFO CV S/A',
    '012 - BANCO INBURSA',
    '014 - STATE STREET BR BANCO COMERCIAL',
    '015 - UBS BRASIL CCTVM',
    '016 - SICOOB CREDITRAN',
    '017 - BNY MELLON BANCO',
    '018 - BANCO TRICURY',
    '021 - BANCO BANESTES',
    '024 - BANCO BANDEPE',
    '025 - BANCO ALFA',
    '029 - BANCO ITAÚ CONSIGNADO',
    '036 - BANCO BBI',
    '037 - BANCO DO EST. DO PA',
    '040 - BANCO CARGILL',
    '041 - BANCO DO ESTADO DO RS',
    '047 - BANCO DO EST. DE SE',
    '060 - CONFIDENCE CC',
    '062 - HIPERCARD BM',
    '063 - BANCO BRADESCARD',
    '064 - GOLDMAN SACHS DO BRASIL BM S.A',
    '065 - BANCO ANDBANK',
    '066 - BANCO MORGAN STANLEY',
    '069 - BANCO CREFISA',
    '070 - BRB - BANCO DE BRASILIA',
    '074 - BCO. J.SAFRA',
    '075 - BANCO ABN AMRO',
    '076 - BANCO KDB BRASIL',
    '077 - BANCO INTER',
    '078 - HAITONG BI DO BRASIL',
    '079 - BANCO ORIGINAL DO AGRO S/A',
    '080 - B&T CC LTDA.',
    '081 - BANCOSEGURO',
    '082 - BANCO TOPÁZIO',
    '083 - BANCO DA CHINA BRASIL',
    '084 - UNIPRIME NORTE DO PARANÁ - CC',
    '085 - COOP CENTRAL AILOS',
    '088 - BANCO RANDON',
    '089 - CREDISAN CC',
    '091 - CCCM UNICRED CENTRAL RS',
    '092 - BRK CFI',
    '093 - POLOCRED SCMEPP LTDA.',
    '094 - BANCO FINAXIS',
    '095 - TRAVELEX BANCO DE CÂMBIO',
    '096 - BANCO B3',
    '097 - CREDISIS CENTRAL DE COOPERATIVAS DE CRÉDITO LTDA.',
    '098 - CREDIALIANÇA CCR',
    '099 - UNIPRIME CENTRAL CCC LTDA.',
    '100 - PLANNER CV',
    '101 - RENASCENCA DTVM LTDA',
    '102 - XP INVESTIMENTOS CCTVM S/A',
    '105 - LECCA CFI',
    '107 - BANCO BOCOM BBM',
    '108 - PORTOCRED - CFI',
    '111 - OLIVEIRA TRUST DTVM',
    '113 - MAGLIANO CCVM',
    '114 - CENTRAL COOPERATIVA DE CRÉDITO NO ESTADO DO ESPÍRITO SANTO',
    '117 - ADVANCED CC LTDA',
    '119 - BANCO WESTERN UNION',
    '120 - BANCO RODOBENS',
    '121 - BANCO AGIBANK',
    '122 - BANCO BRADESCO BERJ',
    '124 - BANCO WOORI BANK DO BRASIL',
    '125 - PLURAL BANCO BM',
    '126 - BR PARTNERS BI',
    '127 - CODEPE CVC',
    '128 - MS BANK BANCO DE CÂMBIO',
    '129 - UBS BRASIL BI',
    '130 - CARUANA SCFI',
    '131 - TULLETT PREBON BRASIL CVC LTDA',
    '132 - ICBC DO BRASIL BM',
    '133 - CRESOL CONFEDERAÇÃO',
    '134 - BGC LIQUIDEZ DTVM LTDA',
    '136 - UNICRED',
    '138 - GET MONEY CC LTDA',
    '139 - INTESA SANPAOLO BRASIL BM',
    '140 - EASYNVEST - TÍTULO CV SA',
    '142 - BROKER BRASIL CC LTDA.',
    '143 - TREVISO CC',
    '144 - BEXS BANCO DE CAMBIO',
    '145 - LEVYCAM CCV LTDA',
    '146 - GUITTA CC LTDA',
    '149 - FACTA CFI',
    '157 - ICAP DO BRASIL CTVM LTDA.',
    '159 - CASA CREDITO SCM',
    '163 - COMMERZBANK BRASIL - BANCO MÚLTIPLO',
    '169 - BANCO OLÉ CONSIGNADO',
    '173 - BRL TRUST DTVM SA',
    '174 - PERNAMBUCANAS FINANC CFI',
    '177 - GUIDE',
    '180 - CM CAPITAL MARKETS CCTVM LTDA',
    '183 - SOCRED SA - SCMEPP',
    '184 - BANCO ITAÚ BBA',
    '188 - ATIVA INVESTIMENTOS CCTVM',
    '189 - HS FINANCEIRA',
    '190 - SERVICOOP',
    '191 - NOVA FUTURA CTVM LTDA.',
    '194 - PARMETAL DTVM LTDA',
    '196 - FAIR CC',
    '197 - STONE PAGAMENTOS',
    '208 - BANCO BTG PACTUAL',
    '212 - BANCO ORIGINAL',
    '213 - BANCO ARBI',
    '217 - BANCO JOHN DEERE',
    '218 - BANCO BS2',
    '222 - BANCO CRÉDIT AGRICOLE BR',
    '224 - BANCO FIBRA',
    '233 - BANCO CIFRA',
    '241 - BANCO CLASSICO',
    '243 - BANCO MÁXIMA',
    '246 - BANCO ABC BRASIL',
    '249 - BANCO INVESTCRED UNIBANCO',
    '250 - BCV',
    '253 - BEXS CC',
    '254 - PARANA BANCO',
    '259 - MONEYCORP BANCO DE CÂMBIO',
    '265 - BANCO FATOR',
    '266 - BANCO CEDULA',
    '268 - BARI CIA HIPOTECÁRIA',
    '269 - BANCO HSBC',
    '270 - SAGITUR CC LTDA',
    '271 - IB CCTVM',
    '272 - AGK CC',
    '273 - CCR DE SÃO MIGUEL DO OESTE',
    '274 - MONEY PLUS SCMEPP LTDA',
    '276 - SENFF - CFI',
    '278 - GENIAL INVESTIMENTOS CVM',
    '279 - CCR DE PRIMAVERA DO LESTE',
    '280 - AVISTA CFI',
    '281 - CCR COOPAVEL',
    '283 - RB CAPITAL INVESTIMENTOS DTVM LTDA.',
    '285 - FRENTE CC LTDA.',
    '286 - CCR DE OURO',
    '288 - CAROL DTVM LTDA.',
    '289 - DECYSEO CC LTDA.',
    '292 - BS2 DTVM',
    '293 - LASTRO RDV DTVM LTDA',
    '296 - VISION CC',
    '298 - VIPS CC LTDA.',
    '299 - SOROCRED CFI',
    '300 - BANCO LA NACION ARGENTINA',
    '301 - BPP IP',
    '306 - PORTOPAR DTVM LTDA',
    '307 - TERRA INVESTIMENTOS DTVM',
    '309 - CAMBIONET CC LTDA',
    '310 - VORTX DTVM LTDA.',
    '313 - AMAZÔNIA CC LTDA.',
    '315 - PI DTVM',
    '318 - BANCO BMG',
    '319 - OM DTVM LTDA',
    '320 - BANCO CCB BRASIL',
    '321 - CREFAZ SCMEPP LTDA',
    '322 - CCR DE ABELARDO LUZ',
    '325 - ÓRAMA DTVM',
    '326 - PARATI - CFI',
    '329 - QI SCD',
    '330 - BANCO BARI',
    '331 - FRAM CAPITAL DTVM',
    '332 - ACESSO SOLUCOES PAGAMENTO SA',
    '335 - BANCO DIGIO',
    '336 - BANCO C6',
    '340 - SUPER PAGAMENTOS E ADMINISTRACAO DE MEIOS ELETRONICOS',
    '342 - CREDITAS SCD',
    '343 - FFA SCMEPP LTDA.',
    '348 - BANCO XP',
    '349 - AMAGGI CFI',
    '350 - CREHNOR LARANJEIRAS',
    '352 - TORO CTVM LTDA',
    '354 - NECTON INVESTIMENTOS S.A CVM',
    '355 - ÓTIMO SCD',
    '359 - ZEMA CFI S/A',
    '360 - TRINUS CAPITAL DTVM',
    '362 - CIELO',
    '363 - SOCOPA SC PAULISTA',
    '364 - GERENCIANET PAGTOS BRASIL LTDA',
    '365 - SOLIDUS CCVM',
    '366 - BANCO SOCIETE GENERALE BRASIL',
    '367 - VITREO DTVM',
    '368 - BANCO CSF',
    '370 - BANCO MIZUHO',
    '371 - WARREN CVMC LTDA',
    '373 - UP.P SEP',
    '376 - BANCO J.P. MORGAN',
    '378 - BBC LEASING',
    '379 - CECM COOPERFORTE',
    '381 - BANCO MERCEDES-BENZ',
    '382 - FIDUCIA SCMEPP LTDA',
    '383 - JUNO',
    '387 - BANCO TOYOTA DO BRASIL',
    '389 - BANCO MERCANTIL DO BRASIL',
    '390 - BANCO GM',
    '391 - CCR DE IBIAM',
    '393 - BANCO VOLKSWAGEN S.A',
    '394 - BANCO BRADESCO FINANC.',
    '396 - HUB PAGAMENTOS',
    '399 - KIRTON BANK',
    '412 - BANCO CAPITAL',
    '422 - BANCO SAFRA',
    '456 - BANCO MUFG BRASIL',
    '464 - BANCO SUMITOMO MITSUI BRASIL',
    '473 - BANCO CAIXA GERAL BRASIL',
    '477 - CITIBANK N.A.',
    '479 - BANCO ITAUBANK',
    '487 - DEUTSCHE BANKBANCO ALEMAO',
    '488 - JPMORGAN CHASE BANK',
    '492 - ING BANK N.V.',
    '495 - BANCO LA PROVINCIA B AIRES BCE',
    '505 - BANCO CREDIT SUISSE',
    '545 - SENSO CCVM',
    '600 - BANCO LUSO BRASILEIRO',
    '604 - BANCO INDUSTRIAL DO BRASIL',
    '610 - BANCO VR',
    '611 - BANCO PAULISTA',
    '612 - BANCO GUANABARA',
    '613 - OMNI BANCO',
    '623 - BANCO PAN',
    '626 - BANCO C6 CONSIG',
    '630 - SMARTBANK',
    '633 - BANCO RENDIMENTO',
    '634 - BANCO TRIANGULO',
    '637 - BANCO SOFISA',
    '643 - BANCO PINE',
    '652 - ITAÚ UNIBANCO HOLDING',
    '653 - BANCO INDUSVAL',
    '654 - BANCO DIGIMAIS',
    '655 - BANCO VOTORANTIM',
    '707 - BANCO DAYCOVAL S.A',
    '712 - BANCO OURINVEST',
    '739 - BANCO CETELEM',
    '741 - BANCO RIBEIRAO PRETO',
    '743 - BANCO SEMEAR',
    '745 - BANCO CITIBANK',
    '746 - BANCO MODAL',
    '747 - BANCO RABOBANK INTL BRASIL',
    '748 - BANCO COOPERATIVO SICREDI',
    '751 - SCOTIABANK BRASIL',
    '752 - BANCO BNP PARIBAS BRASIL S A',
    '753 - NOVO BANCO CONTINENTAL - BM',
    '754 - BANCO SISTEMA',
    '755 - BOFA MERRILL LYNCH BM',
    '756 - BANCOOB',
    '757 - BANCO KEB HANA DO BRASIL'
];

$banks_selected = $app->repo('RegistrationMeta')->findOneBy([
    'key' => 'field_6469',
    'owner' => $registration->id
])->value; // Banco

$type_bank_accounts_selected = $app->repo('RegistrationMeta')->findOneBy([
    'key' => 'field_6494',
    'owner' => $registration->id
])->value; // Tipo de conta bancária

$agency = explode("-", $app->repo('RegistrationMeta')->findOneBy([
    'key' => 'field_6468',
    'owner' => $registration->id
])->value); // Número da agência

$account = explode("-", $app->repo('RegistrationMeta')->findOneBy([
    'key' => 'field_6464',
    'owner' => $registration->id
])->value); // Número da conta e digito

?>

<?php if($status == '10'): ?>
    
    <div class="registration-fieldset clearfix">
        <h1>Atualizar dados bancarios</h1>
        <form method="POST" action="<?php echo $bank_data_form_action; ?>">    
            <label for="banks">BANCO:</label>
            <br />
            <select name="banks" id="banks">
                <?php foreach($banks as $bank): ?>
                    <?php if($bank === $banks_selected): ?>
                        <option selected="selected" value="<?= $bank ?>"><?= $bank ?></option>
                    <?php else: ?>
                        <option value="<?= $bank ?>"><?= $bank ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
            <br />
            <label for="type_bank_accounts">TIPO DE CONTA BANCÁRIA:</label>
            <br />
            <select name="type_bank_accounts" id="type_bank_accounts">
                <?php foreach($type_bank_accounts as $type_bank_account): ?>
                    <?php if($type_bank_account === $type_bank_accounts_selected): ?>
                        <option selected="selected" value="<?= $type_bank_account ?>"><?= $type_bank_account ?></option>
                    <?php else: ?>
                        <option value="<?= $type_bank_account ?>"><?= $type_bank_account ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
            <br />
            <label>NÚMERO DA AGÊNCIA</label>

            <input type="text" name="agency_number" value="<?= $agency[0] ?>" />
            <label> E DIGITO (SE HOUVER)</label>
            <input type="text" name="agency_digit" value="<?= count($agency) == 2 ? $agency[1]: "" ?>" />
            <br />
            <label>NÚMERO DA CONTA</label>
            <input type="text" name="account_number" value="<?= $account[0] ?>" />
            <label> E DIGITO</label>
            <input type="text" name="account_digit" value="<?= count($account) == 2 ? $account[1]: "" ?>" />
            <br />
            <button type="submit">Enviar</button>
        </form>
    </div>
<?php endif; ?>
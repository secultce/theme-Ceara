<?php
/**
 * User: nalomysouza
 * Date: 08/05/18
 * Time: 22:00
 */

return [
    'maps.center' => [-5.058114374355702, -39.4134521484375],
    'maps.zoom.default' => 7,

    'registration.ownerDefinition' =>[
        'required' => true,
        'label' => \MapasCulturais\i::__('Agente responsável pela inscrição'),
        'agentRelationGroupName' => 'owner',
        'description' => \MapasCulturais\i::__('Pessoa física com os campos nome, endereço, data de nascimento, raça, gênero, email, telefone principal preenchidos. Além dos documentos CPF, identidade (RG) com data de expedição e órgão expedidor obrigatoriamente preenchidos.'),
        'type' => 1,
        'requiredProperties' => ['nomeCompleto','documento','identidade','expedicaoIdentidade','expedidorIdentidade','endereco','dataDeNascimento','raca','genero','emailPrivado','telefone1']
    ],
    'registration.agentRelations' => [
        array(
            'required' => false,
            'label' => \MapasCulturais\i::__('Instituição responsável'),
            'agentRelationGroupName' => 'instituicao',
            'description' => \MapasCulturais\i::__('Agente pessoa jurídica com cadastro dos campos Razão Social, CNPJ, Nome Fantasia, Código da Natureza Jurídica,  Código da Atividade Principal, Data de Fundação, Endereço, Email e Telefone obrigatoriamente preenchidos.'),
            'type' => 2,
            'requiredProperties' => array('razaoSocial', 'dataDeFundacao', 'cnpj', 'endereco', 'emailPrivado', 'telefone1', 'nomeFantasia', 'naturezaJuridica', 'atividadePrincipal')
        ),
        array(
            'required' => false,
            'label' => \MapasCulturais\i::__('Coletivo'),
            'agentRelationGroupName' => 'coletivo',
            'description' => \MapasCulturais\i::__('Agente coletivo sem CNPJ, com os campos Nome ou Razão Social, email e telefone do coletivo obrigatoriamente preenchidos'),
            'type' => 2,
            'requiredProperties' => ['razaoSocial', 'emailPrivado', 'telefone1']
        )
    ]
];

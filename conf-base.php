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
            'description' => \MapasCulturais\i::__('Pessoa jurídica com os campos nome, endereço, CNPJ, data de fundação, email e telefone principal obrigatoriamente preenchidos.'),
            'type' => 2,
            'requiredProperties' => array('nomeCompleto','endereco','documento', 'dataDeNascimento', 'emailPrivado', 'telefone1')
        ),
        array(
            'required' => false,
            'label' => \MapasCulturais\i::__('Coletivo'),
            'agentRelationGroupName' => 'coletivo',
            'description' => \MapasCulturais\i::__('Agente coletivo sem CNPJ, com os campos Data de Nascimento/Fundação, email e telefone principal obrigatoriamente preenchidos'),
            'type' => 2,
            'requiredProperties' => ['dataDeNascimento', 'emailPrivado', 'telefone1']
        )
    ]
];
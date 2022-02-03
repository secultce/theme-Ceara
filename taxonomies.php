<?php
return [
    1 => [
        'slug' => \MapasCulturais\i::__('tag'),
        'entities' => [
            'MapasCulturais\Entities\Space',
            'MapasCulturais\Entities\Agent',
            'MapasCulturais\Entities\Event',
            'MapasCulturais\Entities\Project',
            'MapasCulturais\Entities\Opportunity',
        ]
    ],

    2 => [
        'slug' => \MapasCulturais\i::__('area'),
        'required' => \MapasCulturais\i::__("Informe, pelo menos, uma área de atuação."),
        'entities' => [
            'MapasCulturais\Entities\Space',
            'MapasCulturais\Entities\Agent',
            'MapasCulturais\Entities\Project'
        ],
        'restricted_terms' => [
            \MapasCulturais\i::__('Antropologia'),
            \MapasCulturais\i::__('Arqueologia'),
            \MapasCulturais\i::__('Arquitetura-Urbanismo'),
            \MapasCulturais\i::__('Arquivo'),
            \MapasCulturais\i::__('Arte de Rua'),
            \MapasCulturais\i::__('Arte Digital'),
            \MapasCulturais\i::__('Artes integradas'),
            \MapasCulturais\i::__('Artes Visuais'),
            \MapasCulturais\i::__('Artesanato'),
            \MapasCulturais\i::__('Audiovisual'),
            \MapasCulturais\i::__('Cinema'),
            \MapasCulturais\i::__('Circo'),
            \MapasCulturais\i::__('Comunicação'),
            \MapasCulturais\i::__('Cultura Afro-brasileira'),
            \MapasCulturais\i::__('Cultura Cigana'),
            \MapasCulturais\i::__('Cultura Digital'),
            \MapasCulturais\i::__('Cultura Estrangeira (imigrantes)'),
            \MapasCulturais\i::__('Cultura Indígena'),
            \MapasCulturais\i::__('Cultura LGBT'),
            \MapasCulturais\i::__('Cultura Negra'),
            \MapasCulturais\i::__('Cultura Popular'),
            \MapasCulturais\i::__('Dança'),
            \MapasCulturais\i::__('Design'),
            \MapasCulturais\i::__('Direito Autoral'),
            \MapasCulturais\i::__('Economia Criativa'),
            \MapasCulturais\i::__('Educação'),
            \MapasCulturais\i::__('Esporte'),
            \MapasCulturais\i::__('Filosofia'),
            \MapasCulturais\i::__('Fotografia'),
            \MapasCulturais\i::__('Gastronomia'),
            \MapasCulturais\i::__('Gestão Cultural'),
            \MapasCulturais\i::__('História'),
            \MapasCulturais\i::__('Humor'),
            \MapasCulturais\i::__('Jogos Eletrônicos'),
            \MapasCulturais\i::__('Jornalismo'),
            \MapasCulturais\i::__('Leitura'),
            \MapasCulturais\i::__('Literatura'),
            \MapasCulturais\i::__('Livro'),
            \MapasCulturais\i::__('Meio Ambiente'),
            \MapasCulturais\i::__('Mídias Sociais'),
            \MapasCulturais\i::__('Moda'),
            \MapasCulturais\i::__('Museu'),
            \MapasCulturais\i::__('Música'),
            \MapasCulturais\i::__('Novas Mídias'),
            \MapasCulturais\i::__('Patrimônio Imaterial'),
            \MapasCulturais\i::__('Patrimônio Material'),
            \MapasCulturais\i::__('Performance'),
            \MapasCulturais\i::__('Pesquisa'),
            \MapasCulturais\i::__('Produção'),
            \MapasCulturais\i::__('Produção Cultural'),
            \MapasCulturais\i::__('Povos Tradicionais de Matriz Africana'),
            \MapasCulturais\i::__('Quilombola'),
            \MapasCulturais\i::__('Rádio'),
            \MapasCulturais\i::__('Saúde'),
            \MapasCulturais\i::__('Sociologia'),
            \MapasCulturais\i::__('Teatro'),
            \MapasCulturais\i::__('Técnico Cenografia'),
            \MapasCulturais\i::__('Técnico de Sonorização'),
            \MapasCulturais\i::__('Técnico Figurino'),
            \MapasCulturais\i::__('Técnico Iluminação'),
            \MapasCulturais\i::__('Televisão'),
            \MapasCulturais\i::__('Turismo'),
            \MapasCulturais\i::__('Outros')
        ]
    ],

    3 => [
        'slug' => \MapasCulturais\i::__('linguagem'),
        'required' => \MapasCulturais\i::__("Informe, pelo menos, uma linguagem."),
        'entities' => [
            'MapasCulturais\Entities\Event'
        ],
        'restricted_terms' => [
            \MapasCulturais\i::__('Artes Circenses'),
            \MapasCulturais\i::__('Artes Integradas'),
            \MapasCulturais\i::__('Artes Visuais'),
            \MapasCulturais\i::__('Audiovisual'),
            \MapasCulturais\i::__('Cinema'),
            \MapasCulturais\i::__('Cultura Digital'),
            \MapasCulturais\i::__('Cultura Indígena'),
            \MapasCulturais\i::__('Cultura Tradicional'),
            \MapasCulturais\i::__('Curso ou Oficina'),
            \MapasCulturais\i::__('Dança'),
            \MapasCulturais\i::__('Exposição'),
            \MapasCulturais\i::__('Hip Hop'),
            \MapasCulturais\i::__('Livro e Literatura'),
            \MapasCulturais\i::__('Música Popular'),
            \MapasCulturais\i::__('Música Erudita'),
            \MapasCulturais\i::__('Palestra, Debate ou Encontro'),
            \MapasCulturais\i::__('Rádio'),
            \MapasCulturais\i::__('Teatro'),
            \MapasCulturais\i::__('Outros')
        ]
    ],

    4 => [
        'slug' => \MapasCulturais\i::__('publico'),
        //'required' => \MapasCulturais\i::__("Informe, pelo menos, um público-alvo."),
        'entities' => [
            'MapasCulturais\Entities\Project'
        ],
        'restricted_terms' => [
            \MapasCulturais\i::__('Adolescentes'),
            \MapasCulturais\i::__('Adultos'),
            \MapasCulturais\i::__('Afrodescentes'),
            \MapasCulturais\i::__('Crianças'),
            \MapasCulturais\i::__('Ciganos'),
            \MapasCulturais\i::__('Deficientes'),
            \MapasCulturais\i::__('Homens'),
            \MapasCulturais\i::__('Idosos'),
            \MapasCulturais\i::__('Indígenas'),
            \MapasCulturais\i::__('Jovens'),
            \MapasCulturais\i::__('LGBT'),
            \MapasCulturais\i::__('Mulheres'),
            \MapasCulturais\i::__('População da Zona Rural'),
            \MapasCulturais\i::__('Quilombolas'),
            \MapasCulturais\i::__('Ribeirinhos'),
            \MapasCulturais\i::__('Outros')
        ]
    ],

    5 => [
        'slug' => \MapasCulturais\i::__('municipio'),
        //'required' => \MapasCulturais\i::__("Informe, pelo menos, um município."),
        'entities' => [
            'MapasCulturais\Entities\Project'
        ],
        'restricted_terms' => [
            \MapasCulturais\i::__('Abaiara'),
            \MapasCulturais\i::__('Acarape'),
            \MapasCulturais\i::__('Acaraú'),
            \MapasCulturais\i::__('Acopiara'),
            \MapasCulturais\i::__('Aiuaba'),
            \MapasCulturais\i::__('Alcântaras'),
            \MapasCulturais\i::__('Altaneira'),
            \MapasCulturais\i::__('Alto Santo'),
            \MapasCulturais\i::__('Amontada'),
            \MapasCulturais\i::__('Antonina do Norte'),
            \MapasCulturais\i::__('Apuiarés'),
            \MapasCulturais\i::__('Aquiraz'),
            \MapasCulturais\i::__('Aracati'),
            \MapasCulturais\i::__('Aracoiaba'),
            \MapasCulturais\i::__('Ararendá'),
            \MapasCulturais\i::__('Araripe'),
            \MapasCulturais\i::__('Aratuba'),
            \MapasCulturais\i::__('Arneiroz'),
            \MapasCulturais\i::__('Assaré'),
            \MapasCulturais\i::__('Aurora'),
            \MapasCulturais\i::__('Baixio'),
            \MapasCulturais\i::__('Banabuiú'),
            \MapasCulturais\i::__('Barbalha'),
            \MapasCulturais\i::__('Barreira'),
            \MapasCulturais\i::__('Barro'),
            \MapasCulturais\i::__('Barroquinha'),
            \MapasCulturais\i::__('Baturité'),
            \MapasCulturais\i::__('Beberibe'),
            \MapasCulturais\i::__('Bela Cruz'),
            \MapasCulturais\i::__('Boa Viagem'),
            \MapasCulturais\i::__('Brejo Santo'),
            \MapasCulturais\i::__('Camocim'),
            \MapasCulturais\i::__('Campos Sales'),
            \MapasCulturais\i::__('Canindé'),
            \MapasCulturais\i::__('Capistrano'),
            \MapasCulturais\i::__('Caridade'),
            \MapasCulturais\i::__('Cariré'),
            \MapasCulturais\i::__('Caririaçu'),
            \MapasCulturais\i::__('Cariús'),
            \MapasCulturais\i::__('Carnaubal'),
            \MapasCulturais\i::__('Cascavel'),
            \MapasCulturais\i::__('Catarina'),
            \MapasCulturais\i::__('Catunda'),
            \MapasCulturais\i::__('Caucaia'),
            \MapasCulturais\i::__('Cedro'),
            \MapasCulturais\i::__('Chaval'),
            \MapasCulturais\i::__('Choró'),
            \MapasCulturais\i::__('Chorozinho'),
            \MapasCulturais\i::__('Coreaú'),
            \MapasCulturais\i::__('Crateús'),
            \MapasCulturais\i::__('Crato'),
            \MapasCulturais\i::__('Croatá'),
            \MapasCulturais\i::__('Cruz'),
            \MapasCulturais\i::__('Deputado Irapuan Pinheiro'),
            \MapasCulturais\i::__('Ererê'),
            \MapasCulturais\i::__('Eusébio'),
            \MapasCulturais\i::__('Farias Brito'),
            \MapasCulturais\i::__('Forquilha'),
            \MapasCulturais\i::__('Fortaleza'),
            \MapasCulturais\i::__('Fortim'),
            \MapasCulturais\i::__('Frecheirinhas'),
            \MapasCulturais\i::__('General Sampaio'),
            \MapasCulturais\i::__('Graça'),
            \MapasCulturais\i::__('Granja'),
            \MapasCulturais\i::__('Granjeiro'),
            \MapasCulturais\i::__('Groaíras'),
            \MapasCulturais\i::__('Guaiúba'),
            \MapasCulturais\i::__('Guaraciaba do Norte'),
            \MapasCulturais\i::__('Guaramiranga'),
            \MapasCulturais\i::__('Hidrolândia'),
            \MapasCulturais\i::__('Horizonte'),
            \MapasCulturais\i::__('Ibaretama'),
            \MapasCulturais\i::__('Ibiapina'),
            \MapasCulturais\i::__('Ibicuitinga'),
            \MapasCulturais\i::__('Icapuí'),
            \MapasCulturais\i::__('Icó'),
            \MapasCulturais\i::__('Iguatu'),
            \MapasCulturais\i::__('Independência'),
            \MapasCulturais\i::__('Ipaporanga'),
            \MapasCulturais\i::__('Ipaumirim'),
            \MapasCulturais\i::__('Ipu'),
            \MapasCulturais\i::__('Ipueiras'),
            \MapasCulturais\i::__('Iracema'),
            \MapasCulturais\i::__('Irauçuba'),
            \MapasCulturais\i::__('Itaiçaba'),
            \MapasCulturais\i::__('Itaitinga'),
            \MapasCulturais\i::__('Itapajé'),
            \MapasCulturais\i::__('Itapipoca'),
            \MapasCulturais\i::__('Itapiúna'),
            \MapasCulturais\i::__('Itarema'),
            \MapasCulturais\i::__('Itatira'),
            \MapasCulturais\i::__('Jaguaretama'),
            \MapasCulturais\i::__('Jaguaribara'),
            \MapasCulturais\i::__('Jaguaribe'),
            \MapasCulturais\i::__('Jaguaruana'),
            \MapasCulturais\i::__('Jardim'),
            \MapasCulturais\i::__('Jati'),
            \MapasCulturais\i::__('Jijoca de Jericoacoara'),
            \MapasCulturais\i::__('Juazeiro do Norte'),
            \MapasCulturais\i::__('Jucás'),
            \MapasCulturais\i::__('Lavras da Mangabeira'),
            \MapasCulturais\i::__('Limoeiro do Norte'),
            \MapasCulturais\i::__('Madalena'),
            \MapasCulturais\i::__('Maracanaú'),
            \MapasCulturais\i::__('Maranguape'),
            \MapasCulturais\i::__('Marco'),
            \MapasCulturais\i::__('Martinópole'),
            \MapasCulturais\i::__('Massapê'),
            \MapasCulturais\i::__('Mauriti'),
            \MapasCulturais\i::__('Meruoca'),
            \MapasCulturais\i::__('Milagres'),
            \MapasCulturais\i::__('Milhã'),
            \MapasCulturais\i::__('Miraíma'),
            \MapasCulturais\i::__('Missão Velha'),
            \MapasCulturais\i::__('Mombaça'),
            \MapasCulturais\i::__('Monsenhor Tabosa'),
            \MapasCulturais\i::__('Morada Nova'),
            \MapasCulturais\i::__('Moraújo'),
            \MapasCulturais\i::__('Morrinhos'),
            \MapasCulturais\i::__('Mucambo'),
            \MapasCulturais\i::__('Mulungu'),
            \MapasCulturais\i::__('Nova Olinda'),
            \MapasCulturais\i::__('Nova Russas'),
            \MapasCulturais\i::__('Novo Oriente'),
            \MapasCulturais\i::__('Ocara'),
            \MapasCulturais\i::__('Orós'),
            \MapasCulturais\i::__('Pacajus'),
            \MapasCulturais\i::__('Pacatuba'),
            \MapasCulturais\i::__('Pacoti'),
            \MapasCulturais\i::__('Pacujá'),
            \MapasCulturais\i::__('Palhano'),
            \MapasCulturais\i::__('Palmácia'),
            \MapasCulturais\i::__('Paracuru'),
            \MapasCulturais\i::__('Paraipaba'),
            \MapasCulturais\i::__('Parambu'),
            \MapasCulturais\i::__('Paramoti'),
            \MapasCulturais\i::__('Pedra Branca'),
            \MapasCulturais\i::__('Penaforte'),
            \MapasCulturais\i::__('Pentecoste'),
            \MapasCulturais\i::__('Pereiro'),
            \MapasCulturais\i::__('Pindoretama'),
            \MapasCulturais\i::__('Piquet Carneiro'),
            \MapasCulturais\i::__('Pires Ferreira'),
            \MapasCulturais\i::__('Poranga'),
            \MapasCulturais\i::__('Porteiras'),
            \MapasCulturais\i::__('Potengi'),
            \MapasCulturais\i::__('Potiretama'),
            \MapasCulturais\i::__('Quiterianópolis'),
            \MapasCulturais\i::__('Quixadá'),
            \MapasCulturais\i::__('Quixelô'),
            \MapasCulturais\i::__('Quixeramobim'),
            \MapasCulturais\i::__('Quixeré'),
            \MapasCulturais\i::__('Redenção'),
            \MapasCulturais\i::__('Reriutaba'),
            \MapasCulturais\i::__('Russas'),
            \MapasCulturais\i::__('Saboeiro'),
            \MapasCulturais\i::__('Salitre'),
            \MapasCulturais\i::__('Santa Quitéria'),
            \MapasCulturais\i::__('Santana do Acaraú'),
            \MapasCulturais\i::__('Santana do Cariri'),
            \MapasCulturais\i::__('São Benedito'),
            \MapasCulturais\i::__('São Gonçalo do Amarante'),
            \MapasCulturais\i::__('São João do Jaguaribe'),
            \MapasCulturais\i::__('São Luís do Curu'),
            \MapasCulturais\i::__('Senador Pompeu'),
            \MapasCulturais\i::__('Senador Sá'),
            \MapasCulturais\i::__('Sobral'),
            \MapasCulturais\i::__('Solonópole'),
            \MapasCulturais\i::__('Tabuleiro do Norte'),
            \MapasCulturais\i::__('Tamboril'),
            \MapasCulturais\i::__('Tarrafas'),
            \MapasCulturais\i::__('Tauá'),
            \MapasCulturais\i::__('Tejuçuoca'),
            \MapasCulturais\i::__('Tianguá'),
            \MapasCulturais\i::__('Trairi'),
            \MapasCulturais\i::__('Tururu'),
            \MapasCulturais\i::__('Ubajara'),
            \MapasCulturais\i::__('Umari'),
            \MapasCulturais\i::__('Umirim'),
            \MapasCulturais\i::__('Uruburetama'),
            \MapasCulturais\i::__('Uruoca'),
            \MapasCulturais\i::__('Varjota'),
            \MapasCulturais\i::__('Várzea Alegre'),
            \MapasCulturais\i::__('Viçosa do Ceará')
        ]
    ]
];

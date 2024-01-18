<?php

namespace Ceara\Controllers;

use MapasCulturais\App;
use MapasCulturais\Controllers\EntityController;

class OpportunityFields extends EntityController
{
    public function GET_index(): void
    {
        $app = App::i();
        $opportunityId = $this->data['opportunityId'];

        /**
         * @var \MapasCulturais\Entities\Opportunity $opportunity
         */
        $opportunity = $app->repo('Opportunity')->find($opportunityId);
        if(!$opportunity->canUser('modify')) {
            $this->errorJson(['message' => 'Usuário não tem acesso.'], 403);
            return;
        }

        $quantityFields = $this->countFields($opportunity);

        $this->json($quantityFields);
    }

    private function countFields(\MapasCulturais\Entities\Opportunity $opportunity): int
    {
        $app = App::i();

        $query = $app
            ->getEm()
            ->createQuery("SELECT rfc FROM MapasCulturais\Entities\RegistrationFieldConfiguration rfc WHERE rfc.owner = :opportunity_id");
        $query->setParameters([
            'opportunity_id' => $opportunity->id
        ]);
        $registrationFields = $query->getResult();

        $query = $app
            ->getEm()
            ->createQuery("SELECT rfc FROM MapasCulturais\Entities\RegistrationFileConfiguration rfc WHERE rfc.owner = :opportunity_id");
        $query->setParameters([
            'opportunity_id' => $opportunity->id
        ]);
        $registrationFieldsFiles = $query->getResult();

        return count($registrationFieldsFiles) + count($registrationFields);
    }
}
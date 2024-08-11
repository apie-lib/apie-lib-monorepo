<?php

namespace Apie\Maker\BoundedContext\Resources;

use Apie\Core\Attributes\FakeCount;
use Apie\Core\Attributes\FakeMethod;
use Apie\Core\Identifiers\Identifier;
use Apie\Core\Identifiers\Ulid;
use Apie\Maker\BoundedContext\Identifiers\BoundedContextDefinitionIdentifier;
use Faker\Generator;

#[FakeCount(2)]
#[FakeMethod('createRandom')]
class BoundedContextDefinition implements \Apie\Core\Entities\EntityInterface
{
    private const EXAMPLE_NAMES = [
        'usermanagement',
        'customersupport',
        'orderprocessing',
        'paymentprocessing',
        'inventorymanagement',
        'productcatalog',
        'shippinganddelivery',
        'suppliermanagement',
        'warehousemanagement',
        'sales',
        'marketing',
        'advertising',
        'customerrelationshipmanagement',
        'subscriptionmanagement',
        'billingandinvoicing',
        'financialaccounting',
        'expensetracking',
        'humanresources',
        'employeemanagement',
        'projectmanagement',
        'taskmanagement',
        'teamcollaboration',
        'legalcompliance',
        'contractmanagement',
        'riskmanagement',
        'policymanagement',
        'claimsprocessing',
        'insurancemanagement',
        'investmentmanagement',
        'taxreporting',
        'regulatorycompliance',
        'eventplanning',
        'resourcemanagement',
        'documentmanagement',
        'contentmanagement',
        'filestorage',
        'versioncontrol',
        'auditlogging',
        'configurationmanagement',
        'systemmonitoring',
        'userauthentication',
        'accesscontrol',
        'notificationservice',
        'feedbackandreviews',
        'customerengagement',
        'loyaltyprograms',
        'promotionsanddiscounts',
        'surveymanagement',
        'analyticsandreporting',
        'businessintelligence',
        'forecasting',
        'budgeting',
        'inventoryreplenishment',
        'orderfulfillment',
        'returnsmanagement',
        'customeronboarding',
        'supportticketing',
        'issuetracking',
        'servicedesk',
        'complianceauditing',
        'securitymanagement',
        'qualityassurance',
        'testingandvalidation',
        'releasemanagement',
        'continuousintegration',
        'deploymentautomation',
        'devops',
        'itservicemanagement',
        'incidentmanagement',
        'problemmanagement',
        'changemanagement',
        'capacityplanning',
        'assetmanagement',
        'resourceallocation',
        'supplieronboarding',
        'procurement',
        'contractnegotiation',
        'vendormanagement',
        'demandplanning',
        'supplychainmanagement',
        'productionscheduling',
        'manufacturingexecution',
        'qualitycontrol',
        'maintenancemanagement',
        'facilitiesmanagement',
        'energymanagement',
        'environmentalcompliance',
        'sustainabilityreporting',
        'customersuccess',
        'partnermanagement',
        'channelmanagement',
        'salesoperations',
        'leadgeneration',
        'pipelinemanagement',
        'accountmanagement'
    ];
    private BoundedContextDefinitionIdentifier $id;

    public function __construct(public Identifier $name)
    {
        $this->id = BoundedContextDefinitionIdentifier::fromNameAndUlid($name, Ulid::createRandom());
    }

    public function getId(): BoundedContextDefinitionIdentifier
    {
        return $this->id;
    }

    public static function createRandom(Generator $faker): static
    {
        return new static(new Identifier($faker->unique()->randomElement(self::EXAMPLE_NAMES)));
    }
}

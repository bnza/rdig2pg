<?php

namespace App\Service;

use App\Entity\AreaEntity;
use App\Entity\BucketEntity;
use App\Entity\CampaignEntity;
use App\Entity\ContextEntity;
use App\Entity\FindingEntity;
use App\Entity\ObjectEntity;
use App\Entity\PhaseEntity;
use App\Entity\PotteryEntity;
use App\Entity\SampleEntity;
use App\Entity\SiteEntity;
use App\Entity\UserEntity;
use App\Entity\UsersSitesJoinEntity;
use App\Entity\VocFChronologyEntity;
use App\Entity\VocFColorEntity;
use App\Entity\VocOClassEntity;
use App\Entity\VocODecorationEntity;
use App\Entity\VocOMaterialClassEntity;
use App\Entity\VocOMaterialTypeEntity;
use App\Entity\VocOPreservationEntity;
use App\Entity\VocOTechniqueEntity;
use App\Entity\VocOTypeEntity;
use App\Entity\VocPClassEntity;
use App\Entity\VocPDecorationEntity;
use App\Entity\VocPFiringEntity;
use App\Entity\VocPInclusionsFrequencyEntity;
use App\Entity\VocPInclusionsSizeEntity;
use App\Entity\VocPInclusionsTypeEntity;
use App\Entity\VocPPreservationEntity;
use App\Entity\VocPShapeEntity;
use App\Entity\VocPSurfaceTreatmentEntity;
use App\Entity\VocPTechniqueEntity;
use App\Entity\VocSTypeEntity;
use App\Service\Migrator\MigratorInterface;
use Doctrine\ORM\EntityManager;

class SimplePgMigrator
{
    const CLASSES = [
        VocFChronologyEntity::class,
        VocFColorEntity::class,
        VocOClassEntity::class,
        VocODecorationEntity::class,
        VocOMaterialClassEntity::class,
        VocOMaterialTypeEntity::class,
        VocOPreservationEntity::class,
        VocOTechniqueEntity::class,
        VocOTypeEntity::class,
        VocPClassEntity::class,
        VocPDecorationEntity::class,
        VocPFiringEntity::class,
        VocPInclusionsFrequencyEntity::class,
        VocPInclusionsSizeEntity::class,
        VocPInclusionsTypeEntity::class,
        VocPPreservationEntity::class,
        VocPShapeEntity::class,
        VocPSurfaceTreatmentEntity::class,
        VocPTechniqueEntity::class,
        VocSTypeEntity::class,
        SiteEntity::class,
        AreaEntity::class,
        CampaignEntity::class,
        PhaseEntity::class,
        ContextEntity::class,
        BucketEntity::class,
        FindingEntity::class,
        ObjectEntity::class,
        PotteryEntity::class,
        SampleEntity::class,
        UserEntity::class,
        UsersSitesJoinEntity::class,
    ];

    private EntityManager $myEm;
    private EntityManager $pgEm;

    public function __construct(EntityManager $myEm, EntityManager $pgEm)
    {
        $this->myEm = $myEm;
        $this->pgEm = $pgEm;
    }

    public function migrate()
    {
        foreach (self::CLASSES as $class) {
            $path = explode('\\', $class);
            $class = array_pop($path);
            $migratorClass = "App\\Service\\Migrator\\${class}Migrator";
            /**
             * @var MigratorInterface $migrate
             */
            $migrator = new $migratorClass($this->myEm->getConnection(), $this->pgEm->getConnection());
            $migrator->migrate();
        }
    }
}

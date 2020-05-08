<?php

namespace App\Faker\Provider;

use App\Entity\BucketEntity;
use App\Entity\CampaignEntity;
use App\Entity\FindingEntity;
use Faker\Provider\Base as BaseProvider;

final class RdigProvider extends BaseProvider
{
    public function campaignFromBucket(BucketEntity $bucket): CampaignEntity
    {
        return $bucket->getCampaign();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Application;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Jose\Component\KeyManagement\JWKFactory;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $params;
    private $encoder;

    public function __construct(ParameterBagInterface $params, UserPasswordEncoderInterface $encoder)
    {
        $this->params = $params;
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $jwk = json_decode(base64_decode($this->params->get("app_commonground_secret_key")), true);
        $jwk = JWKFactory::createFromValues($jwk);

        $public = $jwk->toPublic();


        $application = new Application();
        $application->setLabel('Admin application');
        $application->setHasAllAuthorizations(true);
        $application->setClientIds([$this->params->get('app_id')]);
        $application->setPublicKey($public->jsonSerialize());

        $manager->persist($application);
        $manager->flush();
    }
}

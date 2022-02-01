<?php

namespace App\Serializer\Normalizer;

use ReflectionClass;
use App\Entity\Reseller;

use ReflectionException;
use App\Entity\OwnerForceInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * This class allow to set by default the property USER = current user : of the class who implements this interface
 */
class OwnerForceNormalizer implements ContextAwareNormalizerInterface, ContextAwareDenormalizerInterface, SerializerAwareInterface
{

    use SerializerAwaretrait;

    private $tokenStorage;
    private $authorizationChecker;

    public function __construct(TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     *
     * @param array $context options that denormalizers have access to
     */
    public function supportsDenormalization($data, string $type, string $format = null, array $context = [])
    {
        if (array_key_exists('owner_force', $context)) {
            return false == $context['owner_force'];
        }
        $testObject = new $type();
        return $testObject instanceof OwnerForceInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = [])
    {

    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $context['owner_force'] = true;
        $object = $this->serializer->denormalize($data, $type, $format, $context);

        if (!$token = $this->tokenStorage->getToken()) {
            return null;
        }
        $user = $token->getUser();
        $objectInfo = new ReflectionClass($type);
        try {
            $objectInfo->getProperty('user');
            $object->setUser($user);
        } catch (ReflectionException $e) {
            
        }
        return $object;
    }
}

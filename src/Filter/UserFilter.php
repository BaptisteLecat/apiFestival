<?php

namespace App\Filter;

use App\Annotation\UserAware;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Doctrine\Common\Annotations\Reader;

final class UserFilter extends SQLFilter
{
    private $reader;

    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if (null === $this->reader) {
            throw new \RuntimeException(sprintf('An annotation reader must be provided. Be sure to call "%s::setAnnotationReader()".', __CLASS__));
        }
        // The Doctrine filter is called for any query on any entity
        // Check if the current entity is "aware" (marked with an annotation)
        $aware = $this->reader->getClassAnnotation($targetEntity->getReflectionClass(), UserAware::class);
        if (!$aware) {
            return '';
        }
        $fieldName = $aware->fieldName;
        try {
            $fieldId = $this->getParameter('id');
        } catch (\InvalidArgumentException $e) {
            // No field id has been defined
            return '';
        }
        if (empty($fieldName) || empty($fieldId)) {
            return '';
        }
        return sprintf('%s.%s = %s', $targetTableAlias, $fieldName, $fieldId);
    }

    public function setAnnotationReader(Reader $reader): void
    {
        $this->reader = $reader;
    }
}
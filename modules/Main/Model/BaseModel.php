<?php

namespace Main\Model;

use Main\Entity\BaseEntity;
use Phalcon\Annotations\Annotation;
use Phalcon\Mvc\Model;
use \Phalcon\Annotations\Adapter\Memory as MemoryAnnotationAdapter;

/**
 * Base implementation of a custom model that uses EntityClass annotation as entity of work definer
 *
 * @author Ands
 *
 * @EntityClass("Main\Entity\BaseEntity")
 */
abstract class BaseModel extends Model {

    /** @var array */
    private static $_annotations = [];

    /** @var array */
    private static $_aliases;

    public static function _find($parameters = null) {
        return parent::find($parameters);
    }

    public static function find($parameters = null) {
        /** @var Model\ResultsetInterface|BaseModel[] $results */
        $results = parent::find($parameters);
        $entities = [];
        $entityClass = self::getCurrentEntityClass();

        foreach ($results as $result) {
            $resultArray = $result->toArray();

            foreach (self::getCurrentAliases() as $alias) {
                $resultArray[$alias] = $result->getRelated($alias);
            }

            /** @var BaseEntity $entity */
            $entity = new $entityClass();
            if (method_exists($entity, 'exchangeArray')) {
                $entity->exchangeArray($resultArray);
            }

            array_push($entities, $entity);
        }

        return $entities;
    }

    public function getRelated($alias, $arguments = null) {
        $modelRelated = parent::getRelated($alias, $arguments);

        if ($modelRelated instanceof BaseModel) {
            /** @var BaseModel|string $modelRelatedClass */
            $modelRelatedClass = get_class($modelRelated);

            $entityClass = $modelRelatedClass::getCurrentEntityClass();

            /** @var BaseEntity $entity */
            $entity = new $entityClass();

            $entity->exchangeArray($modelRelated->toArray());

            return $entity;
        } else {
            return null;
        }
    }

    public function initialize() {
        $annotations = self::getCurrentClassAnnotations();
        $relationshipAnnotations = ['BelongsTo', 'HasOne', 'HasMany', 'HasManyToMany'];
        $className = get_called_class();
        self::$_aliases[$className] = [];

        foreach ($annotations as $annotation) {
            if ($annotation->getName() == 'Source') {
                $this->setSource($annotation->getArgument(0));
            }

            if (in_array($annotation->getName(), $relationshipAnnotations)) {
                $method = lcfirst($annotation->getName());
                $annotationArgs = $annotation->getArguments();

                call_user_func_array([$this, $method], $annotation->getArguments());

                if (isset($annotationArgs[3]['alias'])) {
                    self::$_aliases[$className][] = $annotationArgs[3]['alias'];
                }
            }
        }
    }

    public static function getCurrentAliases() {
        $className = get_called_class();

        if (isset(self::$_aliases[$className])) {
            return self::$_aliases[$className];
        }

        return [];
    }

    public static function getCurrentEntityClass() {
        $annotations = self::getCurrentClassAnnotations();

        foreach ($annotations as $annotation) {
            if ($annotation->getName() == 'EntityClass') {
                return $annotation->getArgument(0);
            }
        }

        return BaseEntity::class;
    }

    /**
     * @param bool $renew
     * @return Annotation[]
     */
    private static function getCurrentClassAnnotations($renew = false) {
        $className = get_called_class();

        if (empty(self::$_annotations[$className]) || $renew) {
            $annotationReader = new MemoryAnnotationAdapter();
            self::$_annotations[$className] = $annotationReader->get($className)->getClassAnnotations();
        }

        return self::$_annotations[$className];
    }
}

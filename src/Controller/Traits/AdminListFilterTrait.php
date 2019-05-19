<?php

namespace App\Controller\Traits;

use App\Entity\Traits\MetaFieldTrait;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;

/**
 * Trait AdminListFilterTrait
 */
trait AdminListFilterTrait
{
    /**
     * Find all
     *
     * @param string $entityClass
     * @param int $page
     * @param int $maxPerPage
     * @param null $sortField
     * @param null $sortDirection
     * @param null $dqlFilter
     *
     * @return mixed|\Pagerfanta\Pagerfanta
     */
    protected function findAll(
        $entityClass,
        $page = 1,
        $maxPerPage = 15,
        $sortField = null,
        $sortDirection = null,
        $dqlFilter = null
    ) {
        $alias = $this->getAlias($entityClass);
        $query = $this->em->getRepository($entityClass)
            ->createQueryBuilder($alias);

        $usingTrait = in_array(
            MetaFieldTrait::class,
            array_keys((new \ReflectionClass($entityClass))->getTraits())
        );

        if ($usingTrait) {
            $query->where($alias . '.deletedAt IS NULL and ' . $alias . '.createdAt IS NOT NULL');

            // The checked aliases arent really required, but its good for debugging to have them...
            $checkedAliases = [];
            $checkedAliases[$alias] = true;

            $query = $this->getRecursiveEntityDeletedAtQuery($query, $entityClass, $alias, $checkedAliases);
            $this->logger->info(
                "Read query \n\n{query}\n\n with aliases\n{aliases}",
                ['query' => $query->getDQL(), 'aliases' => json_encode($checkedAliases, JSON_PRETTY_PRINT)]
            );
        }

        if (null === $sortDirection || !\in_array(\strtoupper($sortDirection), ['ASC', 'DESC'])) {
            $sortDirection = 'DESC';
        }

        $this->dispatch(EasyAdminEvents::POST_LIST_QUERY_BUILDER, [
            'query_builder' => $query,
            'sort_field' => $sortField,
            'sort_direction' => $sortDirection,
        ]);

        return $this->get('easyadmin.paginator')->createOrmPaginator($query, $page, $maxPerPage);
    }

    /**
     * Generate a query that checks recursively for deleted parent (ManyToOne) entites (Group -> checks for deleted
     * Wave)
     *
     * @param QueryBuilder $query
     * @param $entityClass
     * @param string $entityAlias
     * @param $checkedAliases
     *
     * @return QueryBuilder
     */
    private function getRecursiveEntityDeletedAtQuery(
        QueryBuilder $query,
        $entityClass,
        string $entityAlias,
        &$checkedAliases
    ) {
        $meta = $this->em->getMetadataFactory()->getMetadataFor($entityClass);
        $names = $this->getAssociationFields($meta);

        foreach ($names as $name) {
            $mapping = $meta->getAssociationMapping($name);

            if ($mapping['isOwningSide']) {
                $alias = $this->getAlias($mapping['targetEntity']);
                $join = $entityAlias . '.' . $mapping['fieldName'];

                $query
                    ->leftJoin(
                        $join,
                        $alias
                    )->andWhere(
                        $alias . '.deletedAt IS NULL'
                    );

                $checkedAliases[$alias] = true;
                $this->getRecursiveEntityDeletedAtQuery($query, $mapping['targetEntity'], $alias, $checkedAliases);
            }
        }

        return $query;
    }

    /**
     * Converts the Class\Name\Of\A\Class to class_name_of_a_class
     *
     * @param string $className
     *
     * @return string
     */
    private function getAlias(string $className)
    {
        $class = str_replace('\\', '_', $className) . uniqid('___');

        return strtolower($class);
    }

    /**
     * @param ClassMetadata $meta
     *
     * @return array|string[]
     */
    private function getAssociationFields(ClassMetadata $meta)
    {
        $names = $meta->getAssociationNames();
        $map = [
            'createdBy' => true,
            'updatedBy' => true,
        ];
        $names = array_filter($names, function ($name) use ($map) {
            // remove all names that are in the map
            return !isset($map[$name]);
        });

        return $names;
    }
}

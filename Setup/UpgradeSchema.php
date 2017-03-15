<?php
/**
 * Copyright © 2015 Dexa. All rights reserved.
 * See LABELS.txt for license details.
 */
namespace Dexa\Warmer\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * UpgradeSchema
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * Upgrade
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $connection = $setup->getConnection();


        /**
         * Create table 'dexa_warmer_url'
         */
        $table = $connection
            ->newTable($setup->getTable('dexa_warmer_url'))
            ->addColumn(
                'url_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'GeoIp'
            )
            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false],
                'Creation Time'
            )
            ->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false],
                'Update Time'
            )
            ->addColumn(
                'visited_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false],
                'Last Visit Time'
            )
            ->addColumn(
                'warmed_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false],
                'Warmed Time'
            )
            ->addColumn(
                'url',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2083',
                ['nullable' => false],
                'Url'
            )
            ->addColumn(
                'hash',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '64',
                ['nullable' => false],
                'Hash'
            )
            ->addColumn(
                'path',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '256',
                ['nullable' => false],
                'Path'
            )
            ->addColumn(
                'cms_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_NUMERIC,
                null,
                ['unsigned' => true, 'nullable' => true],
                'CMS Id'
            )
            ->addColumn(
                'product_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_NUMERIC,
                null,
                ['unsigned' => true, 'nullable' => true],
                'CMS Id'
            )
            ->addColumn(
                'category_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_NUMERIC,
                null,
                ['unsigned' => true, 'nullable' => true],
                'CMS Id'
            )
            ->addColumn(
                'view_count',
                \Magento\Framework\DB\Ddl\Table::TYPE_NUMERIC,
                '',
                ['nullable' => false, 'unsigned' => true],
                'View Count'
            )
            ->addColumn(
                'last_http_code',
                \Magento\Framework\DB\Ddl\Table::TYPE_NUMERIC,
                '',
                ['nullable' => false, 'unsigned' => true, ],
                'Error Count'
            )
            ->addColumn(
                'ignore_url',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => 0],
                'Ignore_url'
            )
            ->addColumn(
                'order_position',
                \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => false, 'unsigned' => true, 'default' => 0],
                'Order Position'
            )
            ->addIndex(
                $connection->getIndexName(
                    $setup->getTable('dexa_warmer_url'),
                    ['hash'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['hash'],
                ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->setComment('Dexa Warmer Urls.');
        $connection->createTable($table);

        $setup->endSetup();
    }
}

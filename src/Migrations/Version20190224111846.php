<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190224111846 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE item_template (id INT AUTO_INCREMENT NOT NULL, entry INT NOT NULL, class INT NOT NULL, subclass INT NOT NULL, name VARCHAR(255) NOT NULL, displayid INT NOT NULL, quality INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP INDEX idx_guild_key ON config');
        $this->addSql('CREATE INDEX idx_guild_key ON config (guild_id, config_key)');
        $this->addSql('DROP INDEX idx_items_item_name ON items');
        $this->addSql('CREATE INDEX idx_items_item_name ON items (item_name)');
        $this->addSql('DROP INDEX idx_loot_guild_id_user_id_item_name_insert_ts ON loot_list');
        $this->addSql('CREATE INDEX idx_loot_guild_id_user_id_item_name_insert_ts ON loot_list (guild_id, member_id, item_name, insert_ts)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE item_template');
        $this->addSql('DROP INDEX idx_guild_key ON config');
        $this->addSql('CREATE INDEX idx_guild_key ON config (guild_id(191), config_key(191))');
        $this->addSql('DROP INDEX idx_items_item_name ON items');
        $this->addSql('CREATE INDEX idx_items_item_name ON items (item_name(191))');
        $this->addSql('DROP INDEX idx_loot_guild_id_user_id_item_name_insert_ts ON loot_list');
        $this->addSql('CREATE INDEX idx_loot_guild_id_user_id_item_name_insert_ts ON loot_list (guild_id(191), member_id(191), item_name(191), insert_ts)');
    }
}

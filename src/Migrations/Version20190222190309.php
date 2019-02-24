<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190222190309 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE INDEX idx_guild_key ON config (guild_id, config_key)');
        $this->addSql('DROP INDEX idx_loot_guild_id_user_id_item_name_insert_ts ON loot_list');
        $this->addSql('CREATE INDEX idx_loot_guild_id_user_id_item_name_insert_ts ON loot_list (guild_id, member_id, item_name, insert_ts)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX idx_guild_key ON config');
        $this->addSql('DROP INDEX idx_loot_guild_id_user_id_item_name_insert_ts ON loot_list');
        $this->addSql('CREATE INDEX idx_loot_guild_id_user_id_item_name_insert_ts ON loot_list (guild_id(191), member_id(191), item_name(191), insert_ts)');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240514122628 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE building ADD gls_strength SMALLINT DEFAULT NULL, ADD gls_note SMALLINT DEFAULT NULL, ADD gls_created_at DATETIME DEFAULT NULL, ADD gls_updated_at DATETIME DEFAULT NULL, DROP strength, DROP note, DROP created_at, DROP updated_at, CHANGE name gls_name VARCHAR(50) NOT NULL, CHANGE slug gls_slug VARCHAR(20) NOT NULL, CHANGE caste gls_caste VARCHAR(40) DEFAULT NULL, CHANGE image gls_image VARCHAR(100) DEFAULT NULL, CHANGE identifier gls_identifier VARCHAR(40) NOT NULL');
        $this->addSql('ALTER TABLE `character` ADD gls_caste VARCHAR(20) DEFAULT NULL, ADD gls_knowledge VARCHAR(20) DEFAULT NULL, ADD gls_slug VARCHAR(20) NOT NULL, ADD gls_kind VARCHAR(20) NOT NULL, DROP caste, DROP knowledge, DROP slug, DROP kind, CHANGE surname gls_surname VARCHAR(50) NOT NULL, CHANGE strength gls_strength SMALLINT DEFAULT NULL, CHANGE image gls_image VARCHAR(50) DEFAULT NULL, CHANGE identifier gls_identifier VARCHAR(40) NOT NULL, CHANGE modification gls_modification DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `building` ADD strength SMALLINT DEFAULT NULL, ADD note SMALLINT DEFAULT NULL, ADD created_at DATETIME DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL, DROP gls_strength, DROP gls_note, DROP gls_created_at, DROP gls_updated_at, CHANGE gls_name name VARCHAR(50) NOT NULL, CHANGE gls_slug slug VARCHAR(20) NOT NULL, CHANGE gls_caste caste VARCHAR(40) DEFAULT NULL, CHANGE gls_image image VARCHAR(100) DEFAULT NULL, CHANGE gls_identifier identifier VARCHAR(40) NOT NULL');
        $this->addSql('ALTER TABLE `character` ADD caste VARCHAR(20) DEFAULT NULL, ADD knowledge VARCHAR(20) DEFAULT NULL, ADD slug VARCHAR(20) NOT NULL, ADD kind VARCHAR(20) NOT NULL, DROP gls_caste, DROP gls_knowledge, DROP gls_slug, DROP gls_kind, CHANGE gls_surname surname VARCHAR(50) NOT NULL, CHANGE gls_strength strength SMALLINT DEFAULT NULL, CHANGE gls_image image VARCHAR(50) DEFAULT NULL, CHANGE gls_identifier identifier VARCHAR(40) NOT NULL, CHANGE gls_modification modification DATETIME DEFAULT NULL');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220626083411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FD44F05E5');
        $this->addSql('DROP INDEX UNIQ_C53D045FD44F05E5 ON image');
        $this->addSql('ALTER TABLE image DROP images_id');
        $this->addSql('ALTER TABLE product ADD images_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADD44F05E5 FOREIGN KEY (images_id) REFERENCES image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04ADD44F05E5 ON product (images_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image ADD images_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FD44F05E5 FOREIGN KEY (images_id) REFERENCES product (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C53D045FD44F05E5 ON image (images_id)');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADD44F05E5');
        $this->addSql('DROP INDEX UNIQ_D34A04ADD44F05E5 ON product');
        $this->addSql('ALTER TABLE product DROP images_id');
    }
}

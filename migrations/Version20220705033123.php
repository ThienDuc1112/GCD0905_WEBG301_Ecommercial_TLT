<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220705033123 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_detail (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, order_ref_id INT DEFAULT NULL, quantity INT NOT NULL, INDEX IDX_ED896F464584665A (product_id), INDEX IDX_ED896F46E238517C (order_ref_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_detail ADD CONSTRAINT FK_ED896F464584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE order_detail ADD CONSTRAINT FK_ED896F46E238517C FOREIGN KEY (order_ref_id) REFERENCES `order` (id)');
        $this->addSql('DROP TABLE detail_order');
        $this->addSql('ALTER TABLE `order` ADD status VARCHAR(255) NOT NULL, DROP delivery_address, DROP order_phone, DROP name_customer, DROP order_status, CHANGE order_date created_at DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE detail_order (id INT AUTO_INCREMENT NOT NULL, products_id INT NOT NULL, orders_id INT DEFAULT NULL, quantity INT NOT NULL, INDEX IDX_88D958C1CFFE9AD6 (orders_id), INDEX IDX_88D958C16C8A81A9 (products_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE detail_order ADD CONSTRAINT FK_88D958C1CFFE9AD6 FOREIGN KEY (orders_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE detail_order ADD CONSTRAINT FK_88D958C16C8A81A9 FOREIGN KEY (products_id) REFERENCES product (id)');
        $this->addSql('DROP TABLE order_detail');
        $this->addSql('ALTER TABLE `order` ADD order_phone VARCHAR(255) NOT NULL, ADD name_customer VARCHAR(255) NOT NULL, ADD order_status VARCHAR(20) NOT NULL, CHANGE status delivery_address VARCHAR(255) NOT NULL, CHANGE created_at order_date DATETIME NOT NULL');
    }
}

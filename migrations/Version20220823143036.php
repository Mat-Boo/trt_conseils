<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220823143036 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE candidature (id INT AUTO_INCREMENT NOT NULL, candidate_id_id INT NOT NULL, job_offer_id_id INT NOT NULL, is_approved TINYINT(1) NOT NULL, INDEX IDX_E33BD3B847A475AB (candidate_id_id), INDEX IDX_E33BD3B89F05F8F7 (job_offer_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B847A475AB FOREIGN KEY (candidate_id_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B89F05F8F7 FOREIGN KEY (job_offer_id_id) REFERENCES job_offer (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B847A475AB');
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B89F05F8F7');
        $this->addSql('DROP TABLE candidature');
    }
}

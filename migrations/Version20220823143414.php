<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220823143414 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B847A475AB');
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B89F05F8F7');
        $this->addSql('DROP INDEX IDX_E33BD3B89F05F8F7 ON candidature');
        $this->addSql('DROP INDEX IDX_E33BD3B847A475AB ON candidature');
        $this->addSql('ALTER TABLE candidature ADD candidate_id INT NOT NULL, ADD job_offer_id INT NOT NULL, DROP candidate_id_id, DROP job_offer_id_id');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B891BD8781 FOREIGN KEY (candidate_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B83481D195 FOREIGN KEY (job_offer_id) REFERENCES job_offer (id)');
        $this->addSql('CREATE INDEX IDX_E33BD3B891BD8781 ON candidature (candidate_id)');
        $this->addSql('CREATE INDEX IDX_E33BD3B83481D195 ON candidature (job_offer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B891BD8781');
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B83481D195');
        $this->addSql('DROP INDEX IDX_E33BD3B891BD8781 ON candidature');
        $this->addSql('DROP INDEX IDX_E33BD3B83481D195 ON candidature');
        $this->addSql('ALTER TABLE candidature ADD candidate_id_id INT NOT NULL, ADD job_offer_id_id INT NOT NULL, DROP candidate_id, DROP job_offer_id');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B847A475AB FOREIGN KEY (candidate_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B89F05F8F7 FOREIGN KEY (job_offer_id_id) REFERENCES job_offer (id)');
        $this->addSql('CREATE INDEX IDX_E33BD3B89F05F8F7 ON candidature (job_offer_id_id)');
        $this->addSql('CREATE INDEX IDX_E33BD3B847A475AB ON candidature (candidate_id_id)');
    }
}

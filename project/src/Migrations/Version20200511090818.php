<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200511090818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change `pottery`.`inclusions_type_id` to `pottery`.`inclusions_type_id`';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE pottery DROP FOREIGN KEY FK_1A6518399053E54B;');
        $this->addSql('DROP INDEX IDX_1A6518399053E54B ON pottery;');
        $this->addSql('ALTER TABLE pottery CHANGE inclusions_type_id inclusions_type INT DEFAULT NULL;');
        $this->addSql('ALTER TABLE pottery ADD CONSTRAINT FK_1A651839105EC1E1 FOREIGN KEY (inclusions_type) REFERENCES voc__p__inclusions_type (id) ON DELETE RESTRICT;');
        $this->addSql('CREATE INDEX IDX_1A651839105EC1E1 ON pottery (inclusions_type);');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE pottery DROP FOREIGN KEY FK_1A651839105EC1E1;');
        $this->addSql('DROP INDEX IDX_1A651839105EC1E1 ON pottery;');
        $this->addSql('ALTER TABLE pottery CHANGE inclusions_type inclusions_type_id INT DEFAULT NULL;');
        $this->addSql('ALTER TABLE pottery ADD CONSTRAINT FK_1A6518399053E54B FOREIGN KEY (inclusions_type_id) REFERENCES voc__p__inclusions_type (id) ON DELETE RESTRICT;');
        $this->addSql('CREATE INDEX FK_1A6518399053E54B ON pottery (inclusions_type_id);');
    }
}

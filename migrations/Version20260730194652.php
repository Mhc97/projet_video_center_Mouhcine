<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260730194652 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Création des tables user et videos';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user (
            id INT AUTO_INCREMENT NOT NULL, 
            firstname VARCHAR(255) NOT NULL, 
            lastname VARCHAR(255) NOT NULL, 
            is_verified TINYINT NOT NULL, 
            email VARCHAR(180) NOT NULL, 
            roles JSON NOT NULL, 
            password VARCHAR(255) NOT NULL, 
            created_at DATETIME NOT NULL, 
            updated_at DATETIME NOT NULL, 
            UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4');

        $this->addSql('CREATE TABLE videos (
            id INT AUTO_INCREMENT NOT NULL, 
            title VARCHAR(50) NOT NULL, 
            video_link VARCHAR(500) NOT NULL, 
            description LONGTEXT NOT NULL, 
            premium_video TINYINT NOT NULL, 
            user_id INT NOT NULL, 
            created_at DATETIME NOT NULL, 
            updated_at DATETIME NOT NULL, 
            INDEX IDX_VIDEO_USER (user_id), 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4');

        $this->addSql('ALTER TABLE videos ADD CONSTRAINT FK_VIDEO_USER FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE videos');
        $this->addSql('DROP TABLE user');
    }
}
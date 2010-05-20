CREATE TABLE image (id BIGINT AUTO_INCREMENT, channel_id BIGINT NOT NULL, host VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, filename VARCHAR(255) NOT NULL, sizes TEXT NOT NULL, source VARCHAR(255) NOT NULL, source_url VARCHAR(255), permission VARCHAR(255) NOT NULL, description LONGTEXT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX channel_id_idx (channel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = INNODB;
ALTER TABLE image ADD CONSTRAINT image_channel_id_channel_id FOREIGN KEY (channel_id) REFERENCES channel(id);
ALTER TABLE title ADD CONSTRAINT title_image_id_image_id FOREIGN KEY (image_id) REFERENCES image(id);

-- //@UNDO

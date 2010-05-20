CREATE TABLE vertical (id BIGINT AUTO_INCREMENT, title VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL UNIQUE, abbv VARCHAR(5) NOT NULL, rank INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = INNODB;

insert into vertical (title, url, abbv, rank) values ('Money & Business', '/business.html', 'mny', 6);
insert into vertical (title, url, abbv, rank) values ('Entertainment & Hobbies', '/entertainment.html', 'ent', 2);
insert into vertical (title, url, abbv, rank) values ('Family & Lifestyle', '/family.html', 'fam', 3);
insert into vertical (title, url, abbv, rank) values ('Beauty & Health', '/health.html', 'hea', 1);
insert into vertical (title, url, abbv, rank) values ('Home, Garden & Events', '/home-garden.html', 'home', 4);
insert into vertical (title, url, abbv, rank) values ('Internet & Technology', '/internet.html', 'tech', 5);
insert into vertical (title, url, abbv, rank) values ('Style & Shopping', '/style.html', 'sty', 7);
insert into vertical (title, url, abbv, rank) values ('Travel & Vacations', '/travel.html', 'vac', 8);

CREATE TABLE channel (id BIGINT AUTO_INCREMENT, vertical_id BIGINT NOT NULL, title VARCHAR(255) NOT NULL, short_title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL UNIQUE, settings TEXT NOT NULL, INDEX vertical_id_idx (vertical_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = INNODB;
CREATE TABLE channel_details (id BIGINT AUTO_INCREMENT, channel_id BIGINT NOT NULL, highlight_title_id BIGINT NOT NULL, highlight_content LONGTEXT NOT NULL, popular1_title_id BIGINT NOT NULL, popular1_content LONGTEXT NOT NULL, popular2_title_id BIGINT NOT NULL, popular2_content LONGTEXT NOT NULL, about_title_id BIGINT NOT NULL, INDEX channel_id_idx (channel_id), INDEX highlight_title_id_idx (highlight_title_id), INDEX popular1_title_id_idx (popular1_title_id), INDEX popular2_title_id_idx (popular2_title_id), INDEX about_title_id_idx (about_title_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = INNODB;
alter table channel add content longtext;

insert into channel (vertical_id, title, short_title, slug, settings)
select v.id, c.channel_name, c.channel_name, replace(replace(c.description, 'http://', ''), '.lovetoknow.com', ''), 'a:0:{}'
from `OLD_ltk_master`.channels c
join `OLD_ltk_master`.CHANNEL_CATEGORY cc on c.category_id = cc.category_id
join vertical v on cc.category_name = v.title
where c.active = 1;

-- any vertical name changes need to go after the above query because it depends on the vertical names exactly matching what is in the old DB
update vertical set title = 'Parenting & Lifestyle' where title = 'Family & Lifestyle';
update vertical set title = 'Health & Fitness' where title = 'Beauty & Health';

update channel set title='Recovery', short_title='Recovery' where slug='addiction';
update channel set title='Bedding & Linens' where slug='bedding';
update channel set title='Board Games', short_title='Board Games' where slug='boardgames';
update channel set title='Buyer''s Guide' where slug='buy';
update channel set title='Cake Decorating', short_title='Cake Decorating' where slug='cake-decorating';
update channel set title='Cell Phones', short_title='Cell Phones' where slug='cellphones';
update channel set title='Children''s Books', short_title='Children''s Books' where slug='childrens-books';
update channel set title='Children''s Clothing', short_title='Children''s Clothing' where slug='childrens-clothing';
update channel set title='Credit Cards', short_title='Credit Cards' where slug='creditcards';
update channel set title='Dating & Relationships' where slug='dating';
update channel set title='Death and Dying', short_title='Death and Dying' where slug='dying';
update channel set title='Engagement Rings', short_title='Engagement Rings' where slug='engagementrings';
update channel set title='Family' where slug='family';
update channel set title='Feng Shui', short_title='Feng Shui' where slug='feng-shui';
update channel set title='Freelance Writing', short_title='Freelance Writing' where slug='freelance-writing';
update channel set title='Gluten Free' where slug='gluten';
update channel set title='Green Living', short_title='Green Living' where slug='greenliving';
update channel set title='Home School', short_title='Homeschool' where slug='home-school';
update channel set title='Home Improvement', short_title='Home Improvement' where slug='homeimprovement';
update channel set title='Interior Design', short_title='Interior Design' where slug='interiordesign';
update channel set title='Jobs & Careers', short_title='Jobs and Careers' where slug='jobs';
update channel set title='Men''s Fashion', short_title='Men''s Fashion' where slug='mens-fashion';
update channel set title='Plus Size', short_title='Plus Size' where slug='plussize';
update channel set title='Reality TV', short_title='Reality TV' where slug='reality-tv';
update channel set title='San Francisco', short_title='San Francicso' where slug='sanfrancisco';
update channel set title='Science Fiction & Fantasy', short_title='Science Fiction' where slug='sci-fi';
update channel set title='Science Experiments', short_title='Science Experiments' where slug='science-experiments';
update channel set title='Senior Citizens' where slug='seniors';
update channel set title='Sleep Disorders' where slug='sleep';
update channel set title='Soap Operas', short_title='Soap Operas' where slug='soap-operas';
update channel set title='Social Networking', short_title='Social Networking' where slug='socialnetworking';
update channel set title='Stress Management', short_title='Stress Management ' where slug='stress';
update channel set title='Theme Parks', short_title='Theme Parks' where slug='themeparks';
update channel set title='Video Games', short_title='Video Games' where slug='videogames';
update channel set title='Web Design', short_title='Web Design' where slug='web-design';
update channel set title='Women''s Fashion', short_title='Women''s Fashion' where slug='womens-fashion';

-- NOTE: need to make sure the title url field has COLLATE utf8_bin so it will be case-sensitive (some old wiki pages differ only in case, if we canpurge these then we can get rid of this)

CREATE TABLE category_version (id BIGINT, channel_id BIGINT NOT NULL, title_id BIGINT, lft INT, rgt INT, level SMALLINT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_by INT, updated_by INT, version BIGINT, PRIMARY KEY(id, version)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = INNODB;
CREATE TABLE category (id BIGINT AUTO_INCREMENT, channel_id BIGINT NOT NULL, title_id BIGINT, lft INT, rgt INT, level SMALLINT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_by INT, updated_by INT, version BIGINT, UNIQUE INDEX title_idx (title_id, channel_id), INDEX title_id_idx (title_id), INDEX channel_id_idx (channel_id), INDEX created_by_idx (created_by), INDEX updated_by_idx (updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = INNODB;
CREATE TABLE title_version (id BIGINT, channel_id BIGINT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255), url VARCHAR(255) NOT NULL, type ENUM('Category', 'Article', 'Slideshow', 'Quiz'), status ENUM('Queued', 'Available', 'Claimed', 'InProgress', 'Submitted', 'Rejected', 'Approved', 'Published', 'Deleted') DEFAULT 'Queued' NOT NULL, published_content_version BIGINT, notes VARCHAR(255), available_on DATE, author_user_id INT, image_id BIGINT, image_link VARCHAR(255), image_text VARCHAR(255), image_caption VARCHAR(255), image_width BIGINT DEFAULT 300, image_thumbnail TINYINT(1) DEFAULT '0', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_by INT, updated_by INT, version BIGINT, PRIMARY KEY(id, version)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = INNODB;
CREATE TABLE title (id BIGINT AUTO_INCREMENT, channel_id BIGINT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255), url VARCHAR(255) NOT NULL, type ENUM('Category', 'Article', 'Slideshow', 'Quiz'), status ENUM('Queued', 'Available', 'Claimed', 'InProgress', 'Submitted', 'Rejected', 'Approved', 'Published', 'Deleted') DEFAULT 'Queued' NOT NULL, published_content_version BIGINT, notes VARCHAR(255), available_on DATE, author_user_id INT, image_id BIGINT, image_link VARCHAR(255), image_text VARCHAR(255), image_caption VARCHAR(255), image_width BIGINT DEFAULT 300, image_thumbnail TINYINT(1) DEFAULT '0', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_by INT, updated_by INT, version BIGINT, UNIQUE INDEX title_idx (id, channel_id), UNIQUE INDEX url_idx (channel_id, url), INDEX default_sort_idx (title ASC), INDEX channel_id_idx (channel_id), INDEX author_user_id_idx (author_user_id), INDEX image_id_idx (image_id), INDEX created_by_idx (created_by), INDEX updated_by_idx (updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = INNODB;
CREATE TABLE title_category_version (title_id BIGINT, category_id BIGINT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_by INT, updated_by INT, version BIGINT, PRIMARY KEY(title_id, category_id, version)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = INNODB;
CREATE TABLE title_category (title_id BIGINT, category_id BIGINT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_by INT, updated_by INT, version BIGINT, INDEX created_by_idx (created_by), INDEX updated_by_idx (updated_by), PRIMARY KEY(title_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = INNODB;
CREATE TABLE feature_article (id BIGINT AUTO_INCREMENT, channel_id BIGINT, category_id BIGINT NOT NULL, article_id BIGINT NOT NULL, position ENUM('Above', 'Below', 'Right'), INDEX channel_id_idx (channel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = INNODB;

ALTER TABLE channel ADD CONSTRAINT channel_vertical_id_vertical_id FOREIGN KEY (vertical_id) REFERENCES vertical(id);
ALTER TABLE channel_details ADD CONSTRAINT channel_details_popular2_title_id_title_id FOREIGN KEY (popular2_title_id) REFERENCES title(id);
ALTER TABLE channel_details ADD CONSTRAINT channel_details_popular1_title_id_title_id FOREIGN KEY (popular1_title_id) REFERENCES title(id);
ALTER TABLE channel_details ADD CONSTRAINT channel_details_highlight_title_id_title_id FOREIGN KEY (highlight_title_id) REFERENCES title(id);
ALTER TABLE channel_details ADD CONSTRAINT channel_details_channel_id_channel_id FOREIGN KEY (channel_id) REFERENCES channel(id);
ALTER TABLE channel_details ADD CONSTRAINT channel_details_about_title_id_title_id FOREIGN KEY (about_title_id) REFERENCES title(id);

ALTER TABLE category_version ADD CONSTRAINT category_version_id_category_id FOREIGN KEY (id) REFERENCES category(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE category ADD CONSTRAINT category_updated_by_sf_guard_user_id FOREIGN KEY (updated_by) REFERENCES sf_guard_user(id);
ALTER TABLE category ADD CONSTRAINT category_title_id_title_id FOREIGN KEY (title_id, channel_id) REFERENCES title(id, channel_id);
ALTER TABLE category ADD CONSTRAINT category_created_by_sf_guard_user_id FOREIGN KEY (created_by) REFERENCES sf_guard_user(id);
ALTER TABLE category ADD CONSTRAINT category_channel_id_channel_id FOREIGN KEY (channel_id) REFERENCES channel(id);
ALTER TABLE title_version ADD CONSTRAINT title_version_id_title_id FOREIGN KEY (id) REFERENCES title(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE title ADD CONSTRAINT title_updated_by_sf_guard_user_id FOREIGN KEY (updated_by) REFERENCES sf_guard_user(id);

ALTER TABLE title ADD CONSTRAINT title_created_by_sf_guard_user_id FOREIGN KEY (created_by) REFERENCES sf_guard_user(id);
ALTER TABLE title ADD CONSTRAINT title_channel_id_channel_id FOREIGN KEY (channel_id) REFERENCES channel(id);
ALTER TABLE title ADD CONSTRAINT title_author_user_id_sf_guard_user_id FOREIGN KEY (author_user_id) REFERENCES sf_guard_user(id);
ALTER TABLE title_category_version ADD CONSTRAINT title_category_version_title_id_title_category_title_id FOREIGN KEY (title_id) REFERENCES title_category(title_id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE title_category ADD CONSTRAINT title_category_updated_by_sf_guard_user_id FOREIGN KEY (updated_by) REFERENCES sf_guard_user(id);
ALTER TABLE title_category ADD CONSTRAINT title_category_title_id_title_id FOREIGN KEY (title_id) REFERENCES title(id) ON DELETE CASCADE;
ALTER TABLE title_category ADD CONSTRAINT title_category_created_by_sf_guard_user_id FOREIGN KEY (created_by) REFERENCES sf_guard_user(id);
ALTER TABLE title_category ADD CONSTRAINT title_category_category_id_category_id FOREIGN KEY (category_id) REFERENCES category(id);
ALTER TABLE feature_article ADD CONSTRAINT feature_article_channel_id_channel_id FOREIGN KEY (channel_id) REFERENCES channel(id);
ALTER TABLE feature_article ADD CONSTRAINT feature_article_article_id_title_id FOREIGN KEY (article_id) REFERENCES title(id);

-- //@UNDO

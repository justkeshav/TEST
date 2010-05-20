
use `ltk`;

SET @PK := (SELECT COUNT(*) FROM `sf_guard_user`) + 1;

-- ltkeditor (Group Editor)

INSERT INTO `sf_guard_user` (`id`, `username`, `algorithm`, `salt`, `password`, `is_active`, `is_super_admin`, `last_login`, `created_at`, `updated_at`) VALUES
(@PK, 'ltkeditor', 'sha1', 'c73bf2334350c5b34ab34b5dcef28dee', '7f2fb9dd97fd13f3e9cd096fb26aee5f8828f2f9', 1, 0, NULL, NOW(), NOW()); -- password is ltk123

INSERT INTO `sf_guard_user_group` (`user_id`, `group_id`, `created_at`, `updated_at`) VALUES
(@PK, 1, NOW(), NOW());

INSERT INTO `sf_guard_user_permission` (`user_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(@PK, 1, NOW(), NOW());

-- ltkwriter (Premium Writer)

INSERT INTO `sf_guard_user` (`id`, `username`, `algorithm`, `salt`, `password`, `is_active`, `is_super_admin`, `last_login`, `created_at`, `updated_at`) VALUES
(@PK + 1, 'ltkwriter', 'sha1', 'c73bf2334350c5b34ab34b5dcef28dee', '7f2fb9dd97fd13f3e9cd096fb26aee5f8828f2f9', 1, 0, NULL, NOW(), NOW()); -- password is ltk123

INSERT INTO `sf_guard_user_group` (`user_id`, `group_id`, `created_at`, `updated_at`) VALUES
(@PK + 1, 2, NOW(), NOW());

INSERT INTO `sf_guard_user_permission` (`user_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(@PK + 1, 3, NOW(), NOW());

-- ltktitler (Titler)

INSERT INTO `sf_guard_user` (`id`, `username`, `algorithm`, `salt`, `password`, `is_active`, `is_super_admin`, `last_login`, `created_at`, `updated_at`) VALUES
(@PK + 2, 'ltktitler', 'sha1', 'c73bf2334350c5b34ab34b5dcef28dee', '7f2fb9dd97fd13f3e9cd096fb26aee5f8828f2f9', 1, 0, NULL, NOW(), NOW()); -- password is ltk123

INSERT INTO `sf_guard_user_group` (`user_id`, `group_id`, `created_at`, `updated_at`) VALUES
(@PK + 2, 3, NOW(), NOW());

INSERT INTO `sf_guard_user_permission` (`user_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(@PK + 2, 6, NOW(), NOW());

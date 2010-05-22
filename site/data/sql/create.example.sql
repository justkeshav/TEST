create database if not exists `${db.name}`;

use `${db.name}`;

-- needed for dbdeploy delta tracking
create table if not exists changelog
(
  change_number bigint not null,
  delta_set varchar(10) not null,
  start_dt timestamp not null,
  complete_dt timestamp null,
  applied_by varchar(100) not null,
  description varchar(500) not null,
  primary key (change_number, delta_set)
);

-- needed for release tracking
create table if not exists releaselog
(
  build bigint not null,
  version varchar(10) not null,
  complete_dt timestamp not null,
  primary key (build)
);

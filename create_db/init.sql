CREATE DATABASE IF NOT EXISTS ludus_service_team;

CREATE TABLE users (
  id bigint unsigned not null auto_increment primary key,
  name varchar(255) not null,
  email varchar(255) not null unique,
  email_verified_at timestamp null,
  password varchar(255) not null,
  remember_token varchar(100) null,
  created_at timestamp null,
  updated_at timestamp null
);

CREATE TABLE personal_access_tokens (
  id bigint unsigned not null auto_increment primary key,
  tokenable_id bigint unsigned not null,
  tokenable_type varchar(255) not null,
  name varchar(255) not null,
  token varchar(64) not null unique,
  abilities text null,
  last_used_at timestamp null,
  expires_at timestamp null,
  created_at timestamp null,
  updated_at timestamp null
);

CREATE TABLE coach (
  id bigint unsigned not null auto_increment primary key,
  name varchar(255) not null,
  dob date null,
  created_at timestamp null,
  updated_at timestamp null,
  deleted_at timestamp null
);

CREATE TABLE club (
  id bigint unsigned not null auto_increment primary key,
  coach_id bigint unsigned not null,
  popular_name varchar(255) null,
  nickname_club varchar(255) null,
  name_club varchar(255) not null,
  acronym_club varchar(255) null,
  shield_club varchar(255) null,
  created_at timestamp null,
  updated_at timestamp null,
  deleted_at timestamp null
);
ALTER TABLE club
  ADD CONSTRAINT club_coach_id_foreign FOREIGN KEY (coach_id) REFERENCES coach (id);

CREATE TABLE password_reset_tokens (
  email varchar(255) not null primary key,
  token varchar(255) not null,
  created_at timestamp null
);

CREATE TABLE failed_jobs (
  id bigint unsigned not null auto_increment primary key,
  uuid varchar(255) not null unique,
  connection text not null,
  queue text not null,
  payload longtext not null,
  exception longtext not null,
  failed_at timestamp default current_timestamp()
);
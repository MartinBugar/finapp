ALTER TABLE posts
    ADD COLUMN userID bigint unsigned NOT NULL;

ALTER TABLE posts
    add constraint posts_userid_fk
        FOREIGN KEY (userID) REFERENCES users (id)

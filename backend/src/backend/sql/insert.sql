INSERT INTO user (id, firstName, lastName, username, dob, joinDate, permissions)
VALUES (
        'f568d4d2-28b5-4ad5-956c-50bd3930bbe5',
        'Admin',
        'Lastname',
        'admin',
        1100605477,
        1639655077,
        2
);
# pwd: testing123AA
INSERT INTO credential (userId, email, password, token)
VALUES (
        'f568d4d2-28b5-4ad5-956c-50bd3930bbe5',
        'admin@tlt.com',
        '$2y$10$GuMFgzesvuuyT1deDhdayONl2ff0Kara0szw.89VFgXONXdd1JrIO',
        'cd5d9483f707aef56760db463419bef2970b5cac82fb8f24f5c60d7e731b87bf'
);

INSERT INTO user_prefs (userId, theme)
VALUES (
        'f568d4d2-28b5-4ad5-956c-50bd3930bbe5',
        'dark'
);

INSERT INTO post (id, content, title, authorId, createdAt, editedAt)
VALUES (
        '6385c5e2-c9ab-4c43-9548-bf8816b6bad3',
        'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
        'Sample post title',
        'f568d4d2-28b5-4ad5-956c-50bd3930bbe5',
        1639655077,
        null
);

INSERT INTO comment (id, authorId, postId, content, createdAt, editedAt)
VALUES (
        '7a80db4c-fca1-4224-a3ae-b3b6c85d987c',
        'f568d4d2-28b5-4ad5-956c-50bd3930bbe5',
        '6385c5e2-c9ab-4c43-9548-bf8816b6bad3',
        'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo',
        1639655077,
        null
);

INSERT INTO comment (id, authorId, postId, content, createdAt, editedAt)
VALUES (
        'bba583de-985d-45be-88bf-09d24337b1f1',
        'f568d4d2-28b5-4ad5-956c-50bd3930bbe5',
        '6385c5e2-c9ab-4c43-9548-bf8816b6bad3',
        'Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet.',
        1639655077,
        null
);

INSERT INTO comment (id, authorId, postId, content, createdAt, editedAt)
VALUES (
        'e9487660-f493-4508-a12f-fd9c5bf142ed',
        'f568d4d2-28b5-4ad5-956c-50bd3930bbe5',
        '6385c5e2-c9ab-4c43-9548-bf8816b6bad3',
        'Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur',
        1639655077,
        null
);
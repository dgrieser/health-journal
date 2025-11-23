# Tech
PHP + SQLite on the backend and mostly server‑rendered HTML with a bit of vanilla JS is a very good fit for this.

## Backend: PHP + SQLite

- Use PHP with either `SQLite3` or PDO SQLite (`pdo_sqlite`) extension; both are standard and well‑documented for lightweight CRUD apps.
- A simple connection looks like `new PDO('sqlite:/path/to/journal.db')`, which will create the file if it does not exist.
- Table: e.g. `entries(id INTEGER PRIMARY KEY, date TEXT, mood INTEGER, symptoms TEXT, notes TEXT, created_at TEXT)`; you can create it once in a setup script or lazily at first request.
- Use prepared statements for inserts/updates so you are safe from injections and get decent performance: `INSERT INTO entries (...) VALUES (:date, :mood, ...)`.

You can structure it as one `index.php` that routes by `$_GET['action']` (`list`, `new`, `save`, `view`) or as small separate scripts (`list.php`, `edit.php`, etc.), whichever feels simpler.

## Frontend: HTML first, light JS

- List screen: a PHP script queries `SELECT * FROM entries ORDER BY date DESC` and renders a table or list of days; add a small filter form (date from/to, text search) that submits with GET and adds `WHERE` conditions.
- Entry screen: one HTML `<form method="post" action="save.php">` with date, select for mood, checkboxes, textarea, etc.; on submit, PHP validates and inserts into SQLite, then redirects back to the list.

JavaScript can stay minimal:

- Optional: use `fetch()` + `FormData` to submit the form asynchronously and then reload or update the list, which is a few lines of vanilla JS.
- Optional: add some client‑side niceties (live character count, simple validation) but keep all business logic and storage on the server.

## Why this is simple and suitable

- No framework needed: plain PHP files + SQLite DB file are enough, and deployment is just copying PHP files and the `.db` onto a PHP‑capable webserver.
- SQLite gives you easy querying and backups (just copy the file) and is frequently recommended for small, single‑user or low‑traffic web apps.
- PHP + vanilla JS is widely supported, has very low tooling overhead, and matches your “lightweight and simple” requirement well.

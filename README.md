# health-journal

Simple PHP + SQLite journaling app for the Parkinson symptom form in `journal.md` (form text in German, variables/schema in English as requested).

## Initial Setup
- Requirements: PHP 8+ with `pdo_sqlite` (or `sqlite3`) enabled; optional `sqlite3` CLI for inspecting the DB.
- Clone the repo and `cd` into it, then create the SQLite location: `mkdir -p db`.
- Start the dev server from the repo root: `php -S 127.0.0.1:8000 -t public`.
- Open http://127.0.0.1:8000 in a browser. On the first request the DB file `db/journal.db` is created automatically via `src/database.php`.

## Using the app
- Landing page (`/index.php`) lists stored entries.
- Click “Neuer Eintrag”, fill the German form, submit; you are redirected back to the list view.

## Files of interest
- `journal.md`: reference for the symptom protocol structure.
- `tech.md`: notes on architecture and schema choices.
- `public/`: server-rendered UI (`index.php`, `new_entry_form.php`, `style.css`).
- `src/database.php`: SQLite connection and table/view creation.

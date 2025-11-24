# Repository Guidelines

## Project Structure & Module Organization
- `public/`: UI entry points (`index.php` list/edit/delete, `new_entry_form.php` form, `style.css` styling). Keep server-rendered HTML first, with only small vanilla JS helpers.
- `src/database.php`: PDO SQLite connection, table/view creation, and schema drift handling via `add_column_if_missing`. Any new field must be added here and wired into the view if it should appear in weekly summaries.
- `journal.md`: canonical Parkinson symptom protocol; use it to keep field labels and ordering consistent. `tech.md` outlines architecture and schema rationale.
- `db/`: holds `journal.db` (git-ignored). Created automatically on first request.

## Build, Test, and Development Commands
- Run locally: `php -S 127.0.0.1:8000 -t public` from repo root (ensure `mkdir -p db` first). Open http://127.0.0.1:8000.
- PHP lint: `php -l public/*.php src/*.php`.
- Inspect schema: `sqlite3 db/journal.db ".schema daily_symptom_log"` (after first request).

## Coding Style & Naming Conventions
- PHP 8, 4-space indent, snake_case for DB columns, request keys, and PHP variables. Keep array order aligned with the form layout.
- Use prepared statements with named parameters (see `public/index.php`) and escape output (`htmlspecialchars`) for any echoed user input.
- When introducing a field: update `$allFields`, `$booleanFields`/`$arrayFields` as needed, the column map in `initialize_database()`, and form inputs/templates so saves/edits stay symmetric.
- Keep JS minimal and inlined near the form; avoid framework dependencies.

## Testing Guidelines
- No automated suite yet; rely on manual verification:
  - Create, edit, and delete an entry; confirm values persist and render correctly in the list.
  - Check multi-select/textarray fields save and reload (medication rows, symptom lists).
  - Validate new columns appear in SQLite (`.schema`) and that weekly view still builds.
- Run `php -l` before pushing to catch syntax errors.

## Commit & Pull Request Guidelines
- Follow conventional commits used here (`feat(scope): message`, `fix(scope): ...`). Mention the touched area (`form`, `ui`, `meds`, etc.).
- PRs should include: short description of changes, before/after screenshots for UI tweaks (list and form), notes on schema changes/migrations, and manual test notes (commands run + behaviors observed).
- Keep diffs focused; separate cosmetic refactors from schema-changing work where possible.

## Data & Security Notes
- SQLite file contains health data; keep `db/journal.db` out of commits (already git-ignored) and avoid sharing it in PRs.
- Default server is for local use only. If deploying, ensure HTTPS and restrict access; rotate/clear the DB before sharing debug archives.

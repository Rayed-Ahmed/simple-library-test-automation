# Simple Online Library System — Selenium QA Automation

A browser-based QA automation suite built with **Python, Selenium 4, and pytest** for the **Simple Online Library System** included in this repository. The application under test uses PHP and MySQL.

## What is tested

- Login page rendering and successful administrator login
- Invalid-password handling
- Logout and session invalidation
- Student registration with a unique email
- Duplicate-email registration validation
- Student access to the book catalog
- Borrow request for an available book
- Role-based restriction of the administrator dashboard

The tests use the application's actual routes and form fields. The unrelated restaurant/order scripts and selectors from the previous project have been removed.

## 1. Run the PHP application with Docker (recommended)

**Prerequisite:** Docker Desktop, or Docker Engine with the Docker Compose plugin.

From this `selenium/` directory, run:

```bash
docker compose up --build -d
docker compose ps
```

Open the application at:

`http://localhost:8080/simple-online-library-system/login.php`

The PHP application and MySQL run in separate containers. On the first start, Docker imports `simple-online-library-system/l_db.sql` automatically. Database data is persisted in the `library_db_data` Docker volume.

The application's database connection is configured through environment variables in `compose.yaml`; you do not need to edit the PHP database credentials for Docker. The default MySQL root password in this Compose file is `rootpassword`. This is intended for local development only; change it before using the setup beyond your local machine.

To stop the containers while preserving database data:

```bash
docker compose down
```

To completely reset the database and re-import the SQL file:

```bash
docker compose down -v
docker compose up --build -d
```

**Warning:** `docker compose down -v` deletes the database volume and all data stored in it, including test data.

## 2. Alternative: run with XAMPP

If you do not want to use Docker:

1. Install XAMPP (or another PHP and MySQL environment).
2. Copy `simple-online-library-system/` into the web server document root, for example `C:/xampp/htdocs/` on Windows or `/Applications/XAMPP/htdocs/` on macOS.
3. Start Apache and MySQL.
4. Open phpMyAdmin and import `simple-online-library-system/l_db.sql`. The SQL file creates the `library_system_db` database and the default administrator account.
5. If your MySQL credentials differ, update `simple-online-library-system/config/database.php`.
6. Open `http://localhost/simple-online-library-system/login.php` and verify that the application loads.

Default administrator credentials:

- **Email:** `admin@library.com`
- **Password:** `admin123`

Change the default password if you use this setup beyond local testing.

## 3. Prepare the Python test environment

From the `selenium/` directory, create and activate a virtual environment:

```bash
python3 -m venv .venv
```

macOS/Linux:

```bash
source .venv/bin/activate
```

Windows PowerShell:

```powershell
.venv\Scripts\Activate.ps1
```

Install the dependencies and create your local environment file:

```bash
python -m pip install --upgrade pip
python -m pip install -r requirements.txt
cp .env.example .env
```

On Windows, copy `.env.example` to `.env` using File Explorer or run `Copy-Item .env.example .env` in PowerShell.

Edit `.env` if your application URL or credentials differ. To run student workflow and authorization tests, create a student account through the application's Register page and set `STUDENT_EMAIL` and `STUDENT_PASSWORD` in `.env`.

Selenium 4 uses Selenium Manager to locate or download a compatible browser driver in typical setups. Install Chrome or Firefox before running the tests.

## 4. Execute the tests

Make sure the application is running before starting pytest.

Run the complete test suite:

```bash
python -m pytest -v -s
```

Run an individual test module:

```bash
python -m pytest -v -s tests/test_authentication.py
python -m pytest -v -s tests/test_registration.py
python -m pytest -v -s tests/test_student_workflows.py
python -m pytest -v -s tests/test_authorization.py
```

Use `python -m pytest` rather than the standalone `pytest` command when working inside a virtual environment. This ensures pytest runs with the Python interpreter currently selected by that environment.

To run without a visible browser window, set `HEADLESS=true` in `.env`. To watch the browser interactions, keep `HEADLESS=false`.

## Configuration

The values below are the defaults used by the Docker setup. Change them in your local `.env` file when needed.

| Variable | Default | Description |
|---|---|---|
| `BASE_URL` | `http://localhost:8080/simple-online-library-system` | Base URL of the PHP application (the folder containing `login.php`) |
| `BROWSER` | `chrome` | Browser to use: `chrome` or `firefox` |
| `HEADLESS` | `false` | Run without a visible browser window when set to `true` |
| `IMPLICIT_WAIT` | `0` | Selenium implicit wait in seconds |
| `EXPLICIT_WAIT` | `10` | Selenium explicit wait timeout in seconds |
| `SLOW_MO` | `0.7` | Pause in seconds around navigation, clicks, and typing; `0` disables the pauses |
| `ADMIN_EMAIL` | `admin@library.com` | Administrator test account email |
| `ADMIN_PASSWORD` | `admin123` | Administrator test account password |
| `STUDENT_EMAIL` | empty | Existing student account email |
| `STUDENT_PASSWORD` | empty | Existing student account password |

## Slow-motion browser execution

The test suite supports slow-motion execution so you can observe Selenium interacting with the application. The configured delay is applied before and after page navigation, clicks, and text entry.

Set the following in `.env`:

```env
HEADLESS=false
SLOW_MO=1.5
```

Suggested values:

| `SLOW_MO` | Behavior |
|---:|---|
| `0` | No added pauses; normal execution speed |
| `0.7` | Slightly slower; useful for observing actions |
| `1.5` | Slow; recommended for demonstrations |
| `2.0` | Very slow |
| `3.0` | Extra slow |

Run the tests with output visible in the terminal:

```bash
python -m pytest -v -s
```

Keep `HEADLESS=false` to see the browser. Increasing `SLOW_MO` adds time around browser actions; Selenium's explicit waits still operate independently.

## Important test-data notes

- The registration test creates a new account with a generated email address on each run.
- The borrowing test requires at least one available book and a valid student account.
- A successful borrow request changes application data: it creates a pending borrowing record and marks the book unavailable. Use a test database, not production data.
- Student tests are skipped when student credentials are not configured.
- The borrowing test is skipped if no available books are found. Add an available book through the administrator dashboard, then rerun the student workflow tests.
- These are browser UI tests. They do not replace API tests, security penetration tests, accessibility tests, or database-level tests.

## Duplicate-email registration test

The registration handler catches MySQL duplicate-key error `1062` and displays a user-friendly validation message. This matters with modern PHP/MySQLi versions, where a duplicate-key error can throw `mysqli_sql_exception` instead of simply returning `false` from `execute()`.

If the Docker stack is already running, the PHP source is bind-mounted into the container, so the updated handler takes effect without rebuilding the image. Re-run the registration tests:

```bash
python -m pytest -v -s tests/test_registration.py
```

## Project structure

```text
selenium/
├── simple-online-library-system/   # PHP application under test
├── tests/
│   ├── conftest.py                 # Browser setup, waits, and credentials
│   ├── helpers.py                  # Reusable login/logout actions
│   ├── test_authentication.py
│   ├── test_registration.py
│   ├── test_student_workflows.py
│   └── test_authorization.py
├── .env.example
├── compose.yaml
├── pytest.ini
├── requirements.txt
└── README.md
```

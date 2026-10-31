"""Login and logout tests for the real library application's forms."""
from selenium.webdriver.common.by import By
from selenium.webdriver.support import expected_conditions as EC

from .helpers import login


def test_login_page_loads(driver, base_url):
    driver.get(f"{base_url}/login.php")
    assert driver.find_element(By.TAG_NAME, "h2").text.strip() == "Login"
    assert driver.find_element(By.NAME, "email").is_displayed()
    assert driver.find_element(By.NAME, "password").is_displayed()


def test_admin_can_login(driver, wait, base_url, admin_credentials):
    email, password = admin_credentials
    login(driver, wait, base_url, email, password)
    wait.until(EC.url_contains("/admin/index.php"))
    assert "Dashboard" in driver.page_source or "Manage Books" in driver.page_source


def test_invalid_password_shows_error(driver, wait, base_url, admin_credentials):
    email, _ = admin_credentials
    login(driver, wait, base_url, email, "definitely-wrong-password")
    error = wait.until(EC.visibility_of_element_located((By.CSS_SELECTOR, ".alert-danger")))
    assert "password" in error.text.lower() or "account" in error.text.lower()
    assert driver.current_url.endswith("/login.php")


def test_logout_ends_session(driver, wait, base_url, admin_credentials):
    email, password = admin_credentials
    login(driver, wait, base_url, email, password)
    wait.until(EC.url_contains("/admin/index.php"))
    driver.get(f"{base_url}/logout.php")
    wait.until(EC.url_contains("/login.php"))
    driver.get(f"{base_url}/admin/index.php")
    wait.until(EC.url_contains("/login.php"))
    assert driver.current_url.endswith("/login.php")

"""Registration tests use a unique email to avoid modifying existing accounts."""
from uuid import uuid4
from selenium.webdriver.common.by import By
from selenium.webdriver.support import expected_conditions as EC


def test_student_registration_succeeds(driver, wait, base_url):
    email = f"selenium-{uuid4().hex[:10]}@example.test"
    password = "LibraryTest@123"
    driver.get(f"{base_url}/register.php")
    driver.find_element(By.NAME, "full_name").send_keys("Selenium Test Student")
    driver.find_element(By.NAME, "email").send_keys(email)
    driver.find_element(By.NAME, "password").send_keys(password)
    driver.find_element(By.NAME, "role").send_keys("student")
    driver.find_element(By.CSS_SELECTOR, "form input[type='submit']").click()
    wait.until(EC.url_contains("/login.php"))
    assert driver.current_url.endswith("/login.php")


def test_duplicate_email_is_rejected(driver, wait, base_url, admin_credentials):
    email, _ = admin_credentials
    driver.get(f"{base_url}/register.php")
    driver.find_element(By.NAME, "full_name").send_keys("Duplicate Account Test")
    driver.find_element(By.NAME, "email").send_keys(email)
    driver.find_element(By.NAME, "password").send_keys("LibraryTest@123")
    driver.find_element(By.NAME, "role").send_keys("student")
    driver.find_element(By.CSS_SELECTOR, "form input[type='submit']").click()
    error = wait.until(EC.visibility_of_element_located((By.CSS_SELECTOR, ".alert-danger")))
    assert "already registered" in error.text.lower()

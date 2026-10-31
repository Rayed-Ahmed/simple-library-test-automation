"""Basic role-based access checks."""
from selenium.webdriver.support import expected_conditions as EC
from .helpers import login


def test_student_cannot_access_admin_dashboard(driver, wait, base_url, student_credentials):
    email, password = student_credentials
    login(driver, wait, base_url, email, password)
    wait.until(EC.url_contains("/student/index.php"))
    driver.get(f"{base_url}/admin/index.php")
    wait.until(EC.url_contains("/student/index.php"))
    assert driver.current_url.endswith("/student/index.php")

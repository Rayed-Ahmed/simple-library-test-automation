"""Reusable actions for the Simple Online Library System."""
from selenium.webdriver.common.by import By
from selenium.webdriver.support import expected_conditions as EC


def login(driver, wait, base_url, email, password):
    driver.get(f"{base_url}/login.php")
    wait.until(EC.visibility_of_element_located((By.NAME, "email"))).send_keys(email)
    driver.find_element(By.NAME, "password").send_keys(password)
    driver.find_element(By.CSS_SELECTOR, "form input[type='submit']").click()


def logout(driver, wait, base_url):
    driver.get(f"{base_url}/logout.php")
    wait.until(EC.url_contains("/login.php"))

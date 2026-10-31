"""Student role access and borrowing-request tests."""
from selenium.webdriver.common.by import By
from selenium.webdriver.support import expected_conditions as EC

from .helpers import login


def test_student_can_open_browse_books(driver, wait, base_url, student_credentials):
    email, password = student_credentials
    login(driver, wait, base_url, email, password)
    wait.until(EC.url_contains("/student/index.php"))
    driver.get(f"{base_url}/student/browse_books.php")
    wait.until(EC.visibility_of_element_located((By.TAG_NAME, "table")))
    assert "Browse Available Books" in driver.page_source


def test_student_can_submit_borrow_request_when_book_available(driver, wait, base_url, student_credentials):
    email, password = student_credentials
    login(driver, wait, base_url, email, password)
    wait.until(EC.url_contains("/student/index.php"))
    driver.get(f"{base_url}/student/browse_books.php")
    available_links = driver.find_elements(By.XPATH, "//a[contains(@href, 'borrow_book.php?book_id=')]")
    if not available_links:
        import pytest
        pytest.skip("No available books found. Add an available book using the admin account.")
    available_links[0].click()
    message = wait.until(EC.visibility_of_element_located((By.CSS_SELECTOR, ".message-container p")))
    assert "request sent successfully" in message.text.lower()

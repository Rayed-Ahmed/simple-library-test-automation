"""Shared Selenium setup and configuration for the library system tests."""
import os
import time
from pathlib import Path

import pytest
from dotenv import load_dotenv
from selenium import webdriver
from selenium.webdriver.chrome.options import Options as ChromeOptions
from selenium.webdriver.firefox.options import Options as FirefoxOptions
from selenium.webdriver.support.events import AbstractEventListener, EventFiringWebDriver
from selenium.webdriver.support.ui import WebDriverWait

load_dotenv(Path(__file__).resolve().parents[1] / ".env")

BASE_URL = os.getenv("BASE_URL", "http://localhost/simple-online-library-system").rstrip("/")
EXPLICIT_WAIT = int(os.getenv("EXPLICIT_WAIT", "10"))
SLOW_MO = max(0.0, float(os.getenv("SLOW_MO", "0.7")))


class SlowMotionListener(AbstractEventListener):
    """Pause around common browser actions so test execution is visible."""

    def _pause(self):
        if SLOW_MO:
            time.sleep(SLOW_MO)

    def before_navigate_to(self, url, driver):
        self._pause()

    def after_navigate_to(self, url, driver):
        self._pause()

    def before_click(self, element, driver):
        self._pause()

    def after_click(self, element, driver):
        self._pause()

    def before_change_value_of(self, element, driver):
        self._pause()

    def after_change_value_of(self, element, driver):
        self._pause()



def pytest_configure():
    pytest.BASE_URL = BASE_URL


@pytest.fixture
def base_url():
    return BASE_URL


@pytest.fixture
def driver():
    browser = os.getenv("BROWSER", "chrome").lower()
    headless = os.getenv("HEADLESS", "false").lower() in {"1", "true", "yes"}

    if browser == "firefox":
        options = FirefoxOptions()
        if headless:
            options.add_argument("-headless")
        instance = webdriver.Firefox(options=options)
    elif browser == "chrome":
        options = ChromeOptions()
        if headless:
            options.add_argument("--headless=new")
        options.add_argument("--window-size=1440,1000")
        options.add_argument("--disable-notifications")
        instance = webdriver.Chrome(options=options)
    else:
        raise ValueError("BROWSER must be 'chrome' or 'firefox'.")

    instance.implicitly_wait(int(os.getenv("IMPLICIT_WAIT", "0")))
    slow_driver = EventFiringWebDriver(instance, SlowMotionListener())
    yield slow_driver
    slow_driver.quit()


@pytest.fixture
def wait(driver):
    return WebDriverWait(driver, EXPLICIT_WAIT)


@pytest.fixture
def admin_credentials():
    email = os.getenv("ADMIN_EMAIL", "admin@library.com")
    password = os.getenv("ADMIN_PASSWORD", "admin123")
    return email, password


@pytest.fixture
def student_credentials():
    email = os.getenv("STUDENT_EMAIL", "").strip()
    password = os.getenv("STUDENT_PASSWORD", "")
    if not email or not password:
        pytest.skip("Set STUDENT_EMAIL and STUDENT_PASSWORD in .env to run student workflow tests.")
    return email, password

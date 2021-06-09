// Generated by Selenium IDE
const { Builder, By, Key, until } = require('selenium-webdriver')
const assert = require('assert')

describe('LogovanjeKorisnikaUsername_uspesno', function() {
  this.timeout(30000)
  let driver
  let vars
  beforeEach(async function() {
    driver = await new Builder().forBrowser('chrome').build()
    vars = {}
  })
  afterEach(async function() {
    await driver.quit();
  })
  it('LogovanjeKorisnikaUsername_uspesno', async function() {
    await driver.get("http://localhost/bela_berza/public/")
    await driver.findElement(By.css("li:nth-child(3) span")).click()
    await driver.findElement(By.id("nameInputField")).click()
    await driver.findElement(By.id("nameInputField")).sendKeys("ppan")
    await driver.findElement(By.id("surnameInputField")).sendKeys("pan123")
    await driver.findElement(By.css(".btn-outline-success")).click()
    assert(await driver.findElement(By.css("li:nth-child(2) span")).getText() == "Moja riznica")
  })
})

// Generated by Selenium IDE
const { Builder, By, Key, until } = require('selenium-webdriver')
const assert = require('assert')

describe('IzmenaEmaila_greskaFormat', function() {
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
  it('IzmenaEmaila_greskaFormat', async function() {
    await driver.get("http://localhost/bela_berza/public/profile")
    await driver.manage().window().setRect(1936, 1056)
    await driver.findElement(By.css("#profName .svg-inline--fa")).click()
    await driver.findElement(By.name("newEmail")).click()
    await driver.findElement(By.name("newEmail")).sendKeys("petarpan@pmail")
    await driver.findElement(By.name("passwordConfirmation")).sendKeys("Petar12")
    await driver.findElement(By.css("#emailModal .btn-outline-success")).click()
    assert(await driver.findElement(By.css(".alert")).getText() == "×\\\\nPromena odbijena - niste uneli podatke u odgovarajucem formatu!")
  })
})

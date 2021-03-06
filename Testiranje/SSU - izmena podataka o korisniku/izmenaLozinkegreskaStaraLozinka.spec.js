// Generated by Selenium IDE
const { Builder, By, Key, until } = require('selenium-webdriver')
const assert = require('assert')

describe('IzmenaLozinke_greskaStaraLozinka', function() {
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
  it('IzmenaLozinke_greskaStaraLozinka', async function() {
    await driver.get("http://localhost/bela_berza/public/profile")
    await driver.manage().window().setRect(1936, 1056)
    await driver.findElement(By.css("#profPassword .svg-inline--fa")).click()
    await driver.findElement(By.name("oldPassword")).click()
    await driver.findElement(By.name("oldPassword")).sendKeys("paaaaaaaaaaa")
    await driver.findElement(By.name("newPassword")).sendKeys("Petar12")
    await driver.findElement(By.name("newPasswordConfirmation")).sendKeys("Petar12")
    await driver.findElement(By.css(".form-control")).click()
    assert(await driver.findElement(By.css(".alert")).getText() == "×\\\\nPromena odbijena - uneta lozinka se ne poklapa sa vasom lozinkom!")
  })
})

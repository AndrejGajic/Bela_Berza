// Generated by Selenium IDE
const { Builder, By, Key, until } = require('selenium-webdriver')
const assert = require('assert')

describe('SSU_PotvrdaRegistracijeKorisnika_NemaRegistracija', function() {
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
  it('SSU_PotvrdaRegistracijeKorisnika_NemaRegistracija', async function() {
    await driver.get("http://localhost/Bela_Berza/Implementacija/public/")
    await driver.manage().window().setRect(1552, 840)
    await driver.findElement(By.css("li:nth-child(2) > .menu-item > span")).click()
    await driver.findElement(By.css(".alert")).click()
    assert(await driver.findElement(By.css(".alert")).getText() == "Nema nijedne registracije na cekanju")
  })
})

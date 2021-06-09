// Generated by Selenium IDE
const { Builder, By, Key, until } = require('selenium-webdriver')
const assert = require('assert')

describe('PregledUplata_uspesno', function() {
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
  it('PregledUplata_uspesno', async function() {
    await driver.get("http://localhost/bela_berza/public/WalletController")
    await driver.manage().window().setRect(1936, 1056)
    await driver.findElement(By.name("tip")).click()
    {
      const dropdown = await driver.findElement(By.name("tip"))
      await dropdown.findElement(By.xpath("//option[. = 'Uplate']")).click()
    }
    await driver.findElement(By.name("tip")).click()
    await driver.findElement(By.css("form:nth-child(5) .btn")).click()
    assert(await driver.findElement(By.css(".wallet-in:nth-child(2)")).getText() == "Uplata")
  })
})

// Generated by Selenium IDE
const { Builder, By, Key, until } = require('selenium-webdriver')
const assert = require('assert')

describe('Kupovina_uspesna', function() {
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
  it('Kupovina_uspesna', async function() {
    await driver.get("http://localhost/bela_berza/public/Home")
    await driver.findElement(By.id("btnUber")).click()
    await driver.findElement(By.css(".fa-plus > path")).click()
    await driver.findElement(By.css(".fa-plus > path")).click()
    await driver.findElement(By.css(".fa-plus > path")).click()
    await driver.findElement(By.css(".btn-outline-success")).click()
    assert(await driver.findElement(By.css(".alert > strong")).getText() == "Kupovina je uspešno okončana, akcije su dodate u kolekciju i sredstva na vašem računu su ažurirana!")
  })
})

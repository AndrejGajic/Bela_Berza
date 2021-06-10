// Generated by Selenium IDE
const { Builder, By, Key, until } = require('selenium-webdriver')
const assert = require('assert')

describe('SSU_UplataNovcaNaRacun_Uspesno', function() {
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
  it('SSU_UplataNovcaNaRacun_Uspesno', async function() {
    await driver.get("http://localhost/Bela_Berza/Implementacija/public/")
    await driver.manage().window().setRect(1552, 840)
    await driver.findElement(By.css("li:nth-child(3) span")).click()
    await driver.findElement(By.css(".btn-success")).click()
    await driver.findElement(By.name("amountInputFieldPayment")).click()
    await driver.findElement(By.name("amountInputFieldPayment")).sendKeys("300")
    await driver.findElement(By.id("nameInputFieldPayment")).click()
    await driver.findElement(By.id("nameInputFieldPayment")).click()
    {
      const element = await driver.findElement(By.id("nameInputFieldPayment"))
      await driver.actions({ bridge: true}).doubleClick(element).perform()
    }
    await driver.findElement(By.id("nameInputFieldPayment")).sendKeys("Andrej")
    await driver.findElement(By.id("nameInputFieldPayment")).sendKeys(Key.ENTER)
    await driver.findElement(By.id("surnameInputFieldPayment")).sendKeys("Gajic")
    await driver.findElement(By.id("surnameInputFieldPayment")).sendKeys(Key.ENTER)
    await driver.findElement(By.id("creditCardNumberInput")).sendKeys("1234-5678-1234-5679")
    await driver.findElement(By.id("creditCardNumberInput")).sendKeys(Key.ENTER)
    await driver.findElement(By.id("CVC")).sendKeys("123")
    await driver.findElement(By.css(".modal-footer:nth-child(7) > .btn-outline-success")).click()
    assert(await driver.findElement(By.css(".alert > strong")).getText() == "Uplata je uspešno izvršena!")
  })
})

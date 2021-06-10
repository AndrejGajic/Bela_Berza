// Generated by Selenium IDE
const { Builder, By, Key, until } = require('selenium-webdriver')
const assert = require('assert')

describe('SSU_UplataNovcaNaRacun_KarticaNePostoji', function() {
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
  it('SSU_UplataNovcaNaRacun_KarticaNePostoji', async function() {
    await driver.get("http://localhost/Bela_Berza/Implementacija/public/home")
    await driver.manage().window().setRect(1536, 824)
    await driver.findElement(By.linkText("Moj novcanik")).click()
    await driver.findElement(By.css(".btn-success")).click()
    await driver.findElement(By.name("amountInputFieldPayment")).click()
    await driver.findElement(By.name("amountInputFieldPayment")).sendKeys("1000")
    await driver.findElement(By.name("amountInputFieldPayment")).sendKeys(Key.ENTER)
    await driver.findElement(By.id("nameInputFieldPayment")).sendKeys("Andrej")
    await driver.findElement(By.id("nameInputFieldPayment")).sendKeys(Key.ENTER)
    await driver.findElement(By.id("surnameInputFieldPayment")).sendKeys("Gajic")
    await driver.findElement(By.id("surnameInputFieldPayment")).sendKeys(Key.ENTER)
    await driver.findElement(By.id("creditCardNumberInput")).sendKeys("1234-5678-1234-1111")
    await driver.findElement(By.id("creditCardNumberInput")).sendKeys(Key.ENTER)
    await driver.findElement(By.id("CVC")).sendKeys("123")
    await driver.findElement(By.id("CVC")).sendKeys(Key.ENTER)
    {
      const elements = await driver.findElements(By.css(".alert"))
      assert(elements.length)
    }
    assert(await driver.findElement(By.css(".alert")).getText() == "×\\\\nNeuspešna transakcija! Kartica ne postoji!")
  })
})

// Generated by Selenium IDE
const { Builder, By, Key, until } = require('selenium-webdriver')
const assert = require('assert')

describe('SSU_UplataNovcaNaRacun_NeodgovarajuciVlasnik', function() {
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
  it('SSU_UplataNovcaNaRacun_NeodgovarajuciVlasnik', async function() {
    await driver.get("http://localhost/Bela_Berza/Implementacija/public/")
    await driver.manage().window().setRect(1536, 824)
    await driver.findElement(By.css("li:nth-child(3) span")).click()
    await driver.findElement(By.css(".btn-success")).click()
    await driver.findElement(By.name("amountInputFieldPayment")).click()
    await driver.findElement(By.name("amountInputFieldPayment")).sendKeys("1000")
    await driver.findElement(By.name("amountInputFieldPayment")).sendKeys(Key.ENTER)
    await driver.findElement(By.id("nameInputFieldPayment")).sendKeys("Petar")
    await driver.findElement(By.id("nameInputFieldPayment")).sendKeys(Key.ENTER)
    await driver.findElement(By.id("surnameInputFieldPayment")).sendKeys("Pan")
    await driver.findElement(By.id("surnameInputFieldPayment")).sendKeys(Key.ENTER)
    await driver.findElement(By.id("creditCardNumberInput")).sendKeys("1234-5678-1234-5679")
    await driver.findElement(By.id("creditCardNumberInput")).sendKeys(Key.ENTER)
    await driver.findElement(By.id("CVC")).sendKeys("123")
    await driver.findElement(By.id("CVC")).sendKeys(Key.ENTER)
    {
      const elements = await driver.findElements(By.css(".alert"))
      assert(elements.length)
    }
    assert(await driver.findElement(By.css(".alert")).getText() == "×\\\\nNeuspešna transakcija! Uneta osoba nije vlasnik kartice-netačno ime!")
  })
})
